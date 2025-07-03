<?php
$server_name = "localhost";  // Sesuaikan dengan konfigurasi server Anda
$username = "root";          // Sesuaikan
$password = "gapura";              // Sesuaikan
$database_name = "gereja_gembala_baik"; // Sesuaikan

$conn = mysqli_connect($server_name, $username, $password, $database_name);
function updateUserStatus($user_id, $check_status, $new_status)
{
    global $conn;

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT status FROM user_login WHERE id_admin = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return false; // User not found
    }

    $row = $result->fetch_assoc();
    if ($row['status'] === $check_status) {
        // Make sure 'status' column exists in 'sakramen_dewasa'
        $updateStmt = $conn->prepare("UPDATE user_login SET status = ? WHERE id_admin = ?");
        $updateStmt->bind_param("si", $new_status, $user_id);
        $success = $updateStmt->execute();

        return $success;
    }

    return false; // No update needed
}
