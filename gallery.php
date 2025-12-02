<?php
// Database Connection
$conn = mysqli_connect("localhost", "root", "", "college_db");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch images
$result = mysqli_query($conn, "SELECT * FROM gallery1 ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Gallery</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    background: url("college (2).jpeg") no-repeat center center/cover;
    margin: 0;
    padding: 0;
}

/* Heading style */
h1 {
    text-align: center;
    color: white;
    padding: 20px;
    background: linear-gradient(to right, #0066cc, #33ccff);
    margin-bottom: 30px;
    font-size: 40px;
    letter-spacing: 2px;
    border-radius: 0 0 15px 15px;
    box-shadow: 0px 4px 15px rgba(0,0,0,0.3);
}

/* Gallery layout */
.gallery {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 25px;
    padding: 20px;
}

/* Card style */
.gallery-item {
    width: 280px;
    border-radius: 15px;
    overflow: hidden;
    text-align: center;
    background: #fff;
    transition: transform 0.4s, box-shadow 0.4s;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    cursor: pointer;
}
.gallery-item:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* Image style */
.gallery-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: 0.4s;
}
.gallery-item img:hover {
    filter: brightness(90%);
}

/* Caption style */
.caption {
    padding: 12px;
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    color: white;
    font-weight: bold;
    font-size: 18px;
    letter-spacing: 1px;
}
    </style>
</head>
<body>

    <h1 style="text-align: center;">College Gallery</h1>

    <div class="gallery">
        <div class="gallery-item">
            <img src="college.jpeg" alt="College">
            <div class="caption">College</div>
        </div>
        <div class="gallery-item">
            <img src="Library 1.jpeg"alt="Library">
            <div class="caption">Library</div>
        </div>
        <div class="gallery-item">
            <img src="LAB.jpeg" alt="Computer Lab">
            <div class="caption">Computer Lab</div>
        </div>
    </div>

</body>
</html>