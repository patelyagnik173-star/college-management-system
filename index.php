<!DOCTYPE html>
<html>
<head>
    <title>SYYD Institute of Technology</title>
    <style>
      body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    height: 100vh;
    background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    text-align: center;
    color: #fff;
}

@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

        /* Header */
        .header {
            background: linear-gradient(90deg, #003366, #0059b3);
            padding: 25px;
            color: white;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.5);
        }
        .header h1 {
            margin: 0;
            font-size: 34px;
        }

        /* Menu */
        .menu {
            padding: 15px 0;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(8px);
        }
        .menu a {
            margin: 0 20px;
            text-decoration: none;
            color: #ffcc00;
            font-weight: bold;
            transition: 0.3s;
        }
        .menu a:hover {
            color: #00e6e6;
            border-bottom: 2px solid #00e6e6;
            padding-bottom: 4px;
        }

        /* Content */
        .content {
            margin: 60px auto;
            max-width: 800px;
            padding: 40px;
            border-radius: 15px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            box-shadow: 0px 6px 20px rgba(0,0,0,0.5);
        }
        .content h2 {
            color: #ffcc00;
        }
        .content p {
            font-size: 18px;
            line-height: 1.6;
        }

        /* Footer */
        footer {
            margin-top: 80px;
            background: linear-gradient(90deg, #003366, #0059b3);
            padding: 18px;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>SYYD Institute of Technology</h1>
    </div>

    <div class="menu">
        <a href="index.php">Home</a>
        <a href="student_ragister.php">Student Registration</a>
        <a href="student_login.php">Student Login</a>
        <a href="student_dashboard.php">Dashboard</a>
        <a href="update_profile.php">Profile</a>
        <a href="fees.php">Fees & Eligibility</a>
        <a href="gallery.php">Gallery</a>
        <a href="contact.php">Contact Us</a>
	    <a href="attendence.php">Attendence</a>

    </div>

    <div class="content">
        <h2>Welcome to SYYD Institute</h2>
        <p>
            College Management System with modern design.  
            Experience a sleek dark theme with glowing highlights.  
        </p>
    </div>

    <footer>
        &copy; 2025 SYYD Institute of Technology and Management
    </footer>

</body>
</html>