<?php
header("Content-Type: application/json");

// Konfigurasi database
$server_name = "localhost";
$username = "root";
$password = "gapura";
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
    $id = isset($_POST['id']) ? (int) $_POST['id'] : null;
    // id_admin buat akun admin yg mana yang login dan ngubah data
    // $id_admin = isset($_POST['id_admin']) ? (int)$_POST['id_admin'] : null; 
    $nik = $_POST['nik'] ?? null;
    $nama_diri = $_POST['nama_diri'] ?? null;
    $nama_pemandian = $_POST['nama_pemandian'] ?? null;
    $tempat_lahir = $_POST['tempat_lahir'] ?? null;
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? null;
    $nama_ayah = $_POST['nama_ayah'] ?? null;
    $nama_ibu = $_POST['nama_ibu'] ?? null;
    $tempat_pernikahan = $_POST['tempat_pernikahan'] ?? null;
    $tanggal_pernikahan = $_POST['tanggal_pernikahan'] ?? null;
    $lm_paroki = $_POST['lm_paroki'] ?? null;
    $pastor_memberkati = $_POST['pastor_memberkati'] ?? null;
    $saksi_permandian = $_POST['saksi_permandian'] ?? null;
    $alamat = $_POST['alamat'] ?? null;
    $no_telp = $_POST['no_telp'] ?? null;


    // Query untuk update data (gunakan Prepared Statement)
    $sql = "UPDATE sakramen 
            SET nik=?, nama_diri=?, nama_pemandian=?, tempat_lahir=?, tanggal_lahir=?, nama_ayah=?, nama_ibu=?, tempat_pernikahan=?, tanggal_pernikahan=?, lm_paroki=?, pastor_memberkati=?, saksi_permandian=?, alamat=?, no_telp=? 
            WHERE id=?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Gagal mempersiapkan statement: " . $conn->error]);
        exit;
    }

    // Bind parameter ke statement (semua dalam format string kecuali id)
    $stmt->bind_param("issssssssssssss", $nik, $nama_diri, $nama_pemandian, $tempat_lahir, $tanggal_lahir, $nama_ayah, $nama_ibu, $tempat_pernikahan, $tanggal_pernikahan, $lm_paroki, $pastor_memberkati, $saksi_permandian, $alamat, $no_telp, $id);

    // Eksekusi query
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Data berhasil diperbarui"]);
    } else {
        error_log("Error updating ID $id: " . $stmt->error);
        echo json_encode(["success" => false, "message" => "Gagal mengupdate data", "error" => $stmt->error]);
    }

    // Tutup statement dan koneksi
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "message" => "Metode tidak valid"]);
}
