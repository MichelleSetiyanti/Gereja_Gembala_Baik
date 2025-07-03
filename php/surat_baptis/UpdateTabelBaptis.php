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
    $no_buku = mysqli_real_escape_string($conn, $_POST['no_buku']);
    $no_hal = mysqli_real_escape_string($conn, $_POST['no_hal']);
    $no_baptis = mysqli_real_escape_string($conn, $_POST['no_baptis']);
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_permandian = mysqli_real_escape_string($conn, $_POST['tanggal_permandian']);
    $tempat_permandian = mysqli_real_escape_string($conn, $_POST['tempat_permandian']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $jenis_kelamin = mysqli_real_escape_string($conn, $_POST['jenis_kelamin']);
    $nama_ayah = mysqli_real_escape_string($conn, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($conn, $_POST['nama_ibu']);
    $nama_wali_baptis = mysqli_real_escape_string($conn, $_POST['nama_wali_baptis']);
    $nama_pembaptis = mysqli_real_escape_string($conn, $_POST['nama_pembaptis']);
    $tanggal_penguatan = mysqli_real_escape_string($conn, $_POST['tanggal_penguatan']);
    $tempat_penguatan = mysqli_real_escape_string($conn, $_POST['tempat_penguatan']);
    $nama_pasangan = mysqli_real_escape_string($conn, $_POST['nama_pasangan']);
    $tanggal_pernikahan = mysqli_real_escape_string($conn, $_POST['tanggal_pernikahan']);
    $tempat_pernikahan = mysqli_real_escape_string($conn, $_POST['tempat_pernikahan']);
    $tanggal_komuni = mysqli_real_escape_string($conn, $_POST['tanggal_komuni']);
    $name_pastor_paroki = mysqli_real_escape_string($conn, $_POST['nama_pastor_paroki']);

    error_log(print_r($_POST, true)); // Log semua data yang diterima

    // --- NEW VALIDATION: Check for existing no_hal and no_baptis combination ---
    if ($no_hal !== null && $no_baptis !== null) { // Only check if both are provided
        $sql_check_hal_baptis = "SELECT id FROM surat_baptis WHERE no_hal = ? AND no_baptis = ?";
        $stmt_check_hal_baptis = mysqli_prepare($conn, $sql_check_hal_baptis);
        if (!$stmt_check_hal_baptis) {
            echo json_encode(["success" => false, "message" => "SQL Error preparing 'no_hal' and 'no_baptis' check: " . mysqli_error($conn)]);
            exit;
        }
        // Bind parameters as integers
        mysqli_stmt_bind_param($stmt_check_hal_baptis, "ii", $no_hal, $no_baptis);
        mysqli_stmt_execute($stmt_check_hal_baptis);
        mysqli_stmt_store_result($stmt_check_hal_baptis);

        if (mysqli_stmt_num_rows($stmt_check_hal_baptis) > 0) {
            echo json_encode(["success" => false, "message" => "Nomor Hal dan Nomor Baptis ini sudah terdaftar!"]);
            mysqli_stmt_close($stmt_check_hal_baptis);
            mysqli_close($conn);
            exit;
        }
        mysqli_stmt_close($stmt_check_hal_baptis);
    }
    // --- END NEW VALIDATION ---


    // Query untuk update data (gunakan Prepared Statement)
    $sql = "UPDATE surat_baptis 
            SET no_buku=?,no_hal=?,no_baptis=?, nik=?, tanggal_lahir=?, tempat_lahir=?, tanggal_permandian=?, tempat_permandian=?, nama_lengkap=?, jenis_kelamin=?, nama_ayah=?, nama_ibu=?, nama_wali_baptis=?, nama_pembaptis=?, tanggal_penguatan=?, tempat_penguatan=?, nama_pasangan=?, tanggal_pernikahan=?, tempat_pernikahan=?, tanggal_komuni=?, nama_pastor_paroki=?
            WHERE id=?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["success" => false, "message" => "Gagal mempersiapkan statement: " . $conn->error]);
        exit;
    }

    // Bind parameter ke statement
    $stmt->bind_param(
        "sssssssssssssssssssssi",
        $no_buku,
        $no_hal,
        $no_baptis,
        $nik,
        $tanggal_lahir,
        $tempat_lahir,
        $tanggal_permandian,
        $tempat_permandian,
        $nama_lengkap,
        $jenis_kelamin,
        $nama_ayah,
        $nama_ibu,
        $nama_wali_baptis,
        $nama_pembaptis,
        $tanggal_penguatan,
        $tempat_penguatan,
        $nama_pasangan,
        $tanggal_pernikahan,
        $tempat_pernikahan,
        $tanggal_komuni,
        $name_pastor_paroki,
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
