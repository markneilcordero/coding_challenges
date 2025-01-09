<?php
$conn = new mysqli('localhost', 'root', '', 'coding_challenge_db');

if ($conn->connect_error)
{
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'HEAD')
{
    header('Content-Type: application/json');
    $id = $_GET['id'] ?? null;
    if ($id)
    {
        $stmt = $conn->prepare("SELECT 1 FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0)
        {
            http_response_code(200); // Resource exists
        }
        else
        {
            http_response_code(404); // Resource not found
        }
    }
    else
    {
        http_response_code(400); // Bad Request
    }
    exit;
}
elseif ($_SERVER['REQUEST_METHOD'] === 'OPTIONS')
{
    header('Allow: GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS');
    exit;
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PATCH')
{
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id']))
    {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "ID is required."]);
        exit;
    }

    $fields = [];
    $values = [];
    if (isset($data['name']))
    {
        $fields[] = "name = ?";
        $values[] = $data['name'];
    }
    if (isset($data['price']))
    {
        $fields[] = "price = ?";
        $values[] = $data['price'];
    }
    if (isset($data['description']))
    {
        $fields[] = "description = ?";
        $values[] = $data['description'];
    }
    $values[] = $data['id'];

    if (!empty($fields))
    {
        $sql = "UPDATE products SET " . implode(", ", $fields) . " WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $types = str_repeat("s", count($fields) - 1) . "i"; // Assign data types
        $stmt->bind_param($types, ...$values);
        $stmt->execute();
        echo json_encode(["updated" => true]);
    }
    else
    {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "No fields to update."]);
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET')
{
    $result = $conn->query("SELECT * FROM products");
    echo json_encode($result->fetch_all(MYSQLI_ASSOC));
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['name']) || !isset($data['price']))
    {
        http_response_code(400);
        echo json_encode(["error" => "Name and price are required."]);
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $data['name'], $data['price'], $data['description']);
    $stmt->execute();
    echo json_encode(["id" => $conn->insert_id]);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT')
{
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['id']) || !isset($data['name']) || !isset($data['price']))
    {
        http_response_code(400);
        echo json_encode(["error" => "ID, name, and price are required."]);
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
    if (!isset($data['id']))
    {
        http_response_code(400);
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