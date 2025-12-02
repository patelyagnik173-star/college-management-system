<?php


session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ---------- DB CONFIG ----------
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'student_attendance';

$mysqli = new mysqli($db_host, $db_user, $db_pass);
if ($mysqli->connect_error) die('DB Connect Error: ' . $mysqli->connect_error);

$mysqli->query("CREATE DATABASE IF NOT EXISTS `" . $mysqli->real_escape_string($db_name) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->select_db($db_name);

// create tables
$mysqli->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student','teacher') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$mysqli->query("CREATE TABLE IF NOT EXISTS attendance3 (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(100) NOT NULL,
    subject VARCHAR(150) DEFAULT NULL,
    status ENUM('Present','Absent') NOT NULL,
    date DATE NOT NULL,
    marked_by VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// ensure a default teacher exists
$check_teacher = $mysqli->prepare("SELECT id FROM users WHERE role='teacher' LIMIT 1");
$check_teacher->execute();
$check_teacher->store_result();
if ($check_teacher->num_rows === 0) {
    $default_user = 'teacher1';
    $default_pass_hashed = password_hash('teacher123', PASSWORD_DEFAULT);
    $ins = $mysqli->prepare("INSERT INTO users (username,password,role) VALUES (?,?, 'teacher')");
    $ins->bind_param('ss', $default_user, $default_pass_hashed);
    $ins->execute();
    $ins->close();
}
$check_teacher->close();

// CSRF token helpers
function csrf_token(){ if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(16)); return $_SESSION['csrf_token']; }
function csrf_check($t){ return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $t); }

$msg = '';
$msg_type = 'info';

// LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (!csrf_check($_POST['csrf_token'] ?? '')) die('Invalid CSRF');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $mysqli->prepare("SELECT id, username, password, role FROM users WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            header('Location: ' . basename(__FILE__));
            exit;
        } else { $msg = 'Invalid credentials'; $msg_type = 'error'; }
    } else { $msg = 'Invalid credentials'; $msg_type = 'error'; }
    $stmt->close();
}

// LOGOUT
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset(); session_destroy(); header('Location: ' . basename(__FILE__)); exit;
}

// TEACHER: Create Student (ONLY AVAILABLE TO LOGGED-IN TEACHER)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_student'])) {
    if (!csrf_check($_POST['csrf_token'] ?? '')) die('Invalid CSRF');
    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
        $msg = 'Unauthorized: only teachers can create student accounts.'; $msg_type = 'error';
    } else {
        $stu_username = trim($_POST['stu_username'] ?? '');
        $stu_password = $_POST['stu_password'] ?? '';
        if ($stu_username === '' || $stu_password === '') { $msg = 'Student username & password required.'; $msg_type = 'error'; }
        else {
            // check duplicate
            $chk = $mysqli->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $chk->bind_param('s', $stu_username);
            $chk->execute();
            $chk->store_result();
            if ($chk->num_rows > 0) {
                $msg = '⚠️ Student username already exists. Choose another.'; $msg_type = 'error';
            } else {
                $hp = password_hash($stu_password, PASSWORD_DEFAULT);
                $ins = $mysqli->prepare("INSERT INTO users (username,password,role) VALUES (?, ?, 'student')");
                $ins->bind_param('ss', $stu_username, $hp);
                if ($ins->execute()) { $msg = '✅ Student account created.'; $msg_type = 'success'; }
                else { $msg = 'Create student error: ' . $ins->error; $msg_type = 'error'; }
                $ins->close();
            }
            $chk->close();
        }
    }
}

// MARK ATTENDANCE (ONLY TEACHER)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_attendance'])) {
    if (!csrf_check($_POST['csrf_token'] ?? '')) die('Invalid CSRF');
    if (empty($_SESSION['role']) || $_SESSION['role'] !== 'teacher') { $msg = 'Unauthorized: only teachers can mark attendance.'; $msg_type = 'error'; }
    else {
        $student_id = trim($_POST['student_id'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $status = ($_POST['status'] === 'Present') ? 'Present' : 'Absent';
        $date = $_POST['date'] ?? '';
        if ($student_id === '' || $date === '') { $msg = 'Student ID and date are required.'; $msg_type = 'error'; }
        else {
            $ins = $mysqli->prepare("INSERT INTO attendance3 (student_id,subject,status,date,marked_by) VALUES (?, ?, ?, ?, ?)");
            $marked_by = $_SESSION['username'];
            $ins->bind_param('sssss', $student_id, $subject, $status, $date, $marked_by);
            if ($ins->execute()) { $msg = '✅ Attendance marked successfully.'; $msg_type = 'success'; }
            else { $msg = 'Insert error: ' . $ins->error; $msg_type = 'error'; }
            $ins->close();
        }
    }
}

// VIEW attendance
$view_results = [];
$view_for = ''; // which student id viewed
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['view_attendance'])) {
    if (!csrf_check($_POST['csrf_token'] ?? '')) die('Invalid CSRF');
    if (empty($_SESSION['role'])) { $msg = 'Please login to view attendance.'; $msg_type = 'error'; }
    else {
        if ($_SESSION['role'] === 'student') {
            $view_for = $_SESSION['username'];
        } else {
            $view_for = trim($_POST['student_id_view'] ?? '');
        }
        $stmt = $mysqli->prepare("SELECT subject, status, date, marked_by, created_at FROM attendance3 WHERE student_id = ? ORDER BY date DESC, created_at DESC");
        $stmt->bind_param('s', $view_for);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($r = $res->fetch_assoc()) $view_results[] = $r;
        $stmt->close();
        if (empty($view_results)) { $msg = 'No records found for: ' . htmlspecialchars($view_for); $msg_type = 'info'; }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Attendance Portal (Teacher-create Students)</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --bg1:#0f0c29;
  --bg2:#302b63;
  --accent:#8a2be2;
  --muted:#cfc7ff;
}
*{box-sizing:border-box;font-family:Poppins,system-ui,Arial}
html,body{height:100%;margin:0}
body{background:linear-gradient(135deg,var(--bg1),var(--bg2));background-size:400% 400%;animation:grad 12s ease infinite;color:var(--muted);padding:18px}
@keyframes grad{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
.container{max-width:1000px;margin:0 auto}
.header{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px}
.brand{display:flex;gap:12px;align-items:center}
.logo{width:48px;height:48px;border-radius:10px;background:linear-gradient(45deg,#a78bfa,#7c3aed);display:flex;align-items:center;justify-content:center;font-weight:700;color:white;box-shadow:0 6px 18px rgba(124,58,237,0.25)}
h1{margin:0;font-size:20px}
.card{background:linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.02));border-radius:12px;padding:18px;box-shadow:0 8px 30px rgba(2,6,23,0.6);backdrop-filter: blur(6px);border:1px solid rgba(255,255,255,0.04)}
.grid{display:flex;gap:18px;align-items:flex-start;flex-wrap:wrap}
.col{flex:1;min-width:300px}
input,select{width:100%;padding:10px;border-radius:10px;border:1px solid rgba(255,255,255,0.06);background:transparent;color:var(--muted);margin-top:8px}
button{background:linear-gradient(90deg,var(--accent),#6d28d9);border:0;padding:10px 14px;border-radius:10px;color:white;font-weight:600;cursor:pointer;box-shadow:0 8px 20px rgba(88,24,163,0.2);transition:transform .15s ease}
button:hover{transform:translateY(-3px)}
.small{font-size:13px;color:rgba(255,255,255,0.7)}
.msg{padding:10px;border-radius:8px;margin-bottom:12px}
.msg.success{background:rgba(34,197,94,0.12);color:#bbf7d0}
.msg.error{background:rgba(239,68,68,0.08);color:#fecaca}
.msg.info{background:rgba(59,130,246,0.08);color:#bfdbfe}
.table{width:100%;border-collapse:collapse;margin-top:10px}
.table th{background:linear-gradient(90deg,var(--accent),#6d28d9);color:white;padding:10px;border:none;text-align:left}
.table td{padding:10px;border-top:1px solid rgba(255,255,255,0.03);color:var(--muted)}
.topright{text-align:right;margin-bottom:10px}
.link{color:var(--muted);text-decoration:none;padding:6px 10px;border-radius:8px;border:1px solid rgba(255,255,255,0.03)}
.footer{margin-top:18px;color:rgba(255,255,255,0.5);font-size:13px}
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <div class="brand">
      <div class="logo">A</div>
      <div>
        <h1>Attendance Portal</h1>
        <div class="small">Teacher creates student accounts • Teacher marks attendance • Student view-only</div>
      </div>
    </div>
    <div class="topright">
      <?php if (!empty($_SESSION['username'])): ?>
        <div class="small">Logged in as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> (<?php echo htmlspecialchars($_SESSION['role']); ?>) | <a class="link" href="?action=logout">Logout</a></div>
      <?php else: ?>
        <div class="small">Not logged in</div>
      <?php endif; ?>
    </div>
  </div>

  <?php if (!empty($msg)): ?>
    <div class="msg <?php echo htmlspecialchars($msg_type); ?>"><?php echo htmlspecialchars($msg); ?></div>
  <?php endif; ?>

  <?php if (empty($_SESSION['username'])): ?>

    <div class="grid">
      <div class="col card">
        <h3>Login</h3>
        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
          <label class="small">Username</label>
          <input name="username" required>
          <label class="small">Password</label>
          <input type="password" name="password" required>
          <br><br>
          <button name="login">Login</button>
          <p class="small" style="margin-top:8px">Default teacher: <strong>teacher1</strong> / <strong>teacher123</strong></p>
        </form>
      </div>

      <div class="col card">
        <h3>Info</h3>
        <p class="small">Student accounts are created by a Teacher. If you are a student, ask your teacher to create your account (username will be used as Student ID for attendance).</p>
      </div>
    </div>

  <?php else: ?>

    <div class="grid">
      <?php if ($_SESSION['role'] === 'teacher'): ?>
      <div class="col card">
        <h3>Create Student Account (Teacher only)</h3>
        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
          <label class="small">Student Username (use roll/id)</label>
          <input name="stu_username" placeholder="eg: student1" required>
          <label class="small">Password</label>
          <input type="password" name="stu_password" placeholder="set a password for student" required>
          <br><br>
          <button name="create_student">Create Student</button>
        </form>
        <p class="small" style="margin-top:8px">After creating, give the username & password to the student. Student will use these to login and view their attendance.</p>
      </div>
      <?php endif; ?>

      <?php if ($_SESSION['role'] === 'teacher'): ?>
      <div class="col card">
        <h3>Mark Attendance (Teacher)</h3>
        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
          <label class="small">Student ID (username / roll)</label>
          <input name="student_id" placeholder="eg: student1" required>
          <label class="small">Subject</label>
          <input name="subject" placeholder="eg: DBMS">
          <label class="small">Status</label>
          <select name="status"><option value="Present">Present</option><option value="Absent">Absent</option></select>
          <label class="small">Date</label>
          <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
          <br><br>
          <button name="mark_attendance">Mark Attendance</button>
        </form>
      </div>
      <?php else: ?>
      <div class="col card">
        <h3>Your Attendance (Student)</h3>
        <p class="small">Students can view their own attendance only. Click "View Attendance" below.</p>
      </div>
      <?php endif; ?>

      <div class="col card">
        <h3>View Attendance</h3>
        <form method="POST">
          <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">
          <?php if ($_SESSION['role'] === 'teacher'): ?>
            <label class="small">Enter Student ID to view</label>
            <input name="student_id_view" placeholder="eg: student1" required>
          <?php else: ?>
            <p class="small">Students will view their own attendance (username used as student_id).</p>
          <?php endif; ?>
          <br>
          <button name="view_attendance">View Attendance</button>
        </form>
      </div>
    </div>

    <?php if (!empty($view_results)): ?>
      <div class="card" style="margin-top:16px">
        <h3>Attendance Records for <?php echo htmlspecialchars($view_for); ?></h3>
        <table class="table">
          <tr><th>Subject</th><th>Status</th><th>Date</th><th>Marked By</th><th>Recorded At</th></tr>
          <?php foreach ($view_results as $r): ?>
            <tr>
              <td><?php echo htmlspecialchars($r['subject']); ?></td>
              <td><?php echo htmlspecialchars($r['status']); ?></td>
              <td><?php echo htmlspecialchars($r['date']); ?></td>
              <td><?php echo htmlspecialchars($r['marked_by']); ?></td>
              <td><?php echo htmlspecialchars($r['created_at']); ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    <?php endif; ?>

  <?php endif; ?>

  <div class="footer">Tip: For production enable HTTPS & stronger session settings. This demo stores only minimal data locally.</div>
</div>
</body>
</html>
