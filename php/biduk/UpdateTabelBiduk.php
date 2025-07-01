<?php
header("Content-Type: application/json");
$server_name = "localhost";  // Sesuaikan dengan konfigurasi server Anda
$username = "root";          // Sesuaikan
$password = "";              // Sesuaikan
$database_name = "gereja_gembala_baik"; // Sesuaikan

$conn = mysqli_connect($server_name, $username, $password, $database_name);

if (!$conn) {
    echo json_encode(["success" => false, "message" => "Koneksi database gagal!"]);
    exit;
}



// Cek metode request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari request
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    // id_admin buat akun admin yg mana yang login dan ngubah data
    // $id_admin = isset($_POST['id_admin']) ? (int)$_POST['id_admin'] : null;
    $nik = $_POST['nik'] ?? null;
    $nama_keluarga = $_POST['nama_keluarga'] ?? null;
    $alamat = $_POST['alamat'] ?? null;
    $id_admin = $_POST['id_admin'] ?? null;

    error_log(print_r($_POST, true)); // Log semua data yang diterima


    // Query untuk update data (gunakan Prepared Statement)
    $sql = "UPDATE data_biduk 
            SET nik=?, nama_keluarga=?, alamat=?, id_admin=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Gagal mempersiapkan statement: " . $conn->error]);
        exit;
    }

    // Bind parameter ke statement
    $stmt->bind_param(
        "sssii",
        $nik,
        $nama_keluarga,
        $alamat,
        $id_admin,
        $id
    );

    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Data berhasil diperbarui"]);
    } else {
        error_log("Query error: " . $stmt->error);
        echo json_encode(["success" => false, "message" => "Gagal mengupdate data", "error" => $stmt->error]);
    }


    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Metode tidak valid"]);
}
