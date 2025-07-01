<?php
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
    $nik = $_POST['nik'] ?? null;
    $nama_diri = $_POST['nama_diri'] ?? null;
    $nama_ayah = $_POST['nama_ayah'] ?? null;
    $nama_ibu = $_POST['nama_ibu'] ?? null;
    $nama_suami_istri = $_POST['nama_suami_istri'] ?? null;
    $tempat_pernikahan = $_POST['tempat_pernikahan'] ?? null;
    $tanggal_pernikahan = $_POST['tanggal_pernikahan'] ?? null;
    $tempat_permandian = $_POST['tempat_permandian'] ?? null;
    $tanggal_permandian = $_POST['tanggal_permandian'] ?? null;
    $saksi_permandian = $_POST['saksi_permandian'] ?? null;
    $lm_paroki = $_POST['lm_paroki'] ?? null;
    $pastor_pembaptis = $_POST['pastor_pembaptis'] ?? null;
    $jumlah_katekumen = $_POST['jumlah_katekumen'] ?? null;
    $alamat = $_POST['alamat'] ?? null;
    $no_hp = $_POST['no_hp'] ?? null;

    error_log(print_r($_POST, true)); // Log semua data yang diterima

    // Query untuk update data (gunakan Prepared Statement)
    $sql = "UPDATE sakramen 
            SET nik=?, nama_diri=?, nama_ayah=?, nama_ibu=?, nama_suami_istri=?, tempat_pernikahan=?, tanggal_pernikahan=?,  saksi_permandian=?, lm_paroki=?, alamat=?, no_hp=?, jumlah_katekumen=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Gagal mempersiapkan statement: " . $conn->error]);
        exit;
    }

    // Bind parameter ke statement
    $stmt->bind_param(
        "ssssssssssssi",
        $nik,
        $nama_diri,
        $nama_ayah,
        $nama_ibu,
        $nama_suami_istri,
        $tempat_pernikahan,
        $tanggal_pernikahan,
        $saksi_permandian,
        $lm_paroki,
        $alamat,
        $no_hp,
        $jumlah_katekumen,
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
