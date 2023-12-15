<?php
include ".././koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_kegiatan"])) {
    $id_kegiatan = $_POST["id_kegiatan"];

    // Arahkan pengguna ke laporan.php dengan menyertakan id_kegiatan
    header("Location: /nuraga/organisasi/editlaporan.php?id_kegiatan=" . $id_kegiatan);
    exit;
} else {
    echo "Invalid request";
}
?>
