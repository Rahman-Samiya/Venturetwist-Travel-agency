<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "wanderful");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get login form data
$email = $_POST['email'];
$password = $_POST['password'];

// Check user
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();


// Assuming you already have your DB connection and form processing setup above

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        // Login successful
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Login Successful!',
                    text: 'Welcome, " . htmlspecialchars($user['name']) . "!',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didClose: () => {
                        window.location.href = 'index.html';
                    }
                });
            </script>
        </body>
        </html>";
    } else {
        // Wrong password
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Invalid Password!',
                    text: 'Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            </script>
        </body>
        </html>";
    }
} else {
    // No user found
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'User Not Found!',
                text: 'No account associated with that email.',
                icon: 'warning',
                confirmButtonColor: '#f39c12'
            });
        </script>
    </body>
    </html>";
}


$conn->close();
?>
