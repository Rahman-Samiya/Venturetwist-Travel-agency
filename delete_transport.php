<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wanderful";

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($input['id'])) {
        throw new Exception("Invalid request");
    }

    $id = (int)$input['id'];
    
    // Prepare and execute delete statement
    $stmt = $conn->prepare("DELETE FROM transports WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if (!$stmt->execute()) {
        throw new Exception("Error deleting transport: " . $stmt->error);
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception("No transport found with ID: $id");
    }

    echo json_encode([
        'success' => true,
        'message' => 'Transport deleted successfully',
        'deleted_id' => $id
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
}
?>
