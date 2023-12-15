<?php
session_start();

include ".././koneksi.php";

if (!isset($_SESSION["user"]) || !isset($_SESSION["userType"])) {
    header("location: /nuraga/login.php");
    exit;
}

if ($_SESSION["userType"] !== 'organisasi') {
    switch ($_SESSION["userType"]) {
        case 'admin':
            header("Location: /nuraga/admin/admin.php?username=" . $_SESSION["user"]);
            break;
        case 'relawan':
            header("Location: /nuraga/relawan/relawan.php?username=" . $_SESSION["user"]);
            break;
        default:
            header("Location: /nuraga/login.php");
            break;
    }
    exit;
}

// Di dalam blok yang menangani POST request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil id_kegiatan dan hasil_kegiatan dari formulir
    $id_kegiatan = $_POST["id_kegiatan"];
    $hasil_kegiatan = $_POST["deskripsi-kegiatan"]; // Mengubah ini sesuai dengan nama elemen formulir

    // Periksa apakah id_kegiatan dan hasil_kegiatan tidak kosong
    if (!empty($id_kegiatan) && !empty($hasil_kegiatan)) {
        // Perbarui laporan_kegiatan di database
        $updateQuery = "UPDATE laporan_kegiatan SET hasil_kegiatan = '$hasil_kegiatan' WHERE id_kegiatan = '$id_kegiatan'";
        $updateResult = $conn->query($updateQuery);

        if ($updateResult) {
            // Jika berhasil, arahkan pengguna ke halaman organisasi.php atau kegiatan.php
            header("Location: /nuraga/organisasi/organisasi.php?success=true");
            exit;
        } else {
            echo "Error: " . $updateQuery . "<br>" . $conn->error;
        }
    } else {
        // Tambahkan logika penanganan jika id_kegiatan atau hasil_kegiatan kosong
        echo "Error: id_kegiatan or hasil_kegiatan is empty.";
    }
}
?>

<!-- ... (HTML code) ... -->


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuraga</title>
    <link rel="shortcut icon" href="/nuraga/images/logo/icon.mrh.png">

    <link rel="stylesheet" href="style.css">
    <?php
    // Cek apakah parameter 'success' ada pada URL
    if (isset($_GET['success'])) {
        // Jika 'success' bernilai true, tampilkan alert
        if ($_GET['success'] === 'true') {
            echo 'alert("Laporan kegiatan berhasil dikirim!");';
        }
    }
    ?>
</head>

<body>
    <header>
        <a class="logo" href="/"><img src="/nuraga/images/logo/logo.pth.png" height="50px" alt="logo"></a>
        <nav>
            <ul class="nav__links">
                <li><a href="organisasi.php">Beranda</a></li>
                <li><a href="organisasi.php">Program</a></li>
                <li><a href="organisasi.php">Tentang Kami</a></li>
                <li><a href="daftar.php">Buat Kegiatan</a></li>
                <li><a href="kegiatan.php">kegiatan saya</a></li>
                <li><a href="logout.php">logout</a></li>
            </ul>
        </nav>

    </header>

    
        <section id="login">
            <div class="laporan">
                <br>
                <form method="post" action="">
                    <!-- Tambahkan input hidden untuk menyimpan id_kegiatan -->
                    <input type="hidden" name="id_kegiatan" value="<?php echo isset($_GET['id_kegiatan']) ? $_GET['id_kegiatan'] : ''; ?>">

                    <h3 align="center" style="text">LAPORAN KEGIATAN</h3><br>
                    <textarea id="deskripsi-kegiatan" name="deskripsi-kegiatan" style="padding: 100px;"></textarea>
                    <input type="submit" value="Submit">
                </form>
                <br>
            </div>
        </section>

        <section id="contact">
            <div class="contact-content">
                <h2>Kontak</h2>
                <p>Email: nuraga@gmail.com</p>
                <p>Telepon: 123-456-7890</p>
            </div>
        </section>

        <footer>
            <p>Hak Cipta &copy; 2023 NURAGA</p>
        </footer>

    </body>

</html>