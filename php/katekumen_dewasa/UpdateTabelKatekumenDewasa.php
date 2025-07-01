<?php
include "../upload_file.php";
header("Content-Type: application/json");

// Konfigurasi database
$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "gereja_gembala_baik";

$conn = mysqli_connect($server_name, $username, $password, $database_name);

// Cek koneksi database
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Koneksi database gagal!"]);
    exit;
}

// Cek metode request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari request
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    // id_admin buat akun admin yg mana yang login dan ngubah data
    $id_admin = isset($_POST['id_admin']) ? (int)$_POST['id_admin'] : null;

    $nik = $_POST['nik'] ?? null;
    $nama_lengkap = $_POST['nama_lengkap'] ?? null;
    $tempat_lahir = $_POST['tempat_lahir'] ?? null;
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? null;
    $nama_ayah = $_POST['nama_ayah'] ?? null;
    $nama_ibu = $_POST['nama_ibu'] ?? null;
    $nama_gereja = $_POST['nama_gereja'] ?? null;
    $nama_suami_istri = $_POST['nama_suami_istri'] ?? null;
    $alamat = $_POST['alamat'] ?? null;
    $no_telp = $_POST['no_telp'] ?? null;

    error_log(print_r($_POST, true)); // Log semua data yang diterima

    // Query untuk update data (gunakan Prepared Statement)
    $sql = "UPDATE katekumen_dewasa 
            SET nik=?, nama_lengkap=?, tempat_lahir=?, tanggal_lahir=?, nama_ayah=?, nama_ibu=?,nama_gereja=?,nama_suami_istri=?,alamat=?,no_telp=?, id_admin=?
            WHERE id_katedewasa=?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Gagal mempersiapkan statement: " . $conn->error]);
        exit;
    }

    // Bind parameter ke statement
    $stmt->bind_param(
        "sssssssssssi",
        $nik,
        $nama_lengkap,
        $tempat_lahir,
        $tanggal_lahir,
        $nama_ayah,
        $nama_ibu,
        $nama_gereja,
        $nama_suami_istri,
        $alamat,
        $no_telp,
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
