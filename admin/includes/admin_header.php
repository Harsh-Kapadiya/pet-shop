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

       
        <!-- LOGOUT BUTTON -->
        <a href='logout.php' style="
            padding:10px 20px;
            background:#dc3545;
            color:white;
            border-radius:25px;
            text-decoration:none;
            font-weight:600;
        ">Logout</a>
    </div>
</div>
