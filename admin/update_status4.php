<?php
include ".././koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pastikan variabel yang diterima valid
    $id_kegiatan = isset($_POST['id_kegiatan']) ? $_POST['id_kegiatan'] : '';

    // Lakukan operasi penghapusan data
    $query_delete = "DELETE FROM kegiatan WHERE id_kegiatan = '$id_kegiatan'";
    $result_delete = $conn->query($query_delete);

    // Periksa apakah penghapusan berhasil
    if ($result_delete) {
        // Berhasil menghapus, kirim respon sukses
        $response = [
            'success' => true,
            'message' => 'Data berhasil dihapus.'
        ];
        echo json_encode($response);
    } else {
        // Gagal menghapus, kirim respon gagal
        $response = [
            'success' => false,
            'message' => 'Gagal menghapus data.'
        ];
        echo json_encode($response);
    }
} else {
    // Jika bukan request POST, kirim respon gagal
    $response = [
        'success' => false,
        'message' => 'Metode request tidak valid.'
    ];
    echo json_encode($response);
}

// Tutup koneksi ke database
$conn->close();
?>
