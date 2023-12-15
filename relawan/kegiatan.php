<?php
session_start();

include ".././koneksi.php";

if (!isset($_SESSION["user"]) || !isset($_SESSION["userType"])) {
    header("location: /nuraga/login.php");
    exit;
}

if ($_SESSION["userType"] !== 'relawan') {
    // Redirect ke halaman yang sesuai dengan hak akses pengguna
    switch ($_SESSION["userType"]) {
        case 'admin':
            header("Location: /nuraga/admin/admin.php?username=" . $_SESSION["user"]);
            break;
        case 'organisasi':
            header("Location: /nuraga/organisasi/organisasi.php?username=" . $_SESSION["user"]);
            break;
        default:
            header("Location: /nuraga/login.php");
            break;
    }
    exit;
}


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuraga</title>
    <link rel="shortcut icon" href="/nuraga/images/logo/icon.mrh.png">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8H+0aNCIn1w4/4RM79XEOGQl47c4sDO/MEbqmbek5B+6EAg1PTXBRQDbh8Rw" crossorigin="anonymous"></script>
    <script>
        function completeActivity(id_kegiatan, action) {
            // Lakukan AJAX request untuk menyimpan atau menghapus data ke tabel daftar
            fetch('complete_activity.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'id_kegiatan=' + id_kegiatan + '&action=' + action,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Berhasil ' + (action === 'add' ? 'gabung ke' : 'keluar dari') + ' kegiatan!');
                        location.reload();
                    } else {
                        alert('Gagal ' + (action === 'add' ? 'gabung ke' : 'keluar dari') + ' kegiatan!');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

    <style>
        body {
            background-color: #f8f9fa;
            /* Light gray background color */
        }
    </style>

</head>

<body>
    <header class="header2">
        <a class="logo" href="relawan.php"><img src="/nuraga/images/logo/logo.pth.png" height="50px" alt="logo"></a>
        <nav>
            <ul class="nav__links">
                <li><a href="relawan.php">Beranda</a></li>
                <li><a href="relawan.php">Program</a></li>
                <li><a href="relawan.php">Tentang Kami</a></li>
                <li><a href="kegiatan.php">Cari kegiatan</a></li>

            </ul>
        </nav>
        <li class="mamak"><?php
                            include 'notif.php' ?></li>
        <li class="mamak"> <?php
                            include 'profil.php' ?></li>
    </header>
    <div class="container my-3">
        <br />
        <div class="row">

            <form method="GET">
                <div class="search-container3">
                    <input type="text" name="search" class="search-input3" placeholder="Search for items...">
                    <button type="submit" class="search-btn3">Cari</button>
                    <div class="dropdown3">
                        <button class="search-btn3">Jenis Kegiatan</button>
                        <div class="dropdown-content3">
                            <a href="?jenis_kegiatan=Donasi">Donasi</a>
                            <a href="?jenis_kegiatan=Kerja%20Sosial">Kerja Sosial</a>
                            <a href="?jenis_kegiatan=Penggalangan%20Dana">Penggalangan Dana</a>
                            <a href="?jenis_kegiatan=Bakti%20Sosial">Bakti Sosial</a>
                            <a href="?jenis_kegiatan=Relawan">Relawan</a>
                        </div>
                    </div>
                </div>


                <!-- <div class="input-group">
  <input type="text" class="form-control" aria-label="Text input with segmented dropdown button">
  <div class="input-group-append">
    <button type="button" class="btn btn-outline-secondary">Action</button>
    <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu">
      <a class="dropdown-item" href="#">Action</a>
      <a class="dropdown-item" href="#">Another action</a>
      <a class="dropdown-item" href="#">Something else here</a>
      <div role="separator" class="dropdown-divider"></div>
      <a class="dropdown-item" href="#">Separated link</a>
    </div>
  </div>
</div> -->
            </form>
        </div>
    </div>

    <div class="container my-3">
        <br>
        </br>
        <div class="row">
            <?php
            // Ambil nilai pencarian dari parameter URL
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $jenisKegiatan = isset($_GET['jenis_kegiatan']) ? $_GET['jenis_kegiatan'] : '';

            // Query untuk mengambil data kegiatan dari database dengan status 'belum' dan sesuai dengan kata kunci pencarian
            $query = "SELECT id_kegiatan, nama_kegiatan, jenis_kegiatan, organisasi, lokasi, tanggal_kegiatan, deskripsi_kegiatan, dokumentasi, status FROM kegiatan WHERE status = 'belum'";

            if (!empty($jenisKegiatan)) {
                $query .= " AND jenis_kegiatan = '$jenisKegiatan'";
            }

            // Tambahkan kondisi pencarian jika ada kata kunci pencarian
            if (!empty($search)) {
                $search = $conn->real_escape_string($search);
                $query .= " AND (nama_kegiatan LIKE '%$search%' OR organisasi LIKE '%$search%' OR lokasi LIKE '%$search%' OR tanggal_kegiatan LIKE '%$search%' OR deskripsi_kegiatan LIKE '%$search%')";
            }



            $result = $conn->query($query);

            // Periksa apakah query berhasil dijalankan
            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    $id_kegiatan = $row['id_kegiatan'];
                    $nama_kegiatan = $row['nama_kegiatan'];
                    $nama_organisasi = $row['organisasi'];
                    $lokasi = $row['lokasi'];
                    $tanggal_kegiatan = $row['tanggal_kegiatan'];
                    $deskripsi = $row['deskripsi_kegiatan'];
                    $dokumentasi = $row['dokumentasi'];
                    $status = $row['status'];
                    $jenis_kegiatan = $row['jenis_kegiatan'];

                    // Cek apakah pengguna sudah gabung ke kegiatan atau belum
                    $query_check = "SELECT * FROM daftar WHERE id_kegiatan = '$id_kegiatan' AND id_relawan = '{$_SESSION['id_relawan']}'";
                    $result_check = $conn->query($query_check);

                    $buttonText = $result_check && $result_check->num_rows > 0 ? "Keluar Kegiatan" : "Gabung Kegiatan";
                    $buttonAction = $result_check && $result_check->num_rows > 0 ? "remove" : "add";

                    echo '<div class="col-md-6 mb-4">
                    <div class="blog-card alt">
                        <div class="meta">
                            <div class="photo" style="background-image: url(/nuraga/organisasi/upload/' . $dokumentasi . ')"></div>
                            <ul class="details">
                                <li class="">' . $lokasi . '</li>
                                <li class="">' . $tanggal_kegiatan . '</li>
                              
                                <li class="tags"></li>
                            </ul>
                        </div>
                        <div class="description">
                            <h1>' . $nama_kegiatan . '</h1>
                            <h2>' . $jenis_kegiatan . '</h2>
                            <p>' . $deskripsi . '</p>
                            <p class="read-more">
                            <button class="button-62" onclick="completeActivity(' . $id_kegiatan . ', \'' . $buttonAction . '\')">' . $buttonText . '</button>
                               
                                
                            </p>
                        </div>
                    
                    </div>
                    </div>';
                }


                // Bebaskan hasil query
                $result->free_result();
            } else {
                // Tampilkan pesan jika query gagal
                echo "Error: " . $query . "<br>" . $conn->error;
            }

            // Tutup koneksi ke database
            $conn->close();
            ?>
            <style>
                body {
                    background: radial-gradient(#d61212, #332042);
                }
            </style>

            <div class="row">
                <div class="col-md-5">

                </div>
                <div class="col-md-5">

                </div>
                <div class="col-md-5">

                </div>
            </div>
        </div>
    </div>

    <script>
        function updateCardContent(element, property) {
            const content = element.innerText;
            const card = element.closest('.card');

            switch (property) {
                case 'title':
                    card.querySelector('.card-title').innerText = content;
                    break;
                case 'organisasi':
                    card.querySelector('.card-text i.bi-people-fill + span').innerText = content;
                    break;
                case 'location':
                    card.querySelector('.card-text i.bi-geo-alt-fill + span').innerText = content;
                    break;
                case 'date':
                    card.querySelector('.card-text i.bi-calendar-date-fill + span').innerText = content;
                    break;
                case 'imageAlt':
                    card.querySelector('.card-img').alt = content;
                    break;
            }
        }
    </script>

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
    <style>
        .card-text i.bi-geo-alt-fill,
        .card-text i.bi-calendar-date-fill {
            color: #e74c3c;
        }
    </style>
</body>

</html>