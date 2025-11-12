<?php
session_start();
require_once '../includes/db_connect.php';

// Redirect to login if admin not logged in
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | Pet Haven</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <header style="background: var(--primary-green); color: white; padding: 15px 0;">
        <div class="container" style="display:flex; justify-content:space-between; align-items:center;">
            <h2 style="margin:0; font-weight:700;">🐾 Pet Haven Admin</h2>
            
            <nav>
                <ul style="list-style:none; display:flex; gap:25px; margin:0; padding:0;">
                    <li><a href="index.php" style="color:white; text-decoration:none; font-weight:600;">Home</a></li>
                    <li><a href="check_appointment.php" style="color:white; text-decoration:none; font-weight:600;">Appointments</a></li>
                    <li><a href="order_detailss.php" style="color:white; text-decoration:none; font-weight:600;">Orders</a></li>
                    <li><a href="logout.php" style="color:white; text-decoration:none; font-weight:600;">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container" style="padding: 30px;">
