<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "wanderful");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data and sanitize
$name = $_POST['name'];
$email = $_POST['email'];
$password = ($_POST['password']); 

// Check if email exists
$check = $conn->prepare("SELECT * FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();


// Assuming connection ($conn) and user input ($name, $email, $password) are already available

if ($result->num_rows > 0) {
    // Email already exists
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Email Already Exists!',
                text: 'Try logging in instead.',
                confirmButtonText: 'Go to Login',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.href = 'index.html';
            });
        </script>
    </body>
    </html>";
} else {
    // Insert new user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        // Registration successful
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    text: 'You can now log in.',
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    didClose: () => {
                        window.location.href = 'index.html';
                    }
                });
            </script>
        </body>
        </html>";
    } else {
        // Error during registration
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Registration Failed!',
                    text: '" . addslashes($stmt->error) . "',
                    confirmButtonColor: '#d33'
                });
            </script>
        </body>
        </html>";
    }
}



$conn->close();
?>
