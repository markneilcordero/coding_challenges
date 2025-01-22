<?php
header('Content-Type: application/json');

$users = [
    ["id" => 1, "name" => "Alice"],
    ["id" => 2, "name" => "Bob"],
    ["id" => 3, "name" => "Charlie"],
];

echo json_encode($users);
?>