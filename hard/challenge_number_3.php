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
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !isset($data['completed']))
    {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "ID and completed status are required."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE todos SET completed = ? WHERE id = ?");
    $stmt->bind_param("ii", $data['completed'], $data['id']);
    $stmt->execute();
    echo json_encode(["updated" => true]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE')
{
    // Delete a to-do
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']))
    {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "ID is required."]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM todos WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    echo json_encode(["deleted" => true]);
}

$conn->close();
?>