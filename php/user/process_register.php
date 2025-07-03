<?php
header('Content-Type: application/json');

$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "gereja_gembala_baik";

$con = mysqli_connect($server_name, $username, $password, $database_name);


if (!$con) {
    echo json_encode(["success" => false, "message" => "Koneksi database gagal!"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    $check_sql = "SELECT * FROM user_login WHERE username = '$username'";
    $check_result = mysqli_query($con, $check_sql);

    if ($password != $confirm_password) {
        echo json_encode(["success" => false, "message" => "Password Tidak Sesuai"]);
    } else

        if (mysqli_num_rows($check_result) > 0) {
            echo json_encode(["success" => false, "message" => "Username sudah terdaftar!"]);
        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO user_login (username, password) VALUES ('$username', '$hashed_password')";

            if (mysqli_query($con, $sql)) {
                echo json_encode(["success" => true, "message" => "Akun berhasil didaftarkan!"]);
            } else {
                echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($con)]);
            }
        }

    mysqli_close($con);
}
