<?php

include 'koneksi.php';

$query = "SELECT kegiatan.nama_kegiatan, laporan_kegiatan.hasil_kegiatan, kegiatan.dokumentasi
          FROM kegiatan
          JOIN laporan_kegiatan ON kegiatan.id_kegiatan = laporan_kegiatan.id_kegiatan
          WHERE kegiatan.status = 'selesai'";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NURAGA</title>
    <link rel="icon" href="images/logo/icon.mrh.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
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
</head>

<body>
    <header>
        <a class="logo" href=""><img src="images/logo/logo.pth.png" height="50px" alt="logo"></a>
        <nav>
            <ul class="nav__links">
            <li><a href="#hero" class="scroll-link">Beranda</a></li>
        <li><a href="#programs" class="scroll-link">Program</a></li>
        <li><a href="#about" class="scroll-link">Tentang Kami</a></li>
            </ul>
        </nav>

        <a class="cta" href="login.php">Masuk</a>
        <input type="checkbox" id="modal" />
        <label for="modal" class="cta">Daftar
            <i class="fa fa-fire" aria-hidden="true"></i>
        </label>
        <label for="modal" class="modal-bg"></label>
        <div class="modal-content">
            <label for="modal" class="close">
                <i class="fa fa-times" aria-hidden="true"></i>
            </label>
            <div class="Group1" style="width: 607px; height: 461px; position: relative">
                <div class="Rectangle1" style="width: 607px; height: 461px; left: 0px; top: 0px; position: absolute; background: white; box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25); border-radius: 10px"></div>

                <a href="regis/organisasi/index.php" class="button success" style="width: 240px; height: 244px; left: 321.50px; top: 188px; position: absolute; background: #FF0000; border-radius: 10px">
                    <img class="ProfilOrganisasi1" style="width: 190px; height: 201px; position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%);" src="images/profil_organisasi.png" />
                </a>

                <a href="regis/relawan/index.php" style="width: 240px; height: 244px; left: 40px; top: 188px; position: absolute; background: #FF0000; border-radius: 10px">
                    <img class="ProfilRelawan1" style="width: 178px; height: 171px; position: absolute; top: 40%; left: 50%; transform: translate(-50%, -50%);" src="images/profil_relawan.png" />
                </a>

                <div class="Rectangle5" style="width: 178px; height: 27px; left: 72px; top: 393px; position: absolute; background: white; border-radius: 10px"></div>
                <div class="Rectangle6" style="width: 178px; height: 27px; left: 353px; top: 393px; position: absolute; background: white; border-radius: 10px"></div>
                <div class="Relawan" style="left: 95px; top: 387px; position: absolute; top: 83%; text-align: center; color: black; font-size: 32px; font-family: 'Inter', sans-serif; font-weight: 700; word-wrap: break-word">Relawan</div>
                <div class="Organisasi" style="width: 165px; height: 28px; left: 359px; top: 384px; position: absolute; text-align: center; color: black; font-size: 26px; font-family: 'Inter', sans-serif; font-weight: 700; word-wrap: break-word">Organisasi</div>

                <div class="Frame1" style="width: 522px; height: 129px; padding-top: 3px; padding-bottom: 5px; padding-left: 4px; padding-right: 3px; left: 40px; top: 27px; position: absolute; background: #FF0000; border-radius: 10px; overflow: hidden; justify-content: center; align-items: center; display: inline-flex">
                    <div class="BergabungDenganNuragaSebagaiOrganisasiAtauSebagaiRelawan" style="width: 515px; text-align: center; color: white; font-size: 28px; font-family: 'Inter', sans-serif; font-weight: 700; word-wrap: break-word; line-height: 1.2">Bergabung dengan Nuraga sebagai organisasi atau sebagai relawan</div>
                </div>


            </div>
        </div>


        </div>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js'></script>

        <p class="menu cta">Menu</p>
    </header>

    <section id="hero">
        <div class="hero-content">
            <h1 style="height: 30px;">NURAGA</h1>
            <p>Platform untuk berkontribusi dan membantu sesama </p>
            <br>
            <a href="login.php" class="btn">Gabung Sekarang</a>
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
            <img src="images/logo/logo.htm.png" alt="">
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