<?php
$conn = new mysqli('localhost', 'root', '', 'coding_challenge_db');

// Check connection
if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    // Fetch all users
    $result = $conn->query("SELECT * FROM users");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name']) || !isset($data['email']))
    {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Name and email are required."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $data['name'], $data['email']);
    $stmt->execute();
    echo json_encode(["id" => $conn->insert_id]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !isset($data['name']) || !isset($data['email']))
    {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "ID, name, and email are required."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $data['name'], $data['email'], $data['id']);
    $stmt->execute();
    echo json_encode(["updated" => true]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']))
    {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "ID is required."]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    echo json_encode(["deleted" => true]);
}

$conn->close();
?>