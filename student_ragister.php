<?php
// DB Connection
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'college_db';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if ($mysqli->connect_error) {
    die('DB connection error: ' . $mysqli->connect_error);
}

$msg = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name     = trim($_POST['first_name']);
    $last_name      = trim($_POST['last_name']);
    $dob            = trim($_POST['dob']);
    $gender         = trim($_POST['gender']);
    $category       = trim($_POST['category']);
    $email          = trim($_POST['email']);
    $mobile         = trim($_POST['mobile']);
    $admission_type = trim($_POST['admission_type']);
    $course         = trim($_POST['course']);
    $password       = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);

    // Check if email exists
    $check_email = $mysqli->prepare("SELECT email FROM student_ragister WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        $msg = "❌ Email is already registered";
        $status = "error";
    } else {
        $stmt = $mysqli->prepare("INSERT INTO student_ragister 
            (first_name, last_name, dob, gender, category, email, mobile, admission_type, course, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $first_name, $last_name, $dob, $gender, $category, $email, $mobile, $admission_type, $course, $password);

        if ($stmt->execute()) {
            $msg = "✅ Registration successful";
            $status = "success";
        } else {
            $msg = "❌ Error: " . $mysqli->error;
            $status = "error";
        }
        $stmt->close();
    }
    $check_email->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Registration Form</title>
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
    .form-container {
      width: 400px;
      background: rgba(255, 255, 255, 0.85);
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
      animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn { 
        from {opacity:0; transform:translateY(-10px);} 
        to {opacity:1; transform:translateY(0);} 
    }
    .form-title {
      text-align: center;
      font-size: 26px;
      font-weight: bold;
      margin-bottom: 28px;
      color: #222;
    }
    .form-group { margin-bottom: 14px; }
    label { font-weight: bold; display: block; margin-bottom: 5px; color: #333; }
    input, select {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      background: #f8f8f8;
      transition: 0.2s ease;
    }
    .btn-primary {
      background: linear-gradient(135deg, #0099ff, #007acc);
      color: #fff;
      border: none;
      padding: 12px;
      width: 100%;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
    }
    .popup {
      position: fixed;
      top: -100px;
      left: 50%;
      transform: translateX(-50%);
      background: #fff;
      padding: 15px 30px;
      border-radius: 8px;
      font-weight: bold;
      opacity: 0;
      animation: slideDown 0.6s forwards, fadeOut 0.6s forwards 3s;
      z-index: 9999;
    }
    .popup.success { border-left: 6px solid #28a745; color: #155724; }
    .popup.error   { border-left: 6px solid #dc3545; color: #721c24; }
    @keyframes slideDown {
      from { top: -100px; opacity: 0; }
      to { top: 20px; opacity: 1; }
    }
    @keyframes fadeOut {
      to { opacity: 0; transform: translateY(-20px); }
    }

    /* Login Link */
    .login-link {
      text-align: center;
      margin-top: 12px;
      font-size: 15px;
    }
    .login-link a {
      color: #007acc;
      font-weight: bold;
      text-decoration: none;
    }
    .login-link a:hover {
      text-decoration: underline;
    }

  </style>
</head>
<body>

  <div class="form-container">
    <div class="form-title">Student Registration Form</div>

    <form method="post" action="">
      <div class="form-group">
        <label>First Name*</label>
        <input type="text" name="first_name" required>
      </div>
      <div class="form-group">
        <label>Last Name*</label>
        <input type="text" name="last_name" required>
      </div>
      <div class="form-group">
        <label>Date of Birth*</label>
        <input type="date" name="dob" required>
      </div>
      <div class="form-group">
        <label>Gender*</label>
        <select name="gender" required>
          <option value="">Select</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div class="form-group">
        <label>Category*</label>
        <select name="category" required>
          <option value="">Select</option>
          <option value="General">General</option>
          <option value="SC">SC</option>
          <option value="ST">ST</option>
          <option value="OBC">OBC</option>
        </select>
      </div>
      <div class="form-group">
        <label>Email*</label>
        <input type="email" name="email" required>
      </div>
      <div class="form-group">
        <label>Mobile*</label>
        <input type="text" name="mobile" required>
      </div>
      <div class="form-group">
        <label>Admission Type*</label>
        <select name="admission_type" required>
          <option value="">Select</option>
          <option value="Regular">Regular</option>
          <option value="Transfer">Transfer</option>
        </select>
      </div>
      <div class="form-group">
        <label>Course*</label>
        <select name="course" required>
          <option value="">Select</option>
          <option value="BCA">BCA</option>
          <option value="BSc">BSc</option>
          <option value="BA">BA</option>
        </select>
      </div>
      <div class="form-group">
        <label>Password*</label>
        <input type="password" name="password" required>
      </div>

      <button type="submit" class="btn-primary">Register</button>

      <!-- Login Link -->
      <div class="login-link">
        Already have an account? <a href="Student_login.php">Login Here</a>
      </div>

    </form>
  </div>

  <?php if($msg): ?>
    <div class="popup <?= $status ?>"><?= $msg ?></div>
  <?php endif; ?>

</body>
</html>
