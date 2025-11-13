<?php
session_start();
require_once '../includes/db_connect.php';
include 'includes/admin_header.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_name = $_SESSION['admin_name'];

try {
    $totalCustomers = $pdo->query("SELECT COUNT(*) FROM Customers")->fetchColumn();
    $totalPets      = $pdo->query("SELECT COUNT(*) FROM Pets")->fetchColumn();
    $totalAdoptions = $pdo->query("SELECT COUNT(*) FROM Adoptions")->fetchColumn();
    $totalOrders    = $pdo->query("SELECT COUNT(*) FROM Orders")->fetchColumn();
} catch (PDOException $e) {
    die("Error loading dashboard: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
</head>

<body style="background:#f3f5ed; margin:0; font-family:Inter, sans-serif;">

    <!-- MAIN CONTAINER -->
    <div style="max-width:1200px; margin:auto; padding:30px;">

        <!-- WELCOME TEXT -->
        <h1 style="
            font-family:Poppins, sans-serif;
            font-size:32px;
            font-weight:700;
            color:#2c3e1f;
            margin-bottom:25px;
        ">
            Welcome, <?php echo htmlspecialchars($admin_name); ?>
        </h1>

        <!-- BUTTONS TOP SECTION -->
        <div style="display:flex; gap:15px; flex-wrap:wrap; margin-bottom:35px;">

            <a href='add_pet.php' style="
                padding:12px 22px;
                background:#556b2f;
                color:white;
                border-radius:25px;
                text-decoration:none;
                font-weight:600;
            ">Add New Pet</a>

            <a href='order_details.php' style="
                padding:12px 22px;
                background:#6b8e23;
                color:white;
                border-radius:25px;
                text-decoration:none;
                font-weight:600;
            ">View Orders</a>

            <a href='manage_pets.php' style="
                padding:12px 22px;
                background:#28A745;
                color:white;
                border-radius:25px;
                text-decoration:none;
                font-weight:600;
            ">Manage Pets</a>

            <a href='manage_customers.php' style="
                padding:12px 22px;
                background:#2c3e1f;
                color:white;
                border-radius:25px;
                text-decoration:none;
                font-weight:600;
            ">View Customers</a>

            <a href='admin_more_details.php' style="
                padding:12px 22px;
                background:#8a9770;
                color:white;
                border-radius:25px;
                text-decoration:none;
                font-weight:600;
            ">More Details</a>

        </div>

        <!-- CARDS GRID -->
        <div style="
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
            gap:25px;
        ">

            <!-- CARD 1 -->
            <div style="
                background:white;
                padding:25px;
                border-radius:15px;
                text-align:center;
                box-shadow:0 6px 15px rgba(0,0,0,0.15);
                transition:0.2s;
            "
                onmouseover="this.style.transform='translateY(-6px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <h3 style="font-family:Poppins; color:#556b2f; font-size:20px; margin-bottom:12px;">
                    Total Customers
                </h3>
                <p style="font-size:34px; font-weight:700; color:#6b8e23;">
                    <?php echo $totalCustomers; ?>
                </p>
            </div>

            <!-- CARD 2 -->
            <div style="
                background:white;
                padding:25px;
                border-radius:15px;
                text-align:center;
                box-shadow:0 6px 15px rgba(0,0,0,0.15);
                transition:0.2s;
            "
                onmouseover="this.style.transform='translateY(-6px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <h3 style="font-family:Poppins; color:#556b2f; font-size:20px; margin-bottom:12px;">
                    Total Pets
                </h3>
                <p style="font-size:34px; font-weight:700; color:#6b8e23;">
                    <?php echo $totalPets; ?>
                </p>
            </div>

            <!-- CARD 3 -->
            <div style="
                background:white;
                padding:25px;
                border-radius:15px;
                text-align:center;
                box-shadow:0 6px 15px rgba(0,0,0,0.15);
                transition:0.2s;
            "
                onmouseover="this.style.transform='translateY(-6px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <h3 style="font-family:Poppins; color:#556b2f; font-size:20px; margin-bottom:12px;">
                    Total Adoptions
                </h3>
                <p style="font-size:34px; font-weight:700; color:#28A745;">
                    <?php echo $totalAdoptions; ?>
                </p>
            </div>

            <!-- CARD 4 -->
            <div style="
                background:white;
                padding:25px;
                border-radius:15px;
                text-align:center;
                box-shadow:0 6px 15px rgba(0,0,0,0.15);
                transition:0.2s;
            "
                onmouseover="this.style.transform='translateY(-6px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <h3 style="font-family:Poppins; color:#556b2f; font-size:20px; margin-bottom:12px;">
                    Total Orders
                </h3>
                <p style="font-size:34px; font-weight:700; color:#6b8e23;">
                    <?php echo $totalOrders; ?>
                </p>
            </div>

        </div>

    </div>

</body>

</html>

<?php include 'includes/admin_footer.php'; ?>