<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "wanderful";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create a new post
function createPost($title, $content, $image, $tags) {
    global $conn;

    // Insert post into posts table
    $stmt = $conn->prepare("INSERT INTO posts (title, content, image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $content, $image);
    $stmt->execute();
    $postId = $stmt->insert_id; // Get the ID of the newly created post
    $stmt->close();

    // Insert tags into tags table and post_tags relationship
    foreach ($tags as $tag) {
        // Check if tag already exists
        $stmt = $conn->prepare("INSERT INTO tags (name) VALUES (?) ON DUPLICATE KEY UPDATE id=id");
        $stmt->bind_param("s", $tag);
        $stmt->execute();
        $tagId = $conn->insert_id; // Get the ID of the tag

        // Insert into post_tags
        $stmt = $conn->prepare("INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $postId, $tagId);
        $stmt->execute();
    }
}

// Function to fetch all posts
function fetchPosts() {
    global $conn;
    $sql = "SELECT p.id, p.title, p.content, p.image, p.likes, p.created_at, 
                   GROUP_CONCAT(t.name) AS tags
            FROM posts p
            LEFT JOIN post_tags pt ON p.id = pt.post_id
            LEFT JOIN tags t ON pt.tag_id = t.id
            GROUP BY p.id";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Example usage
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_POST['image']; // Assume this is a base64 string
    $tags = explode(',', $_POST['tags']); // Tags as comma-separated string

    createPost($title, $content, $image, $tags);
}

$posts = fetchPosts();
$conn->close();
?>
