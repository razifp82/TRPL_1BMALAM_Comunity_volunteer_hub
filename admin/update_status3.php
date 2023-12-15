<?php
session_start();

include ".././koneksi.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_relawan = $_POST["id_relawan"];
    $action = $_POST["action"];

    // Lakukan query untuk mengubah nilai kolom status
    $query = "UPDATE relawan SET status = 'none' WHERE id_relawan = '$id_relawan'";

    if ($conn->query($query) === TRUE) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid request"]);
}

$conn->close();
?>
