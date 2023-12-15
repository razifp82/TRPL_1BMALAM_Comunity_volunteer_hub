<?php
session_start();

include ".././koneksi.php";

$query = "SELECT kegiatan.nama_kegiatan, laporan_kegiatan.hasil_kegiatan, kegiatan.dokumentasi
          FROM kegiatan
          JOIN laporan_kegiatan ON kegiatan.id_kegiatan = laporan_kegiatan.id_kegiatan
          WHERE kegiatan.status = 'selesai'";
$result = $conn->query($query);


if (!isset($_SESSION["user"]) || !isset($_SESSION["userType"])) {
    header("location: /nuraga/login.php");
    exit;
}

if ($_SESSION["userType"] !== 'relawan') {
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <title>Nuraga</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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
    <link rel="shortcut icon" href="/nuraga/images/logo/icon.mrh.png">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <header>
        <a class="logo" href="/"><img src="/nuraga/images/logo/logo.pth.png" height="50px" alt="logo"></a>
        <nav>
            <ul class="nav__links">
            <li><a href="#hero" class="scroll-link">Beranda</a></li>
        <li><a href="#programs" class="scroll-link">Program</a></li>
        <li><a href="#about" class="scroll-link">Tentang Kami</a></li>
                <li><a href="kegiatan.php">Cari Kegiatan</a></li>

            </ul>
        </nav>
        <li class="mamak"><?php
                            include 'notif.php' ?></li>
        <li class="mamak"><?php
                            include 'profil.php' ?></li>
    </header>

    <section id="hero">
        <div class="hero-content">
            <h2 style="height: 30px;">Hai, <?php echo ucfirst($_SESSION['nama']); ?>!</h2>
            <p style="font-size: 20px;">Senang melihat Anda kembali! Kami sangat menghargai kontribusi Anda sebagai relawan.</p>
            <p style="font-size: 20px;">Mari bersama-sama menjadikan dunia ini tempat yang lebih baik.</p>


        </div>
    </section>

    <section id="programs">
    <div class="program-content">
        <section class="articles">
            <?php
            while ($row = $result->fetch_assoc()) {
                $nama_kegiatan = $row['nama_kegiatan'];
                $hasil_kegiatan = $row['hasil_kegiatan'];
                $dokumentasi = $row['dokumentasi'];
                echo
                '<article>
                    <div class="article-wrapper">
                        <figure>
                            <img src="/nuraga/organisasi/upload/' .$dokumentasi . '" alt="" />
                        </figure>
                        <div class="article-body">
                            <h2>' .$nama_kegiatan. '</h2>
                            <p>
                                ' .$hasil_kegiatan. '
                            </p>
                            <a href="#" class="read-more">
                                <span class="sr-only">tentang ' . $nama_kegiatan . '</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>';
            
        }
            ?>
        </section>
    </div>
</section>
    <section id="about">
        <div class="about-content">
            <img src="\nuraga/images/logo/logo.htm.png" alt="">
            <br>
            <h2 align="center">Nuraga adalah website online yang mempertemukan organisasi sosial yang membutuhkan relawan dengan siapapun yang ingin menjadi relawan, dan juga memudahkan para relawan mencari suatu kegiatan sosial. Mimpi besar kami adalah melihat kegiatan menjadi relawan sebagai gaya hidup. Di mana relawan bukan lagi kegiatan sosial yang eksklusif tapi sudah menjadi kebiasaan masyarakat Indonesia. Misi utama kami yakni ingin membuat kolaborasi antara relawan dengan organisasi/komunitas dengan misi sosial menjadi lebih mudah melalui platform nuraga</h2>
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