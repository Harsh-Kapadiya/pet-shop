<?php
// You will need to ensure 'includes/header.php' and 'includes/footer.php' exist
// and contain your site's HTML structure.
include 'includes/header.php';
require 'includes/db_connect.php'; // This will establish $pdo and start the session

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirect to your login page if not logged in
    exit;
}

$user_id = $_SESSION['user']['id']; // Assuming user ID is stored in $_SESSION['user']['id']
$specializations = [];
$error_message = '';
$success_message = '';

// --- Handle Appointment Booking POST Request ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $doctor_id = $_POST['doctor_id'] ?? null;
    $appointment_date = $_POST['appointment_date'] ?? null;
    $appointment_time = $_POST['appointment_time'] ?? null;

    if ($doctor_id && $appointment_date && $appointment_time) {
        try {
            // Check if the doctor is available and has capacity for the selected slot
            // (Re-checking capacity at booking time to prevent race conditions)
            $stmt = $pdo->prepare("
                SELECT d.max_patients_per_day, d.start_time, d.end_time
                FROM Doctors d
                WHERE d.doctor_id = ?
            ");
            $stmt->execute([$doctor_id]);
            $doctor_info = $stmt->fetch();

            if (!$doctor_info) {
                throw new Exception("Selected doctor not found.");
            }

            // Count existing appointments for this doctor on this date
            $stmt = $pdo->prepare("
                SELECT COUNT(*) AS booked_count
                FROM appointments
                WHERE doctor_id = ? AND appointment_date = ? AND status IN ('pending', 'confirmed')
            ");
            $stmt->execute([$doctor_id, $appointment_date]);
            $booked_count = $stmt->fetchColumn();

            if ($booked_count >= $doctor_info['max_patients_per_day']) {
                throw new Exception("This doctor is fully booked for " . htmlspecialchars($appointment_date) . ".");
            }

            // Also check for duplicate appointments for the same user with the same doctor at the same time
            $stmt = $pdo->prepare("
                SELECT COUNT(*) FROM appointments
                WHERE user_id = ? AND doctor_id = ? AND appointment_date = ? AND appointment_time = ? AND status IN ('pending', 'confirmed')
            ");
            $stmt->execute([$user_id, $doctor_id, $appointment_date, $appointment_time]);
            if ($stmt->fetchColumn() > 0) {
                 throw new Exception("You already have an appointment with this doctor at this specific time on this date.");
            }

            // Basic time slot validation (ensure submitted time is within doctor's working hours)
            if (strtotime($appointment_time) < strtotime($doctor_info['start_time']) ||
                strtotime($appointment_time) >= strtotime($doctor_info['end_time'])) { // Use >= end_time as last slot should be before end_time
                throw new Exception("Selected time is outside the doctor's working hours.");
            }

            // Insert the appointment
            $ins = $pdo->prepare("INSERT INTO appointments (user_id, doctor_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, 'pending')");
            $ins->execute([$user_id, $doctor_id, $appointment_date, $appointment_time]);
            $success_message = "Your appointment has been booked successfully! We look forward to seeing you.";

            // Redirect to prevent form re-submission on refresh
            header('Location: appointment.php?booked=1');
            exit;

        } catch (Exception $e) {
            $error_message = "Booking failed: " . $e->getMessage();
        }
    } else {
        $error_message = "Please select a doctor, date, and time.";
    }
}


// --- Fetch Specializations for the dropdown ---
try {
    $stmt = $pdo->query("SELECT DISTINCT specialization FROM Doctors ORDER BY specialization");
    $specializations = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $error_message = "Failed to load specializations: " . $e->getMessage();
}

// --- Dynamic content for AJAX requests (to get available doctors and times) ---
if (isset($_GET['action']) && $_GET['action'] == 'get_available_doctors' && isset($_GET['specialization']) && isset($_GET['date'])) {
    header('Content-Type: application/json');

    $selected_specialization = $_GET['specialization'];
    $selected_date = $_GET['date'];
    $day_of_week = date('l', strtotime($selected_date)); // e.g., 'Monday'

    $available_doctors_data = [];

    try {
        // Query doctors by specialization and working day
        $stmt = $pdo->prepare("
            SELECT doctor_id, doctor_name, specialization, start_time, end_time, max_patients_per_day
            FROM Doctors
            WHERE specialization = ? AND FIND_IN_SET(?, working_days) > 0
            ORDER BY doctor_name
        ");
        $stmt->execute([$selected_specialization, $day_of_week]);
        $doctors_on_day = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($doctors_on_day as $doctor) {
            // Count total existing appointments for this doctor on this date
            $stmt = $pdo->prepare("
                SELECT COUNT(*) AS total_booked_today
                FROM appointments
                WHERE doctor_id = ? AND appointment_date = ? AND status IN ('pending', 'confirmed')
            ");
            $stmt->execute([$doctor['doctor_id'], $selected_date]);
            $total_booked_today = $stmt->fetchColumn();

            // If doctor is already fully booked for the day, skip them
            if ($total_booked_today >= $doctor['max_patients_per_day']) {
                continue;
            }

            // Get specific booked time slots for this doctor on this date
            $stmt = $pdo->prepare("
                SELECT appointment_time, COUNT(*) as bookings_at_time
                FROM appointments
                WHERE doctor_id = ? AND appointment_date = ? AND status IN ('pending', 'confirmed')
                GROUP BY appointment_time
            ");
            $stmt->execute([$doctor['doctor_id'], $selected_date]);
            $booked_slots_details = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // ['10:00:00' => 3, '11:00:00' => 2]

            $available_time_slots = [];
            $current_time_stamp = strtotime($doctor['start_time']);
            $end_time_stamp = strtotime($doctor['end_time']);
            $appointment_duration_seconds = 30 * 60; // 30 minutes per appointment

            while ($current_time_stamp < $end_time_stamp) {
                $slot_time_db_format = date('H:i:00', $current_time_stamp);
                $slot_time_display_format = date('h:i A', $current_time_stamp);

                $bookings_for_this_specific_slot = $booked_slots_details[$slot_time_db_format] ?? 0;

                // For simplicity, let's assume each 30-min slot can only take one patient.
                // If a doctor can see multiple patients at the *exact same time slot*,
                // you would need a more complex scheduling logic (e.g., specific slots per doctor).
                // Here, we check if the slot has *any* booking. If max_patients_per_day is for the day,
                // and not per 30-min block, this logic needs adjustment.
                // For now, if max_patients_per_day implies total daily capacity *regardless of time*,
                // then the $total_booked_today check is primary, and individual slots are just to show availability.
                // Let's go with the simpler model: one booking per time slot.
                if ($bookings_for_this_specific_slot == 0) { // Slot is free
                    $available_time_slots[$slot_time_db_format] = $slot_time_display_format;
                }

                $current_time_stamp += $appointment_duration_seconds;
            }

            // Only add doctor if they still have total daily capacity and available slots
            if ($total_booked_today < $doctor['max_patients_per_day'] && !empty($available_time_slots)) {
                 $available_doctors_data[] = [
                    'doctor_id' => $doctor['doctor_id'],
                    'doctor_name' => $doctor['doctor_name'],
                    'specialization' => $doctor['specialization'],
                    'working_hours' => date('h:i A', strtotime($doctor['start_time'])) . ' - ' . date('h:i A', strtotime($doctor['end_time'])),
                    'available_slots' => $available_time_slots
                ];
            }
        }
        echo json_encode(['success' => true, 'doctors' => $available_doctors_data]);

    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => "Database error: " . $e->getMessage()]);
    }
    exit; // Crucial to exit after AJAX response
}
?>

<section class="page-appointment container">
    <h2 class="page-title">Book a Doctor Appointment</h2>

    <?php if ($success_message || (isset($_GET['booked']) && $_GET['booked'] == 1)): ?>
        <div class="alert success text-center mb-md">
            <?= htmlspecialchars($success_message ?: "Your appointment has been booked successfully! We look forward to seeing you."); ?>
        </div>
        <?php
            // Clear the GET parameter after displaying message
            if (isset($_GET['booked'])) {
                unset($_GET['booked']);
                // You might also consider refreshing the page without the parameter
                // header('Location: appointment.php'); exit;
            }
        ?>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <div class="alert error text-center mb-md">
            <?= htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form method="post" class="appointment-form" id="doctorAppointmentForm">
        <div class="form-group">
            <label for="specialization" class="form-label">Select Specialization:</label>
            <select name="specialization" id="specialization" class="form-input-select" required>
                <option value="">-- Choose Specialization --</option>
                <?php foreach ($specializations as $spec): ?>
                    <option value="<?= htmlspecialchars($spec); ?>"><?= htmlspecialchars($spec); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="appointment_date" class="form-label">Select Preferred Date:</label>
            <input type="date" name="appointment_date" id="appointment_date" required class="form-input-date" min="<?= date('Y-m-d'); ?>">
        </div>

        <div class="form-group" id="doctor-selection-container" style="display: none;">
            <label class="form-label">Available Doctors & Timings:</label>
            <div id="available-doctors">
                <p>Please select a specialization and date to see available doctors.</p>
            </div>
        </div>

        <!-- Hidden inputs for selected doctor and time, populated by JS -->
        <input type="hidden" name="doctor_id" id="selected_doctor_id">
        <input type="hidden" name="appointment_time" id="selected_appointment_time">

        <button type="submit" name="book_appointment" class="btn btn-primary-cta" id="bookAppointmentBtn" disabled>Book Appointment</button>
    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const specializationSelect = document.getElementById('specialization');
    const dateInput = document.getElementById('appointment_date');
    const doctorSelectionContainer = document.getElementById('doctor-selection-container');
    const availableDoctorsDiv = document.getElementById('available-doctors');
    const selectedDoctorIdInput = document.getElementById('selected_doctor_id');
    const selectedAppointmentTimeInput = document.getElementById('selected_appointment_time');
    const bookAppointmentBtn = document.getElementById('bookAppointmentBtn');

    // Function to fetch and display available doctors
    function fetchAvailableDoctors() {
        const specialization = specializationSelect.value;
        const date = dateInput.value;

        // Clear previous selections and disable button if inputs change
        selectedDoctorIdInput.value = '';
        selectedAppointmentTimeInput.value = '';
        bookAppointmentBtn.disabled = true;

        if (specialization && date) {
            doctorSelectionContainer.style.display = 'block';
            availableDoctorsDiv.innerHTML = '<p class="text-center">Loading available doctors...</p>';


            fetch(`appointment.php?action=get_available_doctors&specialization=${encodeURIComponent(specialization)}&date=${encodeURIComponent(date)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        if (data.doctors.length > 0) {
                            availableDoctorsDiv.innerHTML = ''; // Clear previous content
                            data.doctors.forEach(doctor => {
                                const doctorCard = document.createElement('div');
                                doctorCard.classList.add('doctor-card', 'mb-sm');
                                doctorCard.innerHTML = `
                                    <h4>Dr. ${doctor.doctor_name} <span class="doctor-specialization">(${doctor.specialization})</span></h4>
                                    <p>Working Hours: ${doctor.working_hours}</p>
                                    <div class="time-slots" data-doctor-id="${doctor.doctor_id}">
                                        ${Object.entries(doctor.available_slots).map(([time_value, time_display]) => `
                                            <button type="button" class="time-slot-btn"
                                                    data-doctor-id="${doctor.doctor_id}"
                                                    data-time-value="${time_value}">
                                                ${time_display}
                                            </button>
                                        `).join('')}
                                    </div>
                                `;
                                availableDoctorsDiv.appendChild(doctorCard);
                            });

                            // Add event listeners to newly created time slot buttons
                            document.querySelectorAll('.time-slot-btn').forEach(button => {
                                button.addEventListener('click', function() {
                                    // Remove 'selected' class from all other buttons
                                    document.querySelectorAll('.time-slot-btn').forEach(btn => btn.classList.remove('selected'));
                                    // Add 'selected' class to the clicked button
                                    this.classList.add('selected');

                                    // Populate hidden inputs
                                    selectedDoctorIdInput.value = this.dataset.doctorId;
                                    selectedAppointmentTimeInput.value = this.dataset.timeValue;
                                    bookAppointmentBtn.disabled = false; // Enable book button
                                });
                            });

                        } else {
                            availableDoctorsDiv.innerHTML = '<p class="text-center">No doctors available for the selected specialization and date.</p>';
                        }
                    } else {
                        availableDoctorsDiv.innerHTML = `<p class="alert error text-center">Error: ${data.error}</p>`;
                        console.error('Server error:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error fetching doctors:', error);
                    availableDoctorsDiv.innerHTML = `<p class="alert error text-center">An error occurred while fetching doctors. Please try again.</p>`;
                });
        } else {
            doctorSelectionContainer.style.display = 'none';
            availableDoctorsDiv.innerHTML = '<p class="text-center">Please select a specialization and date to see available doctors.</p>';
            bookAppointmentBtn.disabled = true;
        }
    }

    // Event listeners for specialization and date changes
    specializationSelect.addEventListener('change', fetchAvailableDoctors);
    dateInput.addEventListener('change', fetchAvailableDoctors);

    // Initial load for the date input to today
    dateInput.value = new Date().toISOString().split('T')[0];
    // And fetch doctors immediately if a specialization might be pre-selected or to show initial state
    // fetchAvailableDoctors(); // Call this if you want to load results immediately on page load
});
</script>

<?php include 'includes/footer.php'; ?>

<style>
    /* Base variables (ensure these are defined globally or above this block) */
    :root {
        --primary-green: #556B2F; /* Olive Green */
        --secondary-green: #6B8E23; /* Dark Olive Green */
        --accent-green: #3CB371; /* Medium Sea Green for success */
        --dark-text: #333333;
        --light-text: #666666;
        --white: #FFFFFF;
        --grey-100: #F8F8F8;
        --grey-200: #EEEEEE;
        --grey-300: #DDDDDD;
        --border-color: #CCCCCC;

        --spacing-xs: 5px;
        --spacing-sm: 10px;
        --spacing-md: 20px;
        --spacing-lg: 30px;
        --spacing-xl: 40px;
        --spacing-xxl: 60px;

        --font-heading: 'Georgia', serif; /* Example heading font */
        --font-body: 'Arial', sans-serif; /* Example body font */
    }

    /* New/Modified styles for appointment.php */

    /* Appointment Page Container */
    .page-appointment {
        padding-top: var(--spacing-xl);
        padding-bottom: var(--spacing-xxl);
        background-color: var(--white);
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        margin-top: var(--spacing-xl);
        text-align: center; /* Center content within the page section */
        max-width: 900px; /* Increased width to better accommodate doctor cards */
        margin-left: auto;
        margin-right: auto;
        font-family: var(--font-body);
    }

    /* Page Title (reusing .page-title from shop.php if available) */
    .page-title {
        font-family: var(--font-heading);
        font-size: 2.8rem;
        color: var(--dark-text);
        text-align: center;
        margin-bottom: var(--spacing-xl);
        padding-top: var(--spacing-md);
    }

    /* Alert Messages */
    .alert {
        padding: var(--spacing-sm) var(--spacing-md);
        border-radius: 6px;
        margin-bottom: var(--spacing-md);
        font-weight: 500;
        text-align: center;
        font-size: 0.95rem;
    }
    .alert.success {
        background-color: #E6F7EB; /* Lighter green */
        color: var(--accent-green);
        border: 1px solid #B7E8C2;
        max-width: 600px; /* Limit alert width */
        margin-left: auto;
        margin-right: auto;
        margin-bottom: var(--spacing-xl); /* More space after alert */
    }
    .alert.error {
        background-color: #FFE6E6; /* Light red */
        color: #CC0000; /* Darker red */
        border: 1px solid #FFB7B7;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: var(--spacing-xl);
    }

    /* Appointment Form Styling */
    .appointment-form {
        background-color: var(--grey-100); /* Light background for the form itself */
        padding: var(--spacing-xl) var(--spacing-lg);
        border-radius: 10px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05); /* Subtle inner shadow */
        display: flex;
        flex-direction: column;
        align-items: center; /* Center form elements */
        gap: var(--spacing-md); /* Space between form groups and button */
        max-width: 650px; /* Adjusted max width for form */
        margin: 0 auto; /* Center the form within its container */
        border: 1px solid var(--grey-200);
    }

    .form-group {
        width: 100%; /* Full width for form group */
        text-align: left; /* Align label to the left */
        margin-bottom: var(--spacing-xs); /* Space below each form group */
    }

    .form-label {
        display: block; /* Make label take full width */
        font-size: 1.1rem;
        color: var(--dark-text);
        margin-bottom: var(--spacing-sm);
        font-weight: 600;
    }

    .form-input-date,
    .form-input-select { /* Applied common styling to select as well */
        width: 100%;
        padding: var(--spacing-sm);
        border: 1px solid var(--grey-300);
        border-radius: 8px; /* Slightly more rounded inputs */
        font-size: 1rem;
        color: var(--dark-text);
        background-color: var(--white);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        height: 50px; /* Fixed height for input */
        appearance: none; /* Remove default browser select arrow */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23666666'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 20px;
        padding-right: 40px; /* Make space for custom arrow */
    }

    .form-input-date:focus,
    .form-input-select:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(85, 107, 47, 0.2); /* Olive green focus ring */
        outline: none;
    }

    /* Button Styling */
    .btn-primary-cta {
        background-color: var(--primary-green);
        color: var(--white);
        border: 1px solid var(--primary-green);
        padding: var(--spacing-sm) var(--spacing-xl); /* Generous padding */
        border-radius: 8px;
        font-size: 1.1rem; /* Larger font for CTA button */
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        min-height: 50px; /* Ensure good touch target */
        width: 100%; /* Full width within the form */
        max-width: 300px; /* But limit max width */
        margin-top: var(--spacing-sm); /* Space above button */
        text-decoration: none; /* In case it's an anchor */
        display: inline-block; /* For proper padding/width */
    }

    .btn-primary-cta:hover:not(:disabled) {
        background-color: var(--secondary-green);
        border-color: var(--secondary-green);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .btn-primary-cta:disabled {
        background-color: var(--grey-300);
        border-color: var(--grey-300);
        color: var(--light-text);
        cursor: not-allowed;
        opacity: 0.7;
        transform: none; /* Remove hover transform when disabled */
        box-shadow: none;
    }

    /* Doctor Selection Container */
    #doctor-selection-container {
        width: 100%;
        margin-top: var(--spacing-md);
        text-align: left;
    }

    #available-doctors p {
        color: var(--light-text);
        text-align: center;
        margin: var(--spacing-md) 0;
        font-style: italic;
    }

    /* Doctor Card Styling */
    .doctor-card {
        background-color: var(--white);
        border: 1px solid var(--grey-200);
        border-radius: 8px;
        padding: var(--spacing-md);
        margin-bottom: var(--spacing-sm);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .doctor-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .doctor-card h4 {
        margin-top: 0;
        margin-bottom: var(--spacing-xs);
        color: var(--primary-green);
        font-size: 1.25rem;
        font-family: var(--font-heading);
    }

    .doctor-specialization {
        font-size: 0.9em;
        color: var(--light-text);
        font-weight: normal;
        margin-left: var(--spacing-xs);
    }

    .doctor-card p {
        margin-bottom: var(--spacing-sm);
        color: var(--light-text);
        font-size: 0.95em;
    }

    /* Time Slot Buttons */
    .time-slots {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-xs);
        margin-top: var(--spacing-sm);
        justify-content: center; /* Center time slots within the card */
    }

    .time-slot-btn {
        background-color: var(--grey-200);
        border: 1px solid var(--grey-300);
        color: var(--dark-text);
        padding: var(--spacing-xs) var(--spacing-sm);
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        line-height: 1.2;
        min-width: 80px; /* Give buttons a minimum width */
    }

    .time-slot-btn:hover:not(.selected) {
        background-color: var(--grey-300);
        border-color: var(--border-color);
        color: var(--dark-text);
    }

    .time-slot-btn.selected {
        background-color: var(--primary-green);
        color: var(--white);
        border-color: var(--primary-green);
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Adjustments for appointment page */
    @media (max-width: 768px) {
        .page-appointment {
            padding-top: var(--spacing-md);
            padding-bottom: var(--spacing-xl);
            margin-top: var(--spacing-md);
            max-width: 100%; /* Allow full width on smaller screens */
            border-radius: 0; /* No border radius on full width */
            box-shadow: none;
        }
        .page-title {
            font-size: 2.2rem;
            margin-bottom: var(--spacing-lg);
            padding-top: var(--spacing-sm);
        }
        .appointment-form {
            padding: var(--spacing-lg) var(--spacing-md);
            gap: var(--spacing-md);
            max-width: 100%;
            border-radius: 0;
            box-shadow: none;
            border-left: none;
            border-right: none;
        }
        .form-label {
            font-size: 1rem;
        }
        .form-input-date,
        .form-input-select {
            padding: var(--spacing-xs);
            height: 45px;
        }
        .btn-primary-cta {
            font-size: 1rem;
            padding: var(--spacing-xs) var(--spacing-lg);
            min-height: 45px;
            max-width: 300px;
        }
        .alert.success, .alert.error {
            margin-bottom: var(--spacing-lg);
            max-width: 100%;
            border-radius: 0;
            border-left: none;
            border-right: none;
        }
        .time-slots {
            justify-content: flex-start; /* Align time slots to left on smaller screens */
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.8rem;
        }
        .appointment-form {
            padding: var(--spacing-md) var(--spacing-sm);
        }
        .btn-primary-cta {
            max-width: 100%; /* Allow full width on very small screens */
        }
        .doctor-card h4 {
            font-size: 1.15rem;
        }
        .doctor-specialization {
            display: block; /* Stack specialization below name on tiny screens */
            margin-left: 0;
            margin-top: var(--spacing-xs);
            font-size: 0.85em;
        }
        .time-slot-btn {
            font-size: 0.85rem;
            min-width: 70px;
            padding: 6px 8px;
        }
    }
</style>
