<?php
$host = "localhost";
$user = "root"; 
$password = ""; 
$dbname = "wanderful";


$conn = new mysqli($host, $user, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

// Check if form submitted by POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and assign variables
    $fullName = sanitize($_POST['fullName']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $destination = sanitize($_POST['destination']);
    $travelStart = sanitize($_POST['travelStart']);
    $travelEnd = sanitize($_POST['travelEnd']);
    $passengers = (int)$_POST['passengers'];
    $specialRequests = sanitize($_POST['specialRequests']);

    
    if (empty($fullName) || strlen($fullName) < 2) {
        die("Full name is required and must be at least 2 characters.");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }
    if (empty($phone)) {
        die("Phone number is required.");
    }
    if (empty($destination)) {
        die("Destination is required.");
    }
    if (empty($travelStart) || empty($travelEnd)) {
        die("Travel dates are required.");
    }
    if ($travelStart > $travelEnd) {
        die("Travel end date must be after start date.");
    }
    if ($passengers < 1 || $passengers > 20) {
        die("Passengers must be between 1 and 20.");
    }


    $sql = "INSERT INTO bookings (fullName, email, phone, destination, travelStart, travelEnd, passengers, specialRequests)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssssssis", $fullName, $email, $phone, $destination, $travelStart, $travelEnd, $passengers, $specialRequests);

   

if ($stmt->execute()) {
    echo "
    <style>
        .success-container {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            backdrop-filter: blur(8px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1050;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.4s ease;
        }
        .success-container.visible {
            opacity: 1;
            pointer-events: auto;
        }
        .success-card {
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            border-radius: 20px;
            padding: 40px 50px;
            box-shadow: 0 25px 40px rgba(155, 89, 182, 0.4);
            max-width: 400px;
            width: 90%;
            color: #fff;
            text-align: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transform: scale(0.8);
            opacity: 0;
            animation-fill-mode: forwards;
            animation-duration: 0.6s;
            animation-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            animation-name: popInScale;
        }
        @keyframes popInScale {
            0% { opacity: 0; transform: scale(0.8); }
            60% { opacity: 1; transform: scale(1.1); }
            100% { opacity: 1; transform: scale(1); }
        }
        .success-icon {
            margin: 0 auto 25px;
            display: block;
            width: 80px;
            height: 80px;
        }
        .checkmark-circle {
            stroke-dasharray: 240;
            stroke-dashoffset: 240;
            stroke-width: 6;
            stroke: #fff;
            fill: none;
            animation: strokeDash 0.6s ease forwards;
        }
        .checkmark-path {
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            stroke-width: 6;
            stroke-linecap: round;
            stroke: #fff;
            fill: none;
            animation: strokeDash 0.3s ease 0.6s forwards;
        }
        @keyframes strokeDash {
            to { stroke-dashoffset: 0; }
        }
        .success-icon-container {
            display: inline-block;
            animation: bounce 1s ease infinite;
            transform-origin: center;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        h2 {
            margin: 0 0 15px 0;
            font-weight: 700;
            font-size: 28px;
            text-shadow: 0 0 8px rgba(255,255,255,0.25);
        }
        p {
            font-weight: 500;
            font-size: 18px;
            line-height: 1.4;
            text-shadow: 0 0 5px rgba(0,0,0,0.2);
        }
        strong {
            text-shadow: 0 0 6px rgba(255,255,255,0.4);
        }
        .fade-out {
            animation: fadeOut 0.5s ease forwards;
        }
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: scale(0.8);
                pointer-events: none;
            }
        }
    </style>

    <div id='booking-success' class='success-container' role='alert' aria-live='assertive' aria-atomic='true'>
        <div class='success-card'>
            <span class='success-icon-container'>
                <svg class='success-icon' viewBox='0 0 52 52' xmlns='http://www.w3.org/2000/svg'>
                    <circle class='checkmark-circle' cx='26' cy='26' r='24' />
                    <path class='checkmark-path' fill='none' d='M14 27l7 7 16-16' />
                </svg>
            </span>
            <h2>Booking Successful!</h2>
            <p>Thank you, <strong>$fullName</strong>. Your booking for <strong>$destination</strong> is confirmed.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successBox = document.getElementById('booking-success');
            successBox.classList.add('visible');
            setTimeout(() => {
                successBox.classList.add('fade-out');
                setTimeout(() => {
                    successBox.style.display = 'none';
                }, 500);
            }, 4000);
        });
    </script>
    ";
} else {
    echo "<p class='error-message' style='color: #b00020; font-weight: 600;'>Error: " . $stmt->error . "</p>";
}


    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
