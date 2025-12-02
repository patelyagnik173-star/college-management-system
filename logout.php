<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Logging Out...</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(-45deg, #1f63d1, #23a6d5, #23d5ab, #1f63d1);
        background-size: 400% 400%;
        animation: gradientBG 10s ease infinite;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #fff;
        text-align: center;
    }
    @keyframes gradientBG {
        0% {background-position: 0% 50%;}
        50% {background-position: 100% 50%;}
        100% {background-position: 0% 50%;}
    }
    .box {
        background: rgba(0,0,0,0.4);
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.5);
        animation: fadeIn 1.2s ease;
    }
    h1 {
        font-size: 28px;
        margin-bottom: 10px;
    }
    p {
        font-size: 16px;
        margin-bottom: 20px;
    }
    .loader {
        border: 5px solid rgba(255,255,255,0.3);
        border-top: 5px solid #fff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }
    @keyframes spin {
        100% { transform: rotate(360deg); }
    }
    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }
</style>
</head>
<body>
    <div class="box">
        <h1>Logging Out...</h1>
        <p>You are being securely logged out. Redirecting to login page.</p>
        <div class="loader"></div>
    </div>

    <!-- Auto Redirect after 3 seconds -->
    <script>
        setTimeout(function(){
            window.location.href = "student_login.php";
        }, 3000);
    </script>
</body>
</html>