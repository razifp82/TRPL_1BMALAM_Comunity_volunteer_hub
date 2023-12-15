<?php
session_start();
include ".././koneksi.php";

if (isset($_POST['id_organisasi']) && isset($_POST['status'])) {
    $idOrganisasi = $_POST['id_organisasi'];
    $status = $_POST['status'];

    // Lakukan query untuk memperbarui status
    $query = "UPDATE organisasi SET status = '$status' WHERE id_organisasi = $idOrganisasi";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $response = array('success' => true);
        echo json_encode($response);
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
