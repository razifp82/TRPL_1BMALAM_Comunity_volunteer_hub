<?php
session_start();

include ".././koneksi.php";

$response = array('success' => false);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_kegiatan']) && isset($_POST['action'])) {
    $id_kegiatan = $_POST['id_kegiatan'];
    $id_relawan = $_SESSION['id_relawan'];

    if ($_POST['action'] == 'add') {
        // Gabungkan pengguna ke kegiatan
        $query = "INSERT INTO daftar (id_kegiatan, id_relawan) VALUES ('$id_kegiatan', '$id_relawan')";
    } elseif ($_POST['action'] == 'remove') {
        // Keluarkan pengguna dari kegiatan
        $query = "DELETE FROM daftar WHERE id_kegiatan = '$id_kegiatan' AND id_relawan = '$id_relawan'";
    }

    if ($conn->query($query)) {
        $response['success'] = true;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>