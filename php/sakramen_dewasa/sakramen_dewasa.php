<?php
include '../user/update_user_status.php';
include '../upload_file.php';
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
    $nama_ayah = mysqli_real_escape_string($conn, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($conn, $_POST['nama_ibu']);
    $nama_suami_istri = mysqli_real_escape_string($conn, $_POST['nama_suami_istri']);
    $tempat_pernikahan = mysqli_real_escape_string($conn, $_POST['tempat_pernikahan']);
    $tanggal_pernikahan = mysqli_real_escape_string($conn, $_POST['tanggal_pernikahan']);
    $saksi_permandian = mysqli_real_escape_string($conn, $_POST['saksi_permandian']);
    $lm_paroki = mysqli_real_escape_string($conn, $_POST['lm_paroki']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $id_admin = mysqli_real_escape_string($conn, $_POST['id_admin']);
    $jumlah_katekumen = mysqli_real_escape_string($conn, $_POST['jumlah_katekumen']);

    // Handle File Uploads
    $fileKKKTPFilePath = uploadFile('file_kk_ktp', '../../uploads/');
    $fileSuratPengantarFilePath = uploadFile('file_surat_pengantar', '../../uploads/');
    $kartu_katekumen = uploadFile('kartu_katekumen', '../../uploads/');


    if ($jumlah_katekumen < 60) {
        echo json_encode(["success" => false, "message" => "Jumlah katekumen kurang dari 60!"]);
        exit;
    }

    $sql = "INSERT INTO sakramen 
            (id_admin, nik, nama_diri, nama_ayah, nama_ibu, nama_suami_istri, tempat_pernikahan, tanggal_pernikahan, saksi_permandian, lm_paroki, alamat, no_hp,kartu_katekumen,jumlah_katekumen,file_kk_ktp, file_surat_pengantar,type) 
            VALUES 
            ('$id_admin', '$nik', '$nama_diri', '$nama_ayah', '$nama_ibu', '$nama_suami_istri', '$tempat_pernikahan', '$tanggal_pernikahan', '$saksi_permandian', '$lm_paroki', '$alamat', '$no_hp', '$kartu_katekumen','$jumlah_katekumen', '$fileKKKTPFilePath', '$fileSuratPengantarFilePath','dewasa')";

    if (mysqli_query($conn, $sql)) {
        updateUserStatus($id_admin, 2, 3);
        echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SIMPAN!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
