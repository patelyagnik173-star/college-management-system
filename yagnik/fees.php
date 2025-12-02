<?php
// ✅ Database connection
$conn = mysqli_connect("localhost", "root", "", "college_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ✅ Razorpay API Test Keys
$keyId = "rzp_test_RRacW3vpsyQuWU";
$keySecret = "5COPUZgnRlrXz6tAUZMLSklH";

// Initialize
$paymentSuccess = false;
$selectedCourse = '';
$selectedAmount = '';
$paymentId = '';
$studentName = '';

// ✅ Handle Razorpay callback
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['razorpay_payment_id'])) {
    $paymentId = $_POST['razorpay_payment_id'];
    $selectedCourse = $_POST['course'];
    $selectedAmount = $_POST['amount'];
    $studentName = isset($_POST['student_name']) ? $_POST['student_name'] : 'Guest';

    $stmt = $conn->prepare("INSERT INTO payments (course, amount, student_name, razorpay_payment_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $selectedCourse, $selectedAmount, $studentName, $paymentId);

    if ($stmt->execute()) {
        $paymentSuccess = true;
    } else {
        echo "<script>alert('❌ DB Error: " . addslashes($stmt->error) . "');</script>";
    }
    $stmt->close();
}

// ✅ Fetch course data
$sql = "SELECT course_name, fees, eligibility FROM fees1";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Fees Payment</title>
    <!-- ✅ Keep your existing CSS (unchanged) -->
    <style>
        /* ===== Your original CSS is here (no changes made) ===== */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: linear-gradient(270deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #8f00ff);
            background-size: 1400% 1400%;
            animation: rainbowBG 20s ease infinite;
        }

        @keyframes rainbowBG {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .header {
            color: #fff;
            padding: 25px 40px;
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
            margin-top: 20px;
        }

        table {
            width: 90%;
            max-width: 900px;
            margin: 30px auto;
            border-collapse: separate;
            border-spacing: 0 12px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            overflow: hidden;
        }

        th, td {
            padding: 16px 25px;
            text-align: center;
            font-weight: 600;
            color: #fff;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.5);
        }

        th {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tr:nth-child(2) {background: linear-gradient(120deg, #ff9a9e, #fad0c4);}
        tr:nth-child(3) {background: linear-gradient(120deg, #a1c4fd, #c2e9fb);}
        tr:nth-child(4) {background: linear-gradient(120deg, #fbc2eb, #a6c1ee);}
        tr:nth-child(5) {background: linear-gradient(120deg, #fddb92, #d1fdff);}
        tr:nth-child(6) {background: linear-gradient(120deg, #ffecd2, #fcb69f);}

        tr {
            transition: transform 0.3s, opacity 0.5s;
            border-radius: 8px;
            opacity: 0;
            transform: translateY(20px);
        }

        tr.show {
            opacity: 1;
            transform: translateY(0);
        }

        tr:hover {
            transform: scale(1.02);
        }

        .pay-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(90deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #8f00ff);
            background-size: 400% 400%;
            animation: rainbowButton 6s ease infinite;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .pay-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 18px rgba(0,0,0,0.4);
        }

        @keyframes rainbowButton {
            0% {background-position: 0% 50%;}
            50% {background-position: 100% 50%;}
            100% {background-position: 0% 50%;}
        }

        .payment-box {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(15px);
            width: 400px;
            margin: 40px auto;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            color: #fff;
        }

        .payment-box h2 {
            color: #fff;
            margin-bottom: 15px;
            text-shadow: 1px 1px 8px rgba(0,0,0,0.5);
        }

        .payment-box p {
            font-size: 18px;
            margin: 10px 0;
        }
    </style>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>

<div class="header">Fees & Eligibility Criteria</div>

<table>
    <tr>
        <th>Course</th>
        <th>Fees</th>
        <th>Eligibility</th>
        <th>Action</th>
    </tr>
    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        $rowIndex = 2;
        while ($row = mysqli_fetch_assoc($result)) {
            $course = $row['course_name'];
            $fees = number_format($row['fees'], 2);
            $eligibility = $row['eligibility'];
            echo "<tr class='row-$rowIndex'>
                    <td>$course</td>
                    <td>₹ $fees</td>
                    <td>$eligibility</td>
                    <td><button class='pay-btn' onclick='pay(\"$course\", \"$fees\")'>Pay Now</button></td>
                  </tr>";
            $rowIndex++;
        }
    } else {
        echo "<tr><td colspan='4'>No records found</td></tr>";
    }
    ?>
</table>

<?php if ($paymentSuccess): ?>
    <div class="payment-box">
        <h2>✅ Payment Successful!</h2>
        <p>Thank you, <strong><?php echo htmlspecialchars($studentName); ?></strong></p>
        <p>Paid for: <strong><?php echo htmlspecialchars($selectedCourse); ?></strong></p>
        <p>Amount: ₹ <?php echo htmlspecialchars($selectedAmount); ?></p>
        <!-- ✅ Receipt Download Button -->
        <p><button class="pay-btn" id="download-receipt">📄 Download Receipt</button></p>
    </div>
<?php endif; ?>

<script>
window.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('table tr:not(:first-child)');
    rows.forEach((row, index) => {
        setTimeout(() => {
            row.classList.add('show');
        }, index * 150);
    });
});

function pay(course, amount) {
    var student_name = prompt("Enter your full name:");
    if (!student_name) { alert("Name is required."); return; }

    var options = {
        "key": "<?php echo $keyId; ?>",
        "amount": amount * 100,
        "currency": "INR",
        "name": "Your College Name",
        "description": "Payment for " + course,
        "handler": function(response) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            form.appendChild(createInput("razorpay_payment_id", response.razorpay_payment_id));
            form.appendChild(createInput("course", course));
            form.appendChild(createInput("amount", amount));
            form.appendChild(createInput("student_name", student_name));

            document.body.appendChild(form);
            form.submit();
        },
        "theme": { "color": "#ff416c" }
    };

    var rzp = new Razorpay(options);
    rzp.on('payment.failed', function(response) {
        alert("❌ Payment failed: " + response.error.description);
    });
    rzp.open();
}

function createInput(name, value) {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    return input;
}
</script>

<!-- ✅ jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
document.getElementById('download-receipt')?.addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const studentName = "<?php echo addslashes($studentName); ?>";
    const course = "<?php echo addslashes($selectedCourse); ?>";
    const amount = "<?php echo addslashes($selectedAmount); ?>";
    const paymentId = "<?php echo addslashes($paymentId); ?>";

    doc.setFont("helvetica", "bold");
    doc.setFontSize(18);
    doc.text("Payment Receipt", 20, 20);

    doc.setFontSize(12);
    doc.setFont("helvetica", "normal");
    doc.text("Date: " + new Date().toLocaleDateString(), 20, 35);
    doc.text("Payment ID: " + paymentId, 20, 45);
    doc.text("Student Name: " + studentName, 20, 55);
    doc.text("Course: " + course, 20, 65);
    doc.text("Amount Paid: ₹" + amount, 20, 75);
    doc.text("Status: Success", 20, 85);

    doc.save("receipt_" + paymentId + ".pdf");
});
</script>

</body>
</html>
