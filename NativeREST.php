<?php
// Objective: Develop a REST API (CRUD) service using PHP8 without using frameworks
// - Create elements
// - Get information about an element
// - Update elements
// - Delete elements
// - Validate entity fields

// - Tests cover both functionality and the DB
// - Use token to access data
// - History of entity changes

// Entity fields for Item:
// 	id - int autoincrement
// 	name - char(255)
// 	phone - char(15)
// 	key - char(25) not null
// 	created_at - datetime - element creation date
// 	updated_at - datetime - element update date

// Start session
session_start();

$token_client = $_SERVER['HTTP_TOKEN'];

// Create token if not in session
if (!isset($_SESSION['token'])) {
    $token_server = NULL;
    $token_client = NULL;
}else
    $token_server = $_SESSION['token'];

// Function to generate a token
function generateToken() {
  // Generate a random token
  $token = bin2hex(random_bytes(16));
  $_SESSION['token'] = $token;

  return $token;
}


// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "test_db";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set response content type to JSON
header('Content-Type: application/json');

// Create tables if they don't exist
$sql = "CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    phone VARCHAR(15),
    `key` CHAR(25) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
$conn->query($sql);

$sql = "CREATE TABLE IF NOT EXISTS history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    action VARCHAR(10),
    item_id INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql);

// Function to validate entity fields
function validateFields($name, $phone, $key) {
    // You can implement your validation logic here
    // For simplicity, let's assume all fields are required
    if (empty($name) || empty($phone) || empty($key)) {
        return false;
    }
    return true;
}

// Function to record action to history table
function recordHistory($action, $itemId) {
    global $conn;
    $sql = "INSERT INTO history (action, item_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $action, $itemId);
    $stmt->execute();
}

// Function to authenticate token
function authenticateToken() {
    global $token_client, $token_server;

    if ($token_client === $token_server)
        return true;
    else
        echo json_encode(array("error" => "Invalid token"));
}

// Function to get item by ID
function getItem($id) {
    global $conn;
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Create item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (authenticateToken()){
        // Implementation for creating item
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'];
        $phone = $data['phone'];
        $key = $data['key'];
        if (validateFields($name, $phone, $key)) {
            $token = generateToken();
            $sql = "INSERT INTO items (name, phone, `key`) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $phone, $key);
            $stmt->execute();
            $response = array("message" => "Item created successfully", "token" => $token);
            recordHistory('CREATE', $stmt->insert_id);
            echo json_encode($response);
        } else {
            echo json_encode(array("error" => "Invalid fields"));
        }
    }
}

// Get item by ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    if (authenticateToken()){
        // Implementation for getting item
        $id = $_GET['id'];
        $item = getItem($id);
        if ($item) {
            echo json_encode($item);
        } else {
            echo json_encode(array("error" => "Item not found"));
        }
    }
}

// Update item by ID
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && isset($_GET['id'])) {
    if (authenticateToken()){
        $id = $_GET['id'];
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'];
        $phone = $data['phone'];
        $key = $data['key'];
        if (validateFields($name, $phone, $key)) {
            $sql = "UPDATE items SET name = ?, phone = ?, `key` = ?, updated_at = NOW() WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $name, $phone, $key, $id);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
              recordHistory('UPDATE', $id);
              echo json_encode(array("message" => "Item updated successfully"));
            } else {
                echo json_encode(array("error" => "Failed to update item"));
            }
        } else {
            echo json_encode(array("error" => "Invalid fields"));
        }
    }
}

// Delete item by ID
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['id'])) {
    if (authenticateToken()){
        $id = $_GET['id'];
        $sql = "DELETE FROM items WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
          recordHistory('DELETE', $id);
          echo json_encode(array("message" => "Item deleted successfully"));
        } else {
            echo json_encode(array("error" => "Failed to delete item"));
        }
    }
}

// Close database connection
$conn->close();
?>
