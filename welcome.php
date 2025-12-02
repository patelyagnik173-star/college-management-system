<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome Dashboard</title>

    <style>
        /* FULL PAGE RESET */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            overflow-x: hidden;
        }

        /* 🔥 Animated Gradient Background */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: linear-gradient(-45deg, #00c6ff, #0072ff, #ff5f6d, #ffc371);
            background-size: 400% 400%;
            animation: gradientMove 10s ease infinite;
            z-index: -1;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* 🔥 NAVBAR (Glass Effect) */
        .navbar {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(6px);
            padding: 15px;
            color: white;
            display: flex;
            justify-content: space-around;
            font-size: 18px;
            font-weight: bold;
            border-bottom: 1px solid rgba(255,255,255,0.3);
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 6px 12px;
            transition: 0.3s;
        }
        .navbar a:hover {
            background: rgba(255,255,255,0.4);
            border-radius: 5px;
        }

        /* CONTENT BOX */
        .content {
            margin-top: 80px;
            text-align: center;
            color: white;
            text-shadow: 0px 0px 5px black;
        }

        h1 {
            font-size: 32px;
        }

        /* LOGOUT */
        .logout a {
            color: #ff0000;
            font-size: 20px;
            font-weight: bold;
            background: white;
            padding: 8px 15px;
            border-radius: 8px;
            text-decoration: none;
        }
        .logout a:hover {
            background: #ffe6e6;
        }

    </style>
</head>
<body>

<!-- 🔵 TOP MENU BAR -->
<div class="navbar">
    <a href="student_dashboard.php">Dashboard</a>
    <a href="update_profile.php">Profile</a>
    <a href="fees.php">Fees & Eligibility</a>
    <a href="gallery.php">Gallery</a>
    <a href="contact.php">Contact Us</a>
    <a href="attendence.php">Attendance</a>
</div>

<div class="content">
    <h1>Welcome, <?php echo $_SESSION["email"]; ?> 👋</h1>
    <p>You have successfully logged in!</p>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
