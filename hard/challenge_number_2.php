<?php
$conn = new mysqli('localhost', 'root', '', 'coding_challenge_db');

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $result = $conn->query("SELECT * FROM users");
    $users = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($users);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $data['name'], $data['email']);
    $stmt->execute();
    echo json_encode(["id" => $conn->insert_id]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT')
{
    $id = $_GET['id'];
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $data['name'], $data['email'], $id);
    $stmt->execute();
    echo json_encode(["updated" => true]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE')
{
    $id = $_GET['id'];
    $conn->query("DELETE FROM users WHERE id = $id");
    echo json_encode(["deleted" => true]);
}

$conn->close();
?>