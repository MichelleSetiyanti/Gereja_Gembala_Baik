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
    // $id_admin = isset($_POST['id_admin']) ? (int)$_POST['id_admin'] : null;

    $nik  = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $nama_ayah = mysqli_real_escape_string($conn, $_POST['nama_ayah']);
    $nama_ibu  = mysqli_real_escape_string($conn, $_POST['nama_ibu']);
    $sd_kelas = mysqli_real_escape_string($conn, $_POST['sd_kelas']);
    $nama_sekolah = mysqli_real_escape_string($conn, $_POST['nama_sekolah']);
    $tanggal_katekumen = mysqli_real_escape_string($conn, $_POST['tanggal_katekumen']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $detail_alamat = mysqli_real_escape_string($conn, $_POST['detail_alamat']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $id_admin = mysqli_real_escape_string($conn, $_POST['id_admin']);

    error_log(print_r($_POST, true)); // Log semua data yang diterima

    // Query untuk update data (gunakan Prepared Statement)
    $sql = "UPDATE katekumen_anak 
            SET nik=?, nama_lengkap=?, nama_ayah=?, nama_ibu=?, sd_kelas=?, nama_sekolah=?, tanggal_katekumen=?, alamat=?, detail_alamat=?, no_telp=?,id_admin=? 
            WHERE id_kateanak=?";

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
        $nama_ayah,
        $nama_ibu,
        $sd_kelas,
        $nama_sekolah,
        $tanggal_katekumen,
        $alamat,
        $detail_alamat,
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
