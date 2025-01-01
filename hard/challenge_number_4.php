<?php
$conn = new mysqli('localhost', 'root', '', 'coding_challenge_db');

if ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $result = $conn->query("SELECT * FROM products");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $conn->prepare("INSERT INTO products (name, price, description) VALUES (?,?,?)");
    $stmt->bind_param("sds", $data['name'], $data['price'], $data['description']);
    $stmt->execute();
    echo json_encode(["id" => $conn->insert_id]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']) || !isset($data['name']) || !isset($data['price']) || !isset($data['description']))
    {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "ID, name, price, and description are required."]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $data['name'], $data['price'], $data['description'], $data['id']);
    $stmt->execute();
    echo json_encode(["updated" => true]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "ID is required."]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    echo json_encode(["deleted" => true]);
}

$conn->close();
?>