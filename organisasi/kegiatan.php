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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuraga</title>
    <link rel="shortcut icon" href="/nuraga/images/logo/icon.mrh.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8H+0aNCIn1w4/4RM79XEOGQl47c4sDO/MEbqmbek5B+6EAg1PTXBRQDbh8Rw" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".scroll-link").on('click', function(event) {
                if (this.hash !== "") {
                    event.preventDefault();

                    var hash = this.hash;

                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 800, function(){
                        window.location.hash = hash;
                    });
                }
            });
        });
    </script>  
    <style>
        body {
            background-color: #f8f9fa; /* Light gray background color */
        }
    </style>

</head>
<body>
<header>
        <a class="logo" href="organisasi.php"><img src="/nuraga/images/logo/logo.pth.png" height="50px" alt="logo"></a>
            <nav>
                <ul class="nav__links">
                <li><a href="#hero" class="scroll-link">Beranda</a></li>
        <li><a href="#programs" class="scroll-link">Program</a></li>
        <li><a href="#about" class="scroll-link">Tentang Kami</a></li>
                    <li><a href="daftar.php">Buat Kegiatan</a></li>
                    <li><a href="kegiatan.php">Kegiatan saya</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
	
    </header>
    <div class="container my-3">
   

<br>

</br>

<div class="row">
<?php
$nama_organisasi = $_SESSION["nama_organisasi"];
$query = "SELECT id_kegiatan, nama_kegiatan, lokasi, tanggal_kegiatan, deskripsi_kegiatan, jenis_kegiatan, dokumentasi, status 
          FROM kegiatan 
          WHERE organisasi = '$nama_organisasi'";

$result = $conn->query($query);

// Periksa apakah query berhasil dijalankan
while ($row = $result->fetch_assoc()) {
    $id_kegiatan = $row['id_kegiatan'];
    $nama_kegiatan = htmlspecialchars($row['nama_kegiatan']);
    $lokasi = htmlspecialchars($row['lokasi']);
    $tanggal_kegiatan = htmlspecialchars($row['tanggal_kegiatan']);
    $deskripsi = htmlspecialchars($row['deskripsi_kegiatan']);
    $dokumentasi = htmlspecialchars($row['dokumentasi']);
    $status = htmlspecialchars($row['status']);
    $jenis_kegiatan = htmlspecialchars($row['jenis_kegiatan']);

    // Query untuk menghitung jumlah relawan yang mendaftar untuk kegiatan ini
    $queryDaftar = "SELECT COUNT(*) AS jumlah_relawan FROM daftar WHERE id_kegiatan = $id_kegiatan";
    $resultDaftar = $conn->query($queryDaftar);

    // Periksa apakah query berhasil dijalankan
    if ($resultDaftar) {
        // Ambil hasil query
        $rowDaftar = $resultDaftar->fetch_assoc();
        $jumlah_relawan = $rowDaftar['jumlah_relawan'];

        // Tampilkan konten HTML dengan jumlah relawan yang mendaftar
        if ($status == 'belum') {
            // Tampilkan card untuk kegiatan yang belum selesai
            echo '
            <div class="col-md-6 mb-4">
            <div class="blog-card alt">
                <div class="meta">
                    <div class="photo" style="background-image: url(/nuraga/organisasi/upload/' . $dokumentasi . ')"></div>
                    <ul class="details">
                        <li class="">' . $lokasi . '</li>
                        <li class="">' . $tanggal_kegiatan . '</li>
                        <li>Jumlah Relawan: ' . $jumlah_relawan . '</li>
                        <li class="tags"></li>
                    </ul>
                </div>
                <div class="description">
                    <h1>' . $nama_kegiatan . '</h1>
                    <h2>' . $jenis_kegiatan . '</h2>
                    <p>' . $deskripsi . '</p>
                    <p class="read-more">
                        <button class="button-62" onclick="completeActivity(' . $id_kegiatan . ')">Selesai</button>
                        <button class="button-62" onclick="editCard(' . $id_kegiatan . ')"><i class="bi bi-pencil"></i></button>
                        <button class="button-62" onclick="deleteActivity(' . $id_kegiatan . ')"><i class="bi bi-trash"></i></button>
                    </p>
                </div>
            </div>
            </div>';
        } elseif ($status == 'selesai') {
            // Tampilkan card untuk kegiatan yang sudah selesai
            echo '
            <div class="col-md-6 mb-4">
            <div class="blog-card alt">
                <div class="meta">
                    <div class="photo" style="background-image: url(/nuraga/organisasi/upload/' . $dokumentasi . ')"></div>
                    <ul class="details">
                        <li class="">' . $lokasi . '</li>
                        <li class="">' . $tanggal_kegiatan . '</li>
                        <li>Jumlah Relawan: ' . $jumlah_relawan . '</li>
                        <li class="tags"></li>
                    </ul>
                </div>
                <div class="description">
                    <h1>' . $nama_kegiatan . '</h1>
                    <h2>' . $jenis_kegiatan . '</h2>
                    <p>' . $deskripsi . '</p>
                    <p class="read-more">
                        <button class="button-62" onclick="updateActivity(' . $id_kegiatan . ')">Ubah Laporan</button>
                        <button class="button-62" onclick="deleteActivity(' . $id_kegiatan . ')"><i class="bi bi-trash"></i></button>
                    </p>
                </div>
            </div>
            </div>';
        }

        // Bebaskan hasil query daftar
        $resultDaftar->free_result();
    } else {
        // Tampilkan pesan jika query daftar gagal
        echo "Error: " . $queryDaftar . "<br>" . $conn->error;
    }
}

// Tutup koneksi ke database
$conn->close();
?>


</br>
</div>
        <style>
    body {
        background: radial-gradient(#d61212, #332042);
        background-size: cover; /* Adjust the size to cover the entire background */
        background-position: center; /* Center the background image */
        background-repeat: no-repeat; /* Prevent the background image from repeating */
    }
</style>
    <!-- Add your content here, such as cards or a list of activities -->
    
</div>
<!-- Add this script to your page -->
<script>
    function updateCardContent(element, property) {
        // Retrieve the edited content and update the corresponding property
        const content = element.innerText;
        const card = element.closest('.card');

        switch (property) {
            case 'title':
                card.querySelector('.card-title').innerText = content;
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
            // Add more cases for other editable content
        }
    }

    function completeActivity(id_kegiatan) {
    // Menggunakan AJAX untuk mengirim permintaan ke server
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'complete_activity.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Handle response
            console.log('Activity marked as completed.');

            // Redirect ke laporan.php atau halaman berikutnya
            window.location.href = 'laporan.php?id_kegiatan=' + id_kegiatan;
        } else {
            console.error('Failed to complete activity.');
        }
    };

    // Perlu menambahkan ini untuk mengirim data dengan metode POST
    xhr.send('id_kegiatan=' + id_kegiatan);
}


function updateActivity(id_kegiatan) {
    // Menggunakan AJAX untuk mengirim permintaan ke server
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_activity.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Handle response
            console.log('Activity marked as completed.');

            // Redirect ke laporan.php atau halaman berikutnya
            window.location.href = 'editlaporan.php?id_kegiatan=' + id_kegiatan;
        } else {
            console.error('Failed to complete activity.');
        }
    };

    // Perlu menambahkan ini untuk mengirim data dengan metode POST
    xhr.send('id_kegiatan=' + id_kegiatan);
}


function editCard(id_kegiatan) {
        // Redirect ke halaman ubah.php dengan parameter id_kegiatan
        window.location.href = 'ubah.php?id_kegiatan=' + id_kegiatan;
    }

function deleteActivity(id_kegiatan) {
    // Menggunakan AJAX untuk mengirim permintaan ke server
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete_activity.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            // Handle response
            console.log('Activity deleted.');

            // Tampilkan alert
            alert('Data berhasil dihapus.');

            // Refresh halaman setelah menampilkan alert
            location.reload();
        } else {
            console.error('Failed to delete activity.');
        }
    };
    xhr.send('id_kegiatan=' + id_kegiatan);
}

</script>

<!-- Add a footer section if needed -->
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
    