<?php

use function PHPSTORM_META\type;

include "../upload_file.php";
header('Content-Type: application/json');

$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "gereja_gembala_baik";

// Establish database connection
$conn = mysqli_connect($server_name, $username, $password, $database_name);
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Koneksi database gagal!"]);
    exit;
}

// Handle only POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    function sanitize($conn, $data)
    {
        return mysqli_real_escape_string($conn, trim($data));
    }

    // Required fields
    $no_buku = sanitize($conn, $_POST['no_buku']);
    $no_hal = sanitize($conn, $_POST['no_hal']);
    $no_baptis = sanitize($conn, $_POST['no_baptis']);
    $nik = sanitize($conn, $_POST['nik']);
    $tanggal_lahir = sanitize($conn, $_POST['tanggal_lahir']);
    $tempat_lahir = sanitize($conn, $_POST['tempat_lahir']);
    $tanggal_permandian = sanitize($conn, $_POST['tanggal_permandian']);
    $tempat_permandian = sanitize($conn, $_POST['tempat_permandian']);
    $nama_lengkap = sanitize($conn, $_POST['nama_lengkap']);
    $jenis_kelamin = sanitize($conn, $_POST['jenis_kelamin']);
    $nama_ayah = sanitize($conn, $_POST['nama_ayah']);
    $nama_ibu = sanitize($conn, $_POST['nama_ibu']);
    $nama_wali_baptis = sanitize($conn, $_POST['nama_wali_baptis']);
    $nama_pembaptis = sanitize($conn, $_POST['nama_pembaptis']);
    $tanggal_penguatan = sanitize($conn, $_POST['tanggal_penguatan']);
    $tempat_penguatan = sanitize($conn, $_POST['tempat_penguatan']);
    $nama_pasangan = sanitize($conn, $_POST['nama_pasangan']);
    $tanggal_pernikahan = sanitize($conn, $_POST['tanggal_pernikahan']);
    $tempat_pernikahan = sanitize($conn, $_POST['tempat_pernikahan']);
    $tanggal_komuni = sanitize($conn, $_POST['tanggal_komuni']);
    $sakramen_id = sanitize($conn, isset($_POST['sakramen_id']) ? $_POST['sakramen_id'] :  null);
    $id_admin = sanitize($conn, $_POST['id_admin']);
    $type = sanitize($conn, $_POST['type']);
    $nama_baptis = sanitize($conn, $_POST['nama_baptis']);
    $alamat = sanitize($conn, $_POST['alamat']);
    $nama_pastor_paroki = sanitize($conn, $_POST['nama_pastor_paroki']);
    $type = sanitize($conn, $_POST['type']);

    // Handle File Uploads
    $fileKKKTPFilePath = uploadFile('file_kk_ktp', '../../uploads/');
    $fileSuratPengantarFilePath = uploadFile('file_surat_pengantar', '../../uploads/');

    if ($type !== 'bayi') {
        $sql = "SELECT jumlah_katekumen FROM sakramen WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $sakramen_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);

        if (!$data || $data['jumlah_katekumen'] < 60) {
            echo json_encode(["success" => false, "message" => "Jumlah katekumen kurang dari 60!"]);
            exit;
        }
    }

    $sql = "SELECT * FROM surat_baptis WHERE nik = $nik AND (status = 1 OR status = 2)";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(["success" => false, "message" => "Surat Baptis yang Sudah Disetujui atau Menunggu Persetujuan sudah menggunakan NIK ini!"]);
        exit;
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    if ($type === 'dewasa') {
        $sql = "INSERT INTO surat_baptis 
    (id_admin,no_buku,no_hal,no_baptis, nik, tanggal_lahir, tempat_lahir, tanggal_permandian, tempat_permandian, nama_lengkap, jenis_kelamin, nama_ayah, 
    nama_ibu, nama_wali_baptis, nama_pembaptis, tanggal_penguatan, tempat_penguatan, nama_pasangan, tanggal_pernikahan, tempat_pernikahan, tanggal_komuni, file_kk_ktp, file_surat_pengantar, nama_baptis, alamat, nama_pastor_paroki,type) 
    VALUES 
    (?, ?, ?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

        $stmt = mysqli_prepare(
            $conn,
            $sql
        );
        if (!$stmt) {
            die("SQL Error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "issssssssssssssssssssssssss",
            $id_admin,
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
            $fileKKKTPFilePath,
            $fileSuratPengantarFilePath,
            $nama_baptis,
            $alamat,
            $nama_pastor_paroki,
            $type
        );

        if (!mysqli_stmt_execute($stmt)) {
            die("Error executing statement (dewasa): " . mysqli_stmt_error($stmt));
        }
    } else {
        $sql = "INSERT INTO surat_baptis 
    (id_admin,no_buku,no_hal,no_baptis,nik, tanggal_lahir, tempat_lahir, tanggal_permandian, tempat_permandian, nama_lengkap, jenis_kelamin, nama_ayah, 
     nama_ibu, nama_wali_baptis, nama_pembaptis, tanggal_penguatan, tempat_penguatan, nama_pasangan, tanggal_pernikahan, tempat_pernikahan, tanggal_komuni, file_kk_ktp, file_surat_pengantar, type, nama_baptis, alamat, nama_pastor_paroki) 
    VALUES 
    (?, ?, ?,?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare(
            $conn,
            $sql
        );
        if (!$stmt) {
            die("Error in prepare (bayi): " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param(
            $stmt,
            "issssssssssssssssssssssssss",
            $id_admin,
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
            $fileKKKTPFilePath,
            $fileSuratPengantarFilePath,
            $type,
            $nama_baptis,
            $alamat,
            $nama_pastor_paroki,
        );

        if (!mysqli_stmt_execute($stmt)) {
            die("Error executing statement (bayi): " . mysqli_stmt_error($stmt));
        }
    }

    // After successful insertion
    $baptis_id = mysqli_insert_id($conn);
    echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SIMPAN!"]);


    // Close connections
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
