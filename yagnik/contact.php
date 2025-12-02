<?php
$message_sent = false;
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $msg     = htmlspecialchars($_POST['message']);

    $to = "admissions@syydcollege.edu";   
    $subject = "New Contact Form Submission from $name";
    $body = "You have received a new message:\n\nName: $name\nEmail: $email\nMessage:\n$msg\n";
    $headers = "From: $email\r\nReply-To: $email";

    if (mail($to, $subject, $body, $headers)) {
        $message_sent = true;
    } else {
        $error = "❌ Message could not be sent. Please check server mail settings.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SYYD Institute of Technology</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    /* --- Reset & Body --- */
    * {margin:0; padding:0; box-sizing:border-box;}
    body { 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
      background: linear-gradient(135deg, #e0f7fa, #c8e6c9, #ffe0b2); 
      min-height: 100vh;
      display: flex; 
      flex-direction: column; 
      align-items: center; 
      justify-content: center;
      padding: 30px;
    }

    h1 { 
      color: #1f63d1; 
      font-size: 2.5rem; 
      margin-bottom: 20px; 
      text-align: center;
      text-shadow: 2px 2px 8px rgba(0,0,0,0.15);
    }

    /* --- Contact Link Button --- */
    a#contact-link {
      display: inline-block;
      background: linear-gradient(135deg, #1f63d1, #3a82f7);
      color: #fff;
      padding: 14px 28px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      font-size: 18px;
      transition: all 0.3s ease;
      box-shadow: 0 8px 20px rgba(31,99,209,0.3);
    }
    a#contact-link:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 12px 25px rgba(31,99,209,0.5);
    }

    /* --- Contact Form Container --- */
    #contact {
      display: none;
      margin-top: 30px;
      backdrop-filter: blur(12px);
      background: rgba(255,255,255,0.9);
      border-radius: 25px;
      padding: 40px 35px;
      max-width: 500px;
      width: 100%;
      box-shadow: 0 15px 35px rgba(0,0,0,0.2);
      border-top: 5px solid #1f63d1;
    }

    form { 
      display: flex; 
      flex-direction: column;
    }

    label { 
      margin-top: 15px; 
      font-weight: 600; 
      color: #333;
    }

    input, textarea { 
      width: 100%; 
      padding: 14px 18px; 
      margin-top: 6px; 
      border-radius: 15px; 
      border:1px solid #ccc; 
      font-size: 15px;
      transition: all 0.4s ease;
      background: #f7f7f7;
    }
    input:focus, textarea:focus {
      border-color:#1f63d1; 
      outline:none; 
      box-shadow:0 0 18px rgba(31,99,209,0.25);
      transform: scale(1.02);
      background: #fff;
    }

    button { 
      margin-top: 25px; 
      padding:16px 22px; 
      background: linear-gradient(135deg, #1f63d1, #3a82f7); 
      font-size: 16px;
      font-weight:600;
      color:#fff; 
      border:0; 
      border-radius:18px; 
      cursor:pointer; 
      width:100%;
      transition:0.3s;
      box-shadow: 0 6px 18px rgba(31,99,209,0.3);
    }
    button:hover { 
      background: linear-gradient(135deg, #154ba2, #1f63d1); 
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(31,99,209,0.45);
    }

    /* --- Messages --- */
    .success, .error { 
      margin-bottom: 15px; 
      padding: 15px 18px; 
      border-radius:15px;
      font-size: 15px;
      font-weight: 500;
      animation: fadeIn 0.6s ease;
      text-align: center;
    }
    .success { 
      background:#e6ffed; 
      color:#065f46; 
      border:1px solid #34d399; 
    }
    .error { 
      background:#fee2e2; 
      color:#991b1b; 
      border:1px solid #f87171; 
    }

    @keyframes fadeIn {
      from {opacity:0; transform:translateY(15px);}
      to {opacity:1; transform:translateY(0);}
    }

    /* --- Input Icons --- */
    .input-icon { position: relative; }
    .input-icon i { 
      position: absolute; 
      top: 50%; 
      left: 15px; 
      transform: translateY(-50%); 
      color: #1f63d1; 
      opacity: 0.7; 
    }
    .input-icon input, .input-icon textarea { padding-left: 40px; }

    /* --- Advanced touch --- */
    textarea { min-height: 120px; resize: vertical; }
    h2 { text-align:center; color:#1f63d1; margin-bottom:20px; }

  </style>
</head>
<body>
  <h1>SYYD Institute of Technology</h1>
  <a href="#" id="contact-link">Contact Us</a>

  <div id="contact">
    <?php if ($message_sent): ?>
      <div class="success">✅ Thank you! Your message has been sent successfully.</div>
    <?php elseif (!empty($error)): ?>
      <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <h2>Contact Form</h2>
    <form action="" method="post">
      <div class="input-icon">
        <i class="fa fa-user"></i>
        <input type="text" id="name" name="name" placeholder="Full Name" required>
      </div>

      <div class="input-icon">
        <i class="fa fa-envelope"></i>
        <input type="email" id="email" name="email" placeholder="Email" required>
      </div>

      <div class="input-icon">
        <i class="fa fa-comment"></i>
        <textarea id="message" name="message" placeholder="Your Message" required></textarea>
      </div>

      <button type="submit"><i class="fa fa-paper-plane"></i> Send Message</button>
    </form>
  </div>

  <script>
    document.getElementById("contact-link").addEventListener("click", function(e){
      e.preventDefault();
      const contact = document.getElementById("contact");
      contact.style.display = "block";
      contact.scrollIntoView({behavior:"smooth"});
    });
  </script>
</body>   
</html>
