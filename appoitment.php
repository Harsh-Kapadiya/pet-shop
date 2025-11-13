<?php
// appointment.php
// Place in root where header/footer/includes are available

include 'includes/header.php';
require_once 'includes/db_connect.php'; // sets $pdo and starts session (your header may already start session)

// -- Ensure user logged in (attempt common session keys)
if (!isset($_SESSION['user']) || (empty($_SESSION['user']['cid']) && empty($_SESSION['user']['id']))) {
    // adjust login path as you have in project
    header('Location: login.php');
    exit;
}
$cid = $_SESSION['user']['cid'] ?? $_SESSION['user']['id']; // customer's id

$errors = [];
$success = '';

// Helper: normalize day names to 3-letter short (Mon, Tue, ...)
function get_short_day($date)
{
    return date('D', strtotime($date)); // Mon, Tue, Wed...
}

// Helper: parse working_days string and decide if a given date is working
// supports formats like: "Mon, Wed, Fri" or "Mon-Fri" or "Mon,Wed,Fri"
function is_working_day($working_days_string, $date)
{
    $dayShort = get_short_day($date); // e.g. "Mon"
    $s = str_replace(' ', '', $working_days_string);
    $s = str_replace(array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'), $s);

    // If has '-', treat as range e.g. Mon-Fri
    if (strpos($s, '-') !== false) {
        list($start, $end) = explode('-', $s, 2);
        $order = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $startIndex = array_search($start, $order);
        $endIndex = array_search($end, $order);
        if ($startIndex === false || $endIndex === false) return false;
        if ($startIndex <= $endIndex) {
            $range = array_slice($order, $startIndex, $endIndex - $startIndex + 1);
        } else { // wrap-around
            $range = array_merge(array_slice($order, $startIndex), array_slice($order, 0, $endIndex + 1));
        }
        return in_array($dayShort, $range);
    }

    // otherwise split by comma
    $parts = preg_split('/[,\s]+/', $s, -1, PREG_SPLIT_NO_EMPTY);
    return in_array($dayShort, $parts);
}

// Function to generate 30-min slots between start and end (returns 'H:i:s' strings)
function generate_slots($start_time, $end_time, $step_minutes = 30)
{
    $slots = [];
    $current = strtotime($start_time);
    $end = strtotime($end_time);
    while ($current < $end) {
        $slots[] = date('H:i:s', $current);
        $current = $current + ($step_minutes * 60);
    }
    return $slots;
}

// Handle AJAX: check doctor availability for a date (returns remaining slots count)
if (isset($_GET['action']) && $_GET['action'] === 'check_availability' && isset($_GET['doctor_id']) && isset($_GET['date'])) {
    header('Content-Type: application/json');
    $did = (int)$_GET['doctor_id'];
    $date = $_GET['date'];

    try {
        $stmt = $pdo->prepare("SELECT doctor_name, specialization, working_days, start_time, end_time, max_patients_per_day FROM Doctors WHERE doctor_id = ?");
        $stmt->execute([$did]);
        $doc = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$doc) {
            echo json_encode(['success' => false, 'error' => 'Doctor not found.']);
            exit;
        }

        if (!is_working_day($doc['working_days'], $date)) {
            echo json_encode(['success' => false, 'error' => 'Doctor does not work on selected date.']);
            exit;
        }

        // Count bookings on that date
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Book_appointment WHERE doctor_id = ? AND DATE(time) = ?");
        $stmt->execute([$did, $date]);
        $total_booked = (int)$stmt->fetchColumn();

        // get booked time slots
        $stmt = $pdo->prepare("SELECT TIME(time) as t FROM Book_appointment WHERE doctor_id = ? AND DATE(time) = ? ORDER BY time");
        $stmt->execute([$did, $date]);
        $bookedRows = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $bookedSlots = array_map(function ($t) {
            return $t;
        }, $bookedRows);

        $allSlots = generate_slots($doc['start_time'], $doc['end_time'], 30);
        $freeSlots = array_values(array_diff($allSlots, $bookedSlots));

        $remaining_capacity = max(0, $doc['max_patients_per_day'] - $total_booked);

        echo json_encode([
            'success' => true,
            'doctor' => $doc,
            'total_booked' => $total_booked,
            'remaining_capacity' => $remaining_capacity,
            'free_slots_count' => count($freeSlots),
            'free_slots' => $freeSlots // consumer won't pick slot but useful for UI if needed
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    exit;
}


// Handle form submission (option B: system assigns earliest free slot)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $doctor_id = (int)($_POST['doctor_id'] ?? 0);
    $appointment_date = trim($_POST['appointment_date'] ?? '');
    $pet_category = trim($_POST['pet_category'] ?? '');
    $breed = trim($_POST['breed'] ?? '');

    // Basic validation
    if (!$doctor_id) $errors[] = "Please select a doctor.";
    if (!$appointment_date) $errors[] = "Please select a date.";
    if (!$pet_category) $errors[] = "Please select pet category.";
    if (!$breed) $errors[] = "Please enter pet breed.";

    if (empty($errors)) {
        try {
            // get doctor details
            $stmt = $pdo->prepare("SELECT * FROM Doctors WHERE doctor_id = ?");
            $stmt->execute([$doctor_id]);
            $doc = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$doc) {
                $errors[] = "Selected doctor not found.";
            } else {
                // check working day
                if (!is_working_day($doc['working_days'], $appointment_date)) {
                    $errors[] = "Doctor does not work on the selected date ({$appointment_date}).";
                } else {
                    // count bookings for that day
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Book_appointment WHERE doctor_id = ? AND DATE(time) = ?");
                    $stmt->execute([$doctor_id, $appointment_date]);
                    $booked_count = (int)$stmt->fetchColumn();
                    if ($booked_count >= (int)$doc['max_patients_per_day']) {
                        $errors[] = "Doctor is fully booked for this date.";
                    } else {
                        // get booked slots
                        $stmt = $pdo->prepare("SELECT TIME(time) as t FROM Book_appointment WHERE doctor_id = ? AND DATE(time) = ? ORDER BY time");
                        $stmt->execute([$doctor_id, $appointment_date]);
                        $bookedRows = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        $bookedSlots = array_map(function ($t) {
                            return $t;
                        }, $bookedRows);

                        // generate all slots
                        $allSlots = generate_slots($doc['start_time'], $doc['end_time'], 30);

                        // find earliest free slot
                        $chosenSlot = null;
                        foreach ($allSlots as $slot) {
                            if (!in_array($slot, $bookedSlots)) {
                                $chosenSlot = $slot;
                                break;
                            }
                        }

                        if (!$chosenSlot) {
                            $errors[] = "No free time slot available for the selected date.";
                        } else {
                            // avoid duplicate booking: same cid and doctor same date
                            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Book_appointment WHERE cid = ? AND doctor_id = ? AND DATE(time) = ?");
                            $stmt->execute([$cid, $doctor_id, $appointment_date]);
                            if ($stmt->fetchColumn() > 0) {
                                $errors[] = "You already have a booking with this doctor on the selected date.";
                            } else {
                                // compose datetime
                                $datetime = date('Y-m-d H:i:s', strtotime($appointment_date . ' ' . $chosenSlot));

                                // insert into Book_appointment; pet_id left NULL (user only provides breed & category)
                                $ins = $pdo->prepare("INSERT INTO Book_appointment (doctor_id, time, pet_category, breed, pet_id, cid) VALUES (?, ?, ?, ?, NULL, ?)");
                                $ins->execute([$doctor_id, $datetime, $pet_category, $breed, $cid]);

                                // success — redirect to avoid resubmission
                                $_SESSION['flash_success'] = "Appointment booked with Dr. {$doc['doctor_name']} on " . date('d M Y, h:i A', strtotime($datetime));
                                header('Location: appointment.php?booked=1');
                                exit;
                            }
                        }
                    }
                }
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}


// fetch all doctors for the top table (show full list; availability validated at submit)
try {
    $stmt = $pdo->query("SELECT doctor_id, doctor_name, specialization, working_days, start_time, end_time FROM Doctors ORDER BY doctor_name");
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $doctors = [];
    $errors[] = "Failed to load doctors: " . $e->getMessage();
}

// show flash if redirected after successful booking
if (isset($_SESSION['flash_success'])) {
    $success = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}
?>

<!-- Page markup (inline/minimal styling; fits your container style) -->
<div class="container" style="max-width:1100px; margin:40px auto; font-family:Inter, sans-serif;">

    <h1 style="font-family:Poppins, sans-serif; color:#2c3e1f; font-size:28px; margin-bottom:18px;">Book a Vet Appointment</h1>

    <!-- Doctors table -->
    <div style="background:#fff; border-radius:12px; padding:18px; box-shadow:0 6px 18px rgba(0,0,0,0.06); margin-bottom:20px;">
        <h2 style="font-size:18px; color:var(--primary-green); margin-bottom:12px;">Doctor Sitting Arrangement</h2>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr>
                        <th style="text-align:left; padding:10px; border-bottom:1px solid #eee;">Doctor Name</th>
                        <th style="text-align:left; padding:10px; border-bottom:1px solid #eee;">Specialization</th>
                        <th style="text-align:left; padding:10px; border-bottom:1px solid #eee;">Sitting Days</th>
                        <th style="text-align:left; padding:10px; border-bottom:1px solid #eee;">Sitting Timing</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($doctors)): ?>
                        <tr>
                            <td colspan="4" style="padding:12px; color:#666;">No doctors found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($doctors as $d): ?>
                            <tr>
                                <td style="padding:10px; border-bottom:1px solid #fafafa;"><?php echo htmlspecialchars($d['doctor_name']); ?></td>
                                <td style="padding:10px; border-bottom:1px solid #fafafa;"><?php echo htmlspecialchars($d['specialization']); ?></td>
                                <td style="padding:10px; border-bottom:1px solid #fafafa;"><?php echo htmlspecialchars($d['working_days']); ?></td>
                                <td style="padding:10px; border-bottom:1px solid #fafafa;"><?php echo date('h:i A', strtotime($d['start_time'])) . ' to ' . date('h:i A', strtotime($d['end_time'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form container -->
    <div style="background:#fff; border-radius:12px; padding:22px; box-shadow:0 6px 18px rgba(0,0,0,0.06);">

        <?php if ($success): ?>
            <div style="background:#E6F7EB; color:#2b8b57; padding:12px; border-radius:8px; margin-bottom:12px;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div style="background:#FFEDEE; color:#b02a37; padding:12px; border-radius:8px; margin-bottom:12px;">
                <?php foreach ($errors as $err) echo '<div>' . htmlspecialchars($err) . '</div>'; ?>
            </div>
        <?php endif; ?>

        <form method="post" id="appointmentForm" style="display:grid; gap:12px;">
            <!-- Row 1: Doctor select -->
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <div style="flex:1; min-width:220px;">
                    <label style="display:block; margin-bottom:6px; font-weight:600;">Select Doctor</label>
                    <select name="doctor_id" id="doctorSelect" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #ddd;">
                        <option value="">-- Choose Doctor --</option>
                        <?php foreach ($doctors as $d): ?>
                            <option value="<?php echo (int)$d['doctor_id']; ?>"><?php echo htmlspecialchars($d['doctor_name'] . ' — ' . $d['specialization']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div id="doctorMessage" style="margin-top:6px; font-size:13px; color:#666;"></div>
                </div>
            </div>

            <!-- Row 2: date + breed -->
            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <div style="flex:0 0 240px;">
                    <label style="display:block; margin-bottom:6px; font-weight:600;">Select Date</label>
                    <input type="date" name="appointment_date" id="appointmentDate" required min="<?php echo date('Y-m-d'); ?>" style="padding:10px; border-radius:8px; border:1px solid #ddd; width:100%;">
                </div>

                <div style="flex:1; min-width:220px;">
                    <label style="display:block; margin-bottom:6px; font-weight:600;">Pet Breed (type)</label>
                    <input type="text" name="breed" required placeholder="e.g. Labrador" style="padding:10px; border-radius:8px; border:1px solid #ddd; width:100%;">
                </div>
            </div>

            <!-- Row 3: pet category -->
            <div style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
                <div style="flex:0 0 240px;">
                    <label style="display:block; margin-bottom:6px; font-weight:600;">Pet Category</label>
                    <select name="pet_category" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #ddd;">
                        <option value="">-- choose --</option>
                        <option value="dog">Dog</option>
                        <option value="cat">Cat</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div style="flex:1; display:flex; justify-content:flex-end;">
                    <button type="submit" name="book_appointment" id="bookBtn" style="background:var(--primary-green); color:#fff; border:none; padding:12px 20px; border-radius:10px; font-weight:700; cursor:pointer;">
                        Book Appointment (system will assign time)
                    </button>
                </div>
            </div>
        </form>

        <div style="margin-top:12px; font-size:13px; color:#666;">
            Note: You will be assigned the earliest available 30-minute slot for the selected doctor on your chosen date. If the doctor is fully booked or does not work that day you'll be notified.
        </div>
    </div>
</div>

<script>
    (function() {
        const doctorSelect = document.getElementById('doctorSelect');
        const dateInput = document.getElementById('appointmentDate');
        const doctorMessage = document.getElementById('doctorMessage');
        const bookBtn = document.getElementById('bookBtn');

        // When doctor or date changes, check availability via AJAX
        function checkAvailability() {
            const did = doctorSelect.value;
            const date = dateInput.value;
            doctorMessage.textContent = '';
            bookBtn.disabled = false;

            if (!did || !date) {
                doctorMessage.textContent = '';
                return;
            }

            doctorMessage.textContent = 'Checking availability...';

            fetch(`appointment.php?action=check_availability&doctor_id=${encodeURIComponent(did)}&date=${encodeURIComponent(date)}`)
                .then(r => r.json())
                .then(data => {
                    if (!data.success) {
                        doctorMessage.innerHTML = '<span style="color:#b02a37;">' + (data.error || 'Not available') + '</span>';
                        bookBtn.disabled = true;
                    } else {
                        const rem = data.remaining_capacity;
                        const slots = data.free_slots_count;
                        let msg = `Remaining capacity: ${rem}. Free time slots: ${slots}.`;
                        doctorMessage.innerHTML = '<span style="color:#2c6b2f;">' + msg + '</span>';
                        if (rem <= 0 || slots <= 0) {
                            bookBtn.disabled = true;
                        }
                    }
                })
                .catch(err => {
                    doctorMessage.innerHTML = '<span style="color:#b02a37;">Error checking availability</span>';
                    bookBtn.disabled = true;
                    console.error(err);
                });
        }

        doctorSelect.addEventListener('change', checkAvailability);
        dateInput.addEventListener('change', checkAvailability);

        // set default date to today
        dateInput.value = new Date().toISOString().split('T')[0];

    })();
</script>

<?php include 'includes/footer.php'; ?>