<?php
session_start();
require_once "db.php";

$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($email_err) && empty($password_err)) {
        $sql = "SELECT id, email, password FROM student_ragister WHERE email = ?";
        if ($stmt = mysqli_prepare($mysqli, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $db_email, $db_password);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $db_password)) {
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $db_email;
                            $_SESSION["success_msg"] = "Welcome, you have successfully logged in!";
                            header("location: welcome.php");
                            exit();
                        } else {
                            $login_err = "❌ Invalid email or password.";
                        }
                    }
                } else {
                    $login_err = "❌ No account found with that email.";
                }
            } else {
                $login_err = "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($mysqli);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url("college (2).jpeg") no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.85);
            width: 360px;
            padding: 30px 25px;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            animation: fadeIn 0.5s ease-in-out;
        }
        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #222;
            font-size: 24px;
            font-weight: bold;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background: #f8f8f8;
        }
        input[type="submit"] {
            width: 100%;
            background: linear-gradient(135deg, #0099ff, #007acc);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        .error {
            color: #d10000;
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .footer-text {
            text-align: center;
            margin-top: 12px;
            font-size: 13px;
        }
        .footer-text a {
            color: #0099ff;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>
        <?php 
        if (!empty($login_err)) {
            echo '<div class="error">' . $login_err . '</div>';
        } 
        ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="email" name="email" placeholder="Enter Email" value="<?php echo htmlspecialchars($email); ?>" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" value="Login">
        </form>

        <!-- 🔥 Correct Link -->
        <div class="footer-text">
            Don't have an account? <a href="student_ragister.php">Sign up now</a>
        </div>
    </div>
</body>
</html>
