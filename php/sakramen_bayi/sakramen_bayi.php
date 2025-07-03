<?php
include '../upload_file.php';
include '../user/update_user_status.php';
header('Content-Type: application/json');

$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "gereja_gembala_baik";


$conn = mysqli_connect($server_name, $username, $password, $database_name);


if (!$conn) {
    echo json_encode(["success" => false, "message" => "Koneksi database gagal!"]);
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama_diri = mysqli_real_escape_string($conn, $_POST['nama_diri']);
    $nama_pemandian = mysqli_real_escape_string($conn, $_POST['nama_pemandian']);
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $nama_ayah = mysqli_real_escape_string($conn, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($conn, $_POST['nama_ibu']);
    $tempat_pernikahan = mysqli_real_escape_string($conn, $_POST['tempat_pernikahan']);
    $tanggal_pernikahan = mysqli_real_escape_string($conn, $_POST['tanggal_pernikahan']);
    $lm_paroki = mysqli_real_escape_string($conn, $_POST['lm_paroki']);
    $pastor_memberkati = mysqli_real_escape_string($conn, $_POST['pastor_memberkati']);
    $saksi_permandian = mysqli_real_escape_string($conn, $_POST['saksi_permandian']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $id_admin = mysqli_real_escape_string($conn, $_POST['id_admin']);

    // Handle File Uploads
    $fileKKKTPFilePath = uploadFile('file_kk_ktp', '../../uploads/');
    $fileSuratPengantarFilePath = uploadFile('file_surat_pengantar', '../../uploads/');

    $sql = "INSERT INTO sakramen 
            (id_admin, nik, nama_diri, nama_pemandian, tempat_lahir, tanggal_lahir, nama_ayah, nama_ibu, 
            tempat_pernikahan, tanggal_pernikahan, lm_paroki, pastor_memberkati,
            saksi_permandian, alamat, no_telp, file_kk_ktp, file_surat_pengantar,type) 
            VALUES 
            ('$id_admin', '$nik', '$nama_diri', '$nama_pemandian', '$tempat_lahir', '$tanggal_lahir', '$nama_ayah', '$nama_ibu',
             '$tempat_pernikahan', '$tanggal_pernikahan', '$lm_paroki', '$pastor_memberkati', 
             '$saksi_permandian', '$alamat', '$no_telp','$fileKKKTPFilePath', '$fileSuratPengantarFilePath','bayi')";

    if (mysqli_query($conn, $sql)) {
        updateUserStatus($id_admin, 1, 4);
        echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SIMPAN!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
