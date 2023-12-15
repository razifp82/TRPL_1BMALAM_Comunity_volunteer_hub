<?php
include ".././koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_kegiatan"])) {
    $id_kegiatan = $_POST["id_kegiatan"];

    // Update status kegiatan menjadi 'selesai'
    $updateQuery = "UPDATE kegiatan SET status = 'selesai' WHERE id_kegiatan = '$id_kegiatan'";
    $updateResult = $conn->query($updateQuery);

    if ($updateResult) {
        // Jika berhasil, arahkan pengguna ke laporan.php
        header("Location: /nuraga/organisasi/laporan.php?id_kegiatan=" . $id_kegiatan);
        exit;
    } else {
        echo "error";
    }
} else {
    echo "Invalid request";
}
?>
