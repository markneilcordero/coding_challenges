<?php
$conn = new mysqli('localhost', 'root', '', 'coding_challenge_db');

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $result = $conn->query('SELECT * FROM tasks');
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['title']))
    {
        http_response_code(400);
        echo json_encode(["error" => "Title is required."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO tasks (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $data['title'], $data['description']);
    $stmt->execute();
    echo json_encode(["id" => $conn->insert_id]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !isset($data['title']))
    {
        http_response_code(400);
        echo json_encode(["error" => "ID and title are required."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ?");
    $stmt->bind_param("sssi", $data['title'], $data['description'], $data['status'], $data['id']);
    $stmt->execute();
    echo json_encode(["updated" => true]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']))
    {
        http_response_code(400);
        echo json_encode(["error" => "ID is required."]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    echo json_encode(["deleted" => true]);
}

$conn->close();
?>