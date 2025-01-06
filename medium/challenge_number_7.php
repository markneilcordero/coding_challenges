<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'coding_challenge_db');

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password']))
    {
        $_SESSION['user'] = $user['email'];
        echo "Login successful! Welcome, " . $user['email'];
    }
    else
    {
        echo "Invalid email or password.";
    }
}

$conn->close();
?>