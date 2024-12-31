<?php
$conn = new mysqli('localhost', 'root', '', 'coding_challenge_db');

// Check connection
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

// Handle API requests
if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    // Fetch all to-dos
    $result = $conn->query("SELECT * FROM todos");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    // Add a new to-do
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO todos (task) VALUES (?)");
    $stmt->bind_param("s", $data['task']);
    $stmt->execute();
    echo json_encode(["id" => $conn->insert_id]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT')
{
    // Update a to-do
    parse_str(file_get_contents('php://input'), $_PUT);
    $id = $_PUT['id'];
    $completed = $_PUT['completed'];
    $stmt = $conn->prepare("UPDATE todos SET completed = ? WHERE id = ?");
    $stmt->bind_param("ii", $completed, $id);
    $stmt->execute();
    echo json_encode(["updated" => true]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE')
{
    // Delete a to-do
    parse_str(file_get_contents('php://input'), $_DELETE);
    $id = $_DELETE['id'];
    $stmt = $conn->prepare("DELETE FROM todos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode(["deleted" => true]);
}

$conn->close();
?>