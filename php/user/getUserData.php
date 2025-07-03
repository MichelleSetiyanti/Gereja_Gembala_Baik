<?php
header('Content-Type: application/json');
$server_name = "localhost";
$username = "root";
$password = "gapura";
$database_name = "gereja_gembala_baik";

$conn = mysqli_connect($server_name, $username, $password, $database_name);

if (isset($_GET['userId'])) {

    $userId = $_GET['userId'];

    $sql = "SELECT * FROM user_login WHERE id_admin = $userId";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo json_encode(["success" => true, "data" => $row]);
    } else {
        echo json_encode(["success" => false, "message" => "User not found"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid user ID"]);
}
