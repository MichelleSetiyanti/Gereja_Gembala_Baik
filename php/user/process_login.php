<?php
session_start();
header("Content-Type: application/json");

$server_name = "localhost";
$username = "root";
$password = "gapura";
$database_name = "gereja_gembala_baik";


$con = mysqli_connect($server_name, $username, $password, $database_name);

if (!$con) {
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal!"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $sql = "SELECT * FROM user_login WHERE username = '$username'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);


        if (strcmp($username, $row['username']) === 0 && password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            echo json_encode(["status" => "success", "message" => "Login berhasil!", "data" => json_encode($row)]);
        } else {
            echo json_encode(["status" => "error", "message" => "Password salah!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Akun tidak ditemukan. Silakan hubungi admin untuk registrasi."]);
    }

    mysqli_close($con);
}
