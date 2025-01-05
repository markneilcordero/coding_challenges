<?php
$conn = new mysqli('localhost', 'root', '', 'coding_challenge_db');

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $result = $conn->query("SELECT * FROM blogs");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['title']) || !isset($data['content']))
    {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Title and content are required."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO blogs (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $data['title'], $data['content']);
    $stmt->execute();
    echo json_encode(["id" => $conn->insert_id]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !isset($data['title']) || !isset($data['content']))
    {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "ID, title, and content are required."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE blogs SET title = ?, content = ? WHERE id = ?");
    $stmt->bind_param("ssi", $data['title'], $data['content'], $data['id']);
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

    $stmt = $conn->prepare("DELETE FROM blogs WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    echo json_encode(["deleted" => true]);
}

$conn->close();
?>