<?php
include ".././koneksi.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["id_kegiatan"])) {
    $id_kegiatan = $_POST["id_kegiatan"];

    // Hapus kegiatan dari tabel
    $deleteQuery = "DELETE FROM kegiatan WHERE id_kegiatan = '$id_kegiatan'";
    $deleteResult = $conn->query($deleteQuery);

    if ($deleteResult) {
        echo "success";
    } else {
        echo "error";
    }
}
?>
