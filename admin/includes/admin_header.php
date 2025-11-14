<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!-- ADMIN HEADER -->
<div style="
    width:100%;
    background-color:#ffffff;
    padding:18px 30px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
    display:flex;
    justify-content:space-between;
    align-items:center;
    font-family:Inter, sans-serif;
    position:sticky;
    top:0;
    z-index:1000;
">

    <!-- LOGO -->
    <div style="font-family:Poppins, sans-serif; font-size:1.7rem; font-weight:700;">
        <a href='../admin/dashboard.php' style="
            color:#556b2f;
            text-decoration:none;
        ">
            Pet Haven Admin
        </a>
    </div>

    <!-- NAV MENU -->
    <div style="display:flex; align-items:center; gap:20px;">

        <a href='dashboard.php' style="
            text-decoration:none;
            font-weight:600;
            color:#2c3e1f;
            padding:6px 10px;
        ">Dashboard</a>

        <a href='add_pet.php' style="
            text-decoration:none;
            font-weight:600;
            color:#2c3e1f;
            padding:6px 10px;
        ">Add Pet</a>

        <a href='order_details.php' style="
            text-decoration:none;
            font-weight:600;
            color:#2c3e1f;
            padding:6px 10px;
        ">Orders</a>

        <a href='manage_pets.php' style="
            text-decoration:none;
            font-weight:600;
            color:#2c3e1f;
            padding:6px 10px;
        ">Manage Pets</a>

        <a href='../admin/manage_customers.php' style="
            text-decoration:none;
            font-weight:600;
            color:#2c3e1f;
            padding:6px 10px;
        ">Manage Customers</a>

        <!-- LOGOUT BUTTON -->
        <a href='../admin/logout.php' style="
            padding:10px 20px;
            background:#dc3545;
            color:white;
            border-radius:25px;
            text-decoration:none;
            font-weight:600;
        ">Logout</a>
    </div>
</div>
