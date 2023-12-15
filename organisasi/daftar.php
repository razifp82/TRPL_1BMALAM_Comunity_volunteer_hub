<?php
session_start();

include ".././koneksi.php";

global $conn;

$conn = connectDB();

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

// Proses form jika ada pengiriman data melalui POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES['dokumentasi'])) {
    $nama_kegiatan = $_POST["nama-kegiatan"];
    $jenis_kegiatan = $_POST["jenis-kegiatan"];
    $deskripsi_kegiatan = $_POST["deskripsi-kegiatan"];
    $tanggal_kegiatan = $_POST["tanggal-kegiatan"];
    $lokasi = $_POST["Lokasi"];

    // Ambil nilai dari sesi nama_organisasi
    $nama_organisasi = $_SESSION['nama_organisasi'];
    $id_organisasi = $_SESSION['id_organisasi'];

    $nama_file = $_FILES['dokumentasi']['name'];
    $ukuran_file = $_FILES['dokumentasi']['size'];
    $tipe_file = $_FILES['dokumentasi']['type'];
    $tmp_file = $_FILES['dokumentasi']['tmp_name'];
    
    // Mendapatkan ekstensi file
    $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
    
    // Membuat nama file baru yang unik
    $new_file_name = "kegiatan_" . time() . "_" . rand(1000, 9999) . "." . $ext;
    
    // Simpan file ke direktori tertentu (misalnya: upload/)
    $upload_dir = "upload/";
    $upload_path = $upload_dir . $new_file_name;
    
    // Pindahkan file ke direktori upload
    if (move_uploaded_file($tmp_file, $upload_path)) {
        // Implementasikan penyimpanan data ke database, sesuaikan dengan struktur tabel Anda
        $query = "INSERT INTO kegiatan (nama_kegiatan, jenis_kegiatan, deskripsi_kegiatan, tanggal_kegiatan, lokasi, dokumentasi, organisasi ,id_organisasi) 
                  VALUES ('$nama_kegiatan', '$jenis_kegiatan', '$deskripsi_kegiatan', '$tanggal_kegiatan', '$lokasi', '$new_file_name', '$nama_organisasi','$id_organisasi')";
    
    if ($conn->query($query) === TRUE) {
        // Redirect to halaman organisasi.php after successfully saving
        echo '<script>alert("Kegiatan berhasil disimpan!"); window.location.href = "/nuraga/organisasi/organisasi.php?username=' . $_SESSION["user"] . '";</script>';
        exit;
    } else {
        // Hapus file yang sudah diupload jika gagal menyimpan ke database
        unlink($upload_path);
        // Tampilkan pesan error jika gagal menyimpan
        echo '<script>alert("Error: ' . $conn->error . '");</script>';
    }

    } else {
        echo "Error uploading file.";
    }
}
?>



<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuraga</title>
    <script src="script.js"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="/nuraga/images/logo/icon.mrh.png">
    <script>
        function showSuccessAlert() {
            alert("Kegiatan berhasil disimpan!");
        }
    </script>
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
                <li><a href="kegiatan.php">Kegiatan Saya</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <section id="login">
        <div class="login">
            <br>
            <form method="POST" enctype="multipart/form-data">
                <label style="color: white;" for="nama-kegiatan">Nama Kegiatan:</label>
                <input type="text" id="nama-kegiatan" name="nama-kegiatan" required>

                <label style="color: white;" for="jenis-kegiatan">Jenis Kegiatan:</label>
                <div class="select_mate" data-mate-select="active" >
                <select name="jenis-kegiatan" id="jenis-kegiatan" onchange="" onclick="return false;" id="">
                     <option disabled="disabled" selected="selected">Pilih Jenis Kegiatan</option>
                    <option value="donasi">Donasi</option>
                    <option value="kerja sosial">Kerja Sosial</option>
                    <option value="penggalangan dana">Penggalangan Dana</option>
                    <option value="bakti sosial">Bakti Sosial</option>
                    <option value="relawan">Relawan</option>
                </select>
                <p class="selecionado_opcion"  onclick="open_select(this)" ></p><span onclick="open_select(this)" class="icon_select_mate" ><svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z"/>
                    <path d="M0-.75h24v24H0z" fill="none"/>
                </svg></span>
                <div class="cont_list_select_mate">
                <ul class="cont_select_int">  </ul> 
                </div>
                </div>
                <br><br><br>

                <label style="color: white;" for="deskripsi-kegiatan">Deskripsi Kegiatan:</label>
                <textarea id="deskripsi-kegiatan" name="deskripsi-kegiatan" required></textarea>

                <label style="color: white;" for="tanggal-kegiatan">Tanggal Kegiatan:</label>
                <input type="date" id="tanggal-kegiatan" name="tanggal-kegiatan" required>

                <label style="color: white;" for="tanggal">Lokasi</label>
                <input type="text" id="Lokasi" name="Lokasi" required>

                <label style="color: white;" for="dokumentasi">Poster (file):</label>
                <input type="file" id="dokumentasi" name="dokumentasi" required>

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
