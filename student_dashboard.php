<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Page</title>
    <style>
        /* Rainbow Animated Background */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(270deg, 
                #ff0000,   /* Red */
                #ff7f00,   /* Orange */
                #ffff00,   /* Yellow */
                #00ff00,   /* Green */
                #0000ff,   /* Blue */
                #4b0082,   /* Indigo */
                #8b00ff    /* Violet */
            );
            background-size: 1400% 1400%;
            animation: rainbowMove 15s ease infinite;
            color: #333;
        }

        @keyframes rainbowMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Header with entry animation */
        .header {
            background: rgba(0, 0, 0, 0.7);
            color: #fff;
            text-align: center;
            padding: 15px;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.4);
            animation: fadeInDown 1s ease;
        }

        @keyframes fadeInDown {
            from { transform: translateY(-80px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .sub-header {
            background: rgba(255, 255, 255, 0.8);
            color: #111;
            text-align: center;
            padding: 10px;
            font-size: 20px;
            font-weight: 600;
            animation: fadeIn 2s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Card Content Box */
        .content {
            width: 85%;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.4);
            border-radius: 15px;
            display: flex;
            align-items: flex-start;
            transition: all 0.4s ease;
            animation: floatUp 1.5s ease-out;
        }

        .content:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        }

        @keyframes floatUp {
            from { transform: translateY(60px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .content img {
            width: 220px;
            height: 240px;
            margin-right: 25px;
            border-radius: 10px;
            border: 4px solid #ffcc00;
            transition: transform 0.4s ease, border-color 0.4s ease;
        }

        .content img:hover {
            transform: scale(1.07);
            border-color: #ff0080;
        }

        .content p {
            font-size: 16px;
            line-height: 1.8;
            text-align: justify;
            color: #111;
        }

        .content b {
            color: #d35400;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content {
                flex-direction: column;
                text-align: center;
            }

            .content img {
                margin: 0 auto 20px;
            }
        }
    </style>
</head>
<body>

    <div class="header">Dashboard Page</div>
    <div class="sub-header">President Mam</div>

    <div class="content">
        <img src="President.jpeg" alt="President Mam">
        <div>
            <p>
                <b>SYYD College</b>, located in the heart of India, has been shaping bright minds for over <b>25 years</b>.  
                Our college has earned a reputation as a center of <b>excellence in higher education</b>, attracting over <b>3500+ students</b> every year from across the country.  
                We offer a wide range of programs in <b>Science, Commerce, Arts, and Computer Applications</b>, designed to equip students with the knowledge, skills, and confidence needed to thrive in a competitive world.  
                At SYYD College, our <b>dedicated faculty</b> employ <b>innovative teaching methods</b> and leverage <b>state-of-the-art infrastructure</b> to create a stimulating learning environment.  
                Beyond academics, we focus on <b>holistic development</b> through extracurricular activities, research opportunities, workshops, and seminars that prepare students for leadership and professional excellence.  
                Our mission is to nurture talent, foster creativity, and empower students to become ethical, skilled, and successful individuals who make a positive impact on society.
            </p>
        </div>
    </div>

</body>
</html>