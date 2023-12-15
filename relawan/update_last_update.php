<?php
session_start();

include "../koneksi.php";

if (isset($_POST['id_kegiatan'])) {
    $id_kegiatan = $_POST['id_kegiatan'];
    $id_relawan = $_SESSION['id_relawan'];
    $date = date('Y-m-d H:i:s');

    // Ambil lastUpdate dari kegiatan terkait
    $query_kegiatan = "SELECT lastUpdate FROM kegiatan WHERE id_kegiatan = $id_kegiatan";
    $result_kegiatan = mysqli_query($conn, $query_kegiatan);

    if ($result_kegiatan) {
        $row_kegiatan = mysqli_fetch_assoc($result_kegiatan);
        $lastUpdate_kegiatan = $row_kegiatan['lastUpdate'];

        // Perbarui lastUpdated di tabel daftar dengan lastUpdate kegiatan
        $query = "UPDATE daftar SET lastUpdated = '$lastUpdate_kegiatan' WHERE id_kegiatan = $id_kegiatan AND id_relawan = $id_relawan";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $response = array('success' => true, 'lastUpdate' => $lastUpdate_kegiatan);
            echo json_encode($response);
        } else {
            $response = array('success' => false, 'error' => mysqli_error($conn));
            echo json_encode($response);
        }
    } else {
        $response = array('success' => false, 'error' => mysqli_error($conn));
        echo json_encode($response);
    }
} else {
    // Permintaan tidak valid
    http_response_code(400);
    echo json_encode(array('success' => false, 'error' => 'Permintaan tidak valid'));
}
?>
