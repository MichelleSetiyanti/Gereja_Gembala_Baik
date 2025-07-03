<?php

// Include your file upload function
include "../upload_file.php";

// Set the content type to JSON. This is crucial for AJAX requests.
header('Content-Type: application/json');

// Database connection details
$server_name = "localhost";
$username = "root";
$password = "gapura";
$database_name = "gereja_gembala_baik";

// Establish database connection
$conn = mysqli_connect($server_name, $username, $password, $database_name);
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Koneksi database gagal! " . mysqli_connect_error()]);
    exit; // Stop execution if connection fails
}

// Handle only POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize input data function
    function sanitize($conn, $data)
    {
        // Return null if data is null, otherwise trim and escape
        return $data === null ? null : mysqli_real_escape_string($conn, trim($data));
    }

    // Capture and sanitize all input fields
    $no_buku = sanitize($conn, $_POST['no_buku'] ?? ''); // Use ?? '' for safety if field might be missing
    $no_hal = sanitize($conn, $_POST['no_hal'] ?? '');
    $no_baptis = sanitize($conn, $_POST['no_baptis'] ?? '');
    $nik = sanitize($conn, $_POST['nik'] ?? '');
    $tanggal_lahir = sanitize($conn, $_POST['tanggal_lahir'] ?? '');
    $tempat_lahir = sanitize($conn, $_POST['tempat_lahir'] ?? '');
    $tanggal_permandian = sanitize($conn, $_POST['tanggal_permandian'] ?? '');
    $tempat_permandian = sanitize($conn, $_POST['tempat_permandian'] ?? '');
    $nama_lengkap = sanitize($conn, $_POST['nama_lengkap'] ?? '');
    $jenis_kelamin = sanitize($conn, $_POST['jenis_kelamin'] ?? '');
    $nama_ayah = sanitize($conn, $_POST['nama_ayah'] ?? '');
    $nama_ibu = sanitize($conn, $_POST['nama_ibu'] ?? '');
    $nama_wali_baptis = sanitize($conn, $_POST['nama_wali_baptis'] ?? '');
    $nama_pembaptis = sanitize($conn, $_POST['nama_pembaptis'] ?? '');
    $tanggal_penguatan = sanitize($conn, $_POST['tanggal_penguatan'] ?? '');
    $tempat_penguatan = sanitize($conn, $_POST['tempat_penguatan'] ?? '');
    $nama_pasangan = sanitize($conn, $_POST['nama_pasangan'] ?? '');
    $tanggal_pernikahan = sanitize($conn, $_POST['tanggal_pernikahan'] ?? '');
    $tempat_pernikahan = sanitize($conn, $_POST['tempat_pernikahan'] ?? '');
    $tanggal_komuni = sanitize($conn, $_POST['tanggal_komuni'] ?? '');
    // Ensure sakramen_id is properly handled, as it might be an integer or null
    $sakramen_id_raw = $_POST['sakramen_id'] ?? null;
    $sakramen_id = empty($sakramen_id_raw) ? null : (int) $sakramen_id_raw; // Cast to int or null

    $id_admin = sanitize($conn, $_POST['id_admin'] ?? ''); // Assuming id_admin is required and always present
    $type = sanitize($conn, $_POST['type'] ?? ''); // This is for 'dewasa' or 'bayi'
    $nama_baptis = sanitize($conn, $_POST['nama_baptis'] ?? '');
    $alamat = sanitize($conn, $_POST['alamat'] ?? '');
    $nama_pastor_paroki = sanitize($conn, $_POST['nama_pastor_paroki'] ?? '');

    // --- Critical Fixes for no_hal and no_baptis ---
    // Convert empty strings to actual NULL values for database columns expecting integers
    // if they are allowed to be NULL.
    $no_hal = empty($no_hal) ? null : (int) $no_hal;
    $no_baptis = empty($no_baptis) ? null : (int) $no_baptis;
    // --- End Critical Fixes ---

    // Handle File Uploads (ensure uploadFile function is robust and returns null on failure/no file)
    $fileKKKTPFilePath = uploadFile('file_kk_ktp', '../../uploads/');
    $fileSuratPengantarFilePath = uploadFile('file_surat_pengantar', '../../uploads/');

    // Validate sakramen_id for non-bayi types
    if ($type !== 'bayi' && $sakramen_id !== null) { // Only proceed if sakramen_id is provided
        $sql = "SELECT jumlah_katekumen FROM sakramen WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            echo json_encode(["success" => false, "message" => "SQL Error preparing katekumen check: " . mysqli_error($conn)]);
            exit;
        }
        mysqli_stmt_bind_param($stmt, "i", $sakramen_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt); // Close statement for katekumen check

        if (!$data || $data['jumlah_katekumen'] < 60) {
            echo json_encode(["success" => false, "message" => "Jumlah katekumen kurang dari 60!"]);
            exit;
        }
    }

    // Check for existing Surat Baptis with the same NIK (VULNERABILITY FIX)
    // IMPORTANT: Use prepared statement for NIK check to prevent SQL injection
    $sql_check_nik = "SELECT id FROM surat_baptis WHERE nik = ? AND (status = 1 OR status = 2)";
    $stmt_check_nik = mysqli_prepare($conn, $sql_check_nik);
    if (!$stmt_check_nik) {
        echo json_encode(["success" => false, "message" => "SQL Error preparing NIK check: " . mysqli_error($conn)]);
        exit;
    }
    mysqli_stmt_bind_param($stmt_check_nik, "s", $nik); // NIK is likely a string
    mysqli_stmt_execute($stmt_check_nik);
    mysqli_stmt_store_result($stmt_check_nik); // Store result to check number of rows

    if (mysqli_stmt_num_rows($stmt_check_nik) > 0) {
        echo json_encode(["success" => false, "message" => "Surat Baptis yang Sudah Disetujui atau Menunggu Persetujuan sudah menggunakan NIK ini!"]);
        mysqli_stmt_close($stmt_check_nik);
        mysqli_close($conn);
        exit;
    }
    mysqli_stmt_close($stmt_check_nik); // Close statement after check

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


    // Enable detailed MySQLi error reporting (good for debugging, remove in production)
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $sql = ""; // Initialize SQL variable

    if ($type === 'dewasa') {
        $sql = "INSERT INTO surat_baptis
        (id_admin, no_buku, no_hal, no_baptis, nik, tanggal_lahir, tempat_lahir, tanggal_permandian, tempat_permandian, nama_lengkap, jenis_kelamin, nama_ayah,
        nama_ibu, nama_wali_baptis, nama_pembaptis, tanggal_penguatan, tempat_penguatan, nama_pasangan, tanggal_pernikahan, tempat_pernikahan, tanggal_komuni, file_kk_ktp, file_surat_pengantar, nama_baptis, alamat, nama_pastor_paroki, type)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            echo json_encode(["success" => false, "message" => "SQL Error preparing statement (dewasa): " . mysqli_error($conn)]);
            exit;
        }

        // --- IMPORTANT: Adjust this bind_param string based on your exact database column types ---
        // Assuming:
        // id_admin (int) -> i
        // no_buku (string) -> s
        // no_hal (int NULL) -> i
        // no_baptis (int NULL) -> i
        // All other fields (nik, dates, names, paths, type) are strings -> s
        mysqli_stmt_bind_param(
            $stmt,
            "isiisssssssssssssssssssssss", // Check this string very carefully!
            $id_admin,
            $no_buku,
            $no_hal, // Will be int or null
            $no_baptis, // Will be int or null
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
            echo json_encode(["success" => false, "message" => "Error executing statement (dewasa): " . mysqli_stmt_error($stmt)]);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            exit;
        }
    } else { // type === 'bayi'
        $sql = "INSERT INTO surat_baptis
        (id_admin, no_buku, no_hal, no_baptis, nik, tanggal_lahir, tempat_lahir, tanggal_permandian, tempat_permandian, nama_lengkap, jenis_kelamin, nama_ayah,
        nama_ibu, nama_wali_baptis, nama_pembaptis, tanggal_penguatan, tempat_penguatan, nama_pasangan, tanggal_pernikahan, tempat_pernikahan, tanggal_komuni, file_kk_ktp, file_surat_pengantar, type, nama_baptis, alamat, nama_pastor_paroki)
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            echo json_encode(["success" => false, "message" => "Error preparing statement (bayi): " . mysqli_error($conn)]);
            exit;
        }

        // --- IMPORTANT: Adjust this bind_param string based on your exact database column types ---
        // Ensure the order matches the SQL query's column list for 'bayi' type!
        // Assuming:
        // id_admin (int) -> i
        // no_buku (string) -> s
        // no_hal (int NULL) -> i
        // no_baptis (int NULL) -> i
        // All other fields (nik, dates, names, paths, type) are strings -> s
        mysqli_stmt_bind_param(
            $stmt,
            "isiisssssssssssssssssssssss", // Check this string very carefully!
            $id_admin,
            $no_buku,
            $no_hal, // Will be int or null
            $no_baptis, // Will be int or null
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
            $type, // Position of type variable differs here from 'dewasa'
            $nama_baptis,
            $alamat,
            $nama_pastor_paroki
        );

        if (!mysqli_stmt_execute($stmt)) {
            echo json_encode(["success" => false, "message" => "Error executing statement (bayi): " . mysqli_stmt_error($stmt)]);
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            exit;
        }
    }

    // If all successful
    $baptis_id = mysqli_insert_id($conn);
    echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SIMPAN!"]);

    // Close connections
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    exit; // Ensure nothing else is sent after JSON
}

// If the request method is not POST, send an error response
echo json_encode(["success" => false, "message" => "Metode permintaan tidak valid!"]);
exit; // Ensure nothing else is sent
?>