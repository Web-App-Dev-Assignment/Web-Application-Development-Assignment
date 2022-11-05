<?php

$db_conn = require __DIR__ . "/database.php";

$sql = sprintf("SELECT * FROM user
                WHERE username = '%s'",
                $mysqli->real_escape_string($_GET["username"]));
                
$result = $db_conn->query($sql);

$is_available = $result->num_rows === 0;

$sql = sprintf("SELECT * FROM user
                WHERE email = '%s'",
                $mysqli->real_escape_string($_GET["email"]));
                
$result = $db_conn->query($sql);

header("Content-Type: application/json");

echo json_encode(["available" => $is_available]);

?>