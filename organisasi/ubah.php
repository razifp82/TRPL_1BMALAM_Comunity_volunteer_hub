<?php
session_start();

include ".././koneksi.php";

// ...

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

// Periksa apakah formulir telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa apakah id_kegiatan sudah dikirimkan dari halaman sebelumnya
    if (isset($_GET['id_kegiatan'])) {
        $id_kegiatan = $_GET['id_kegiatan'];

        // Ambil nilai dari formulir
        $nama_kegiatan = $_POST['nama-kegiatan'];
        $jenis_kegiatan = $_POST['jenis-kegiatan'];
        $deskripsi_kegiatan = $_POST['deskripsi-kegiatan'];
        $tanggal_kegiatan = $_POST['tanggal-kegiatan'];
        $lokasi = $_POST['Lokasi'];

        // Handle file upload
        $file_name = $_FILES['dokumentasi']['name'];
        $file_tmp = $_FILES['dokumentasi']['tmp_name'];

        // Tempat penyimpanan file (di luar direktori web)
        $upload_directory = "upload/";
        $file_destination = $upload_directory . $file_name;

        // Create the upload directory if it doesn't exist
        if (!is_dir($upload_directory)) {
            mkdir($upload_directory, 0777, true);
        }

        // Pindahkan file ke lokasi yang diinginkan
        if (move_uploaded_file($file_tmp, $file_destination)) {
            // Query untuk memperbarui data kegiatan di database
            $update_query = "UPDATE kegiatan SET
                                nama_kegiatan = '$nama_kegiatan',
                                jenis_kegiatan = '$jenis_kegiatan',
                                deskripsi_kegiatan = '$deskripsi_kegiatan',
                                tanggal_kegiatan = '$tanggal_kegiatan',
                                lokasi = '$lokasi',
                                dokumentasi = '$file_name'
                            WHERE id_kegiatan = $id_kegiatan";

            // Eksekusi query
            if ($conn->query($update_query) === TRUE) {
                echo '<script>alert("Data kegiatan berhasil diperbarui."); window.location.href = "kegiatan.php";</script>';
                exit;
            } else {
                echo "Error updating record: " . $conn->error;
            }
        } else {
            echo "Gagal memindahkan file yang diunggah.";
        }
    } else {
        echo "ID Kegiatan tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuraga</title>
    <link rel="shortcut icon" href="/nuraga/images/logo/icon.mrh.png">
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>

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

    <body>
        <section id="login">
            <div class="login">
                <?php
                // Periksa apakah id_kegiatan sudah dikirimkan dari halaman sebelumnya
                if (isset($_GET['id_kegiatan'])) {
                    $id_kegiatan = $_GET['id_kegiatan'];

                    // Query untuk mengambil data kegiatan dari database berdasarkan id_kegiatan
                    $query = "SELECT * FROM kegiatan WHERE id_kegiatan = $id_kegiatan";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        $kegiatan = $result->fetch_assoc();
                ?>
                        <br>
                        <form method="POST" enctype="multipart/form-data" action="" class="putih">
                            <label style="color: white;" for="nama-kegiatan">Nama Kegiatan:</label>
                            <input type="text" id="nama-kegiatan" name="nama-kegiatan" value="<?php echo $kegiatan['nama_kegiatan']; ?>" required>

                            <label style="color: white;" for="jenis-kegiatan">Jenis Kegiatan:</label>

                            <div class="select_mate" data-mate-select="active">
                                <select name="jenis-kegiatan" id="jenis-kegiatan" onchange="" onclick="return false;" id="">
                                    <option disabled="disabled" selected="selected">Pilih Jenis Kegiatan</option>
                                    <option value="donasi">Donasi</option>
                                    <option value="kerja sosial">Kerja Sosial</option>
                                    <option value="penggalangan dana">Penggalangan Dana</option>
                                    <option value="bakti sosial">Bakti Sosial</option>
                                    <option value="relawan">Relawan</option>
                                </select>
                                <p class="selecionado_opcion" onclick="open_select(this)"></p><span onclick="open_select(this)" class="icon_select_mate"><svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.41 7.84L12 12.42l4.59-4.58L18 9.25l-6 6-6-6z" />
                                        <path d="M0-.75h24v24H0z" fill="none" />
                                    </svg></span>
                                <div class="cont_list_select_mate">
                                    <ul class="cont_select_int"> </ul>
                                </div>
                            </div>
                            <br><br><br>

                            <label style="color: white;" for="deskripsi-kegiatan">Deskripsi Kegiatan:</label>
                            <textarea id="deskripsi-kegiatan" name="deskripsi-kegiatan" required><?php echo $kegiatan['deskripsi_kegiatan']; ?></textarea>

                            <label style="color: white;" for="tanggal-kegiatan">Tanggal Kegiatan:</label>
                            <input type="date" id="tanggal-kegiatan" name="tanggal-kegiatan" value="<?php echo $kegiatan['tanggal_kegiatan']; ?>" required>

                            <label style="color: white;" for="Lokasi">Lokasi</label>
                            <input type="text" id="Lokasi" name="Lokasi" value="<?php echo $kegiatan['lokasi']; ?>" required>

                            <label style="color: white;" for="dokumentasi">Poster (file):</label>
                            <input type="file" id="dokumentasi" name="dokumentasi" required>

                            <input type="submit" value="Submit">
                        </form>
                <?php
                        // Bebaskan hasil query
                        $result->free_result();
                    } else {
                        echo "Error: " . $query . "<br>" . $conn->error;
                    }
                } else {
                    echo "ID Kegiatan tidak ditemukan.";
                }
                ?>
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