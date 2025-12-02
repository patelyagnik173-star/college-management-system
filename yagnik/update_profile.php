<?php
$conn = mysqli_connect("localhost", "root", "", "college_db");

if (isset($_POST['submit'])) {
    $date = $_POST['date'];
    $name = $_POST['student_name'];
    $contact = $_POST['student_contact'];
    $address = $_POST['address'];

    $sql = "INSERT INTO students (full_name, student_contact, address, created_at) 
            VALUES ('$name', '$contact', '$address', '$date')";

    if (mysqli_query($conn, $sql)) {
        echo "Record inserted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url("clg.jpeg") no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
        }

        .header {
            width: 100%;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .form-container {
            width: 400px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            backdrop-filter: blur(6px);
            transition: transform 0.3s ease;
        }

        .form-container:hover {
            transform: scale(1.02);
        }

        .form-container label {
            display: block;
            margin: 8px 0 5px;
            font-weight: bold;
            color: #333;
        }

        .form-container input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-container button {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s ease;
        }

        .form-container button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <div class="header">Update Profile</div>

    <div class="form-container">
        <form method="POST">
            <label>Date</label>
            <input type="text" name="date" value="<?php echo date('Y-m-d'); ?>" readonly>

            <label>Student Name</label>
            <input type="text" name="student_name" required>

            <label>Student Contact</label>
            <input type="text" name="student_contact" required>

            <label>Address</label>
            <textarea name="address" required></textarea>

            <button type="submit" name="update">Update Profile</button>
        </form>
    </div>

</body>
</html>
	