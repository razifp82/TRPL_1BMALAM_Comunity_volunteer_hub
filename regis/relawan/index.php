<?php
session_start();

include "../../koneksi.php"; // Mengubah path include

// Fungsi untuk memeriksa keunikkan username pada tabel tertentu
function usernameIsUnique($username, $tableName, $conn) {
    // Lakukan kueri untuk memeriksa keunikkan username di tabel
    $query = "SELECT COUNT(*) as count FROM $tableName WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    // Ambil jumlah baris yang memiliki username yang sama
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];

    // Jika count == 0, berarti username unik
    return $count == 0;


}

// Fungsi untuk memeriksa keunikkan email pada tabel tertentu
function emailIsUnique($email, $tableName, $conn) {
    // Lakukan kueri untuk memeriksa keunikkan email di tabel
    $query = "SELECT COUNT(*) as count FROM $tableName WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    // Ambil jumlah baris yang memiliki email yang sama
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];

    // Jika count == 0, berarti email unik
    return $count == 0;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST["nama"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    $username = $_POST["username"];
    $alamat = $_POST["alamat"];
    $tanggal_lahir = date("Y-m-d", strtotime($_POST["tanggal_lahir"]));

    // Validasi unik username dan email di tingkat PHP
    if (usernameIsUnique($username, 'admin', $conn) && usernameIsUnique($username, 'relawan', $conn) && usernameIsUnique($username, 'organisasi', $conn)
        && emailIsUnique($email, 'admin', $conn) && emailIsUnique($email, 'relawan', $conn) && emailIsUnique($email, 'organisasi', $conn)) {
        // Lanjutkan dengan proses pendaftaran
        // ...
    } else {
        echo '<script>alert("Username atau email sudah digunakan. Pilih yang lain.");</script>';
        exit();  // Hentikan proses pendaftaran jika username atau email sudah digunakan
    }

    if (empty($nama) || empty($email) || empty($password) || empty($username) || empty($alamat) || empty($tanggal_lahir)) {
        echo '<script>alert("Harap isi semua bidang.");</script>';
    } else {
        // Validasi ketersediaan jenis_kelamin
        if(isset($_POST["jenis_kelamin"])) {
            $jenis_kelamin = $_POST["jenis_kelamin"];
        } else {
            echo '<script>alert("Harap pilih jenis kelamin.");</script>';
            // Jika jenis kelamin tidak ada, hentikan proses pendaftaran
            exit();
        }

        // Melakukan escape string untuk menghindari SQL Injection
        $nama = $conn->real_escape_string($nama);
        $email = $conn->real_escape_string($email);
        $password = $conn->real_escape_string($password);
        $jenis_kelamin = $conn->real_escape_string($jenis_kelamin);
        $username = $conn->real_escape_string($username);
        $alamat = $conn->real_escape_string($alamat);
        $tanggal_lahir = $conn->real_escape_string($tanggal_lahir);

        // Hashing password sebelum disimpan ke database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Menyiapkan query untuk menyimpan data ke dalam tabel relawan
        $sql = "INSERT INTO relawan (nama, email, password, jenis_kelamin, username, alamat, tanggal_lahir) VALUES ('$nama', '$email', '$hashed_password', '$jenis_kelamin', '$username', '$alamat', '$tanggal_lahir')";

        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Pendaftaran berhasil! Selamat datang, ' . $nama . '!");</script>';
            header("Location: /nuraga/index.php");
        } else {
            echo '<script>alert("Error: ' . $sql . '\n' . $conn->error . '");</script>';
        }

        // Menutup koneksi ke database
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">
    <link rel="shortcut icon" href="/nuraga/images/logo/icon.mrh.png">


    <!-- Title Page-->
    <title>Nuraga</title>

    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/main.css" rel="stylesheet" media="all">
    <style>
         .checkbox-container {
            margin-top: 10px;
            display: flex;
            align-items: center;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            margin-right: 10px; /* Add margin to the right for spacing */
        }

        .checkbox-label button {
            background: none;
            border: none;
            color: #007BFF;
            cursor: pointer;
            text-decoration: underline;
            margin-left: 5px;
        }

        .checkbox-label button:hover {
            color: #0056b3;
        }

        .checkbox-label button:focus {
            outline: none;
        }

        #termsCheckbox {
            margin-right: 5px;
        }

        .modal-container {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal {
            background: #fff;
            padding: 20px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            position: relative;
            border-radius: 8px;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="page-wrapper bg-red p-t-180 p-b-100 font-robo">
        <div class="wrapper wrapper--w960">
            <div class="card card-2">
                <?php
                if (isset($_SESSION["user"], $_SESSION["userType"])) {
                    switch ($_SESSION["userType"]) {
                        case 'admin':
                            header("Location: /nuraga/admin/admin.php?username=" . $_SESSION["user"]);
                            break;
                        case 'relawan':
                            header("Location: /nuraga/relawan/relawan.php?username=" . $_SESSION["user"]);
                            break;
                        case 'organisasi':
                            header("Location: /nuraga/organisasi/organisasi.php?username=" . $_SESSION["user"]);
                            break;
                        default:
                            break;
                    }
                    exit();
                }
                ?>
                <div class="card-heading"></div>
                <div class="card-body">
                    <div class="top_link"><a href="\nuraga/index.php"><img src="https://drive.google.com/u/0/uc?id=16U__U5dJdaTfNGobB_OpwAJ73vM50rPV&export=download" alt="">Kembali ke halaman utama</a></div>
                    <br><br>
                    <h2 class="title">Pendaftaran relawan</h2>
                    <form method="POST">
                        <div class="input-group">
                            <input class="input--style-2" type="text" placeholder="Nama" name="nama">
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <input class="input--style-2 js-datepicker" type="text" placeholder="Tanggal Lahir" name="tanggal_lahir">
                                    <i class="zmdi zmdi-calendar-note input-icon js-btn-calendar"></i>
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="input-group">
                                    <div class="rs-select2 js-select-simple select--no-search">
                                        <select name="jenis_kelamin">
                                            <option disabled="disabled" selected="selected">Jenis Kelamin</option>
                                            <option>Laki-Laki</option>
                                            <option>Perempuan</option>
                                        </select>
                                        <div class="select-dropdown"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <input class="input--style-2" type="text" placeholder="Email" name="email">
                        </div>
                        <div class="input-group">
                            <input class="input--style-2" type="text" placeholder="Alamat" name="alamat">
                        </div>
                        <div class="input-group">
                            <input class="input--style-2" type="text" placeholder="Username" name="username">
                        </div>
                        <div class="input-group">
                            <input class="input--style-2" type="password" placeholder="Password" name="password">
                        </div>
                        <!-- Checkbox for terms and conditions -->
                        
                        <div class="checkbox-container">
        <label for="termsCheckbox" class="checkbox-label">
            <input type="checkbox" id="termsCheckbox" name="termsCheckbox">
            <button type="button" onclick="openModal()">Syarat dan Ketentuan</button>
        </label>
        <div class="modal-container" id="modalContainer">
            <div class="modal">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h2>Syarat dan Ketentuan</h2>
                <br><br>
<p>
    <strong>Pendaftaran dan Akun Pengguna</strong><br>
    <br><em>Persetujuan:</em><p>
    Dengan mendaftar di situs kami, Anda setuju untuk mematuhi syarat dan ketentuan yang tercantum di sini.</p>
</p><br>
<p>
    <em>Informasi Pribadi:</em><p>
    Anda bertanggung jawab atas keakuratan informasi pribadi yang Anda berikan selama proses pendaftaran.</p>
</p><br>
<p>
    <em>Keamanan Akun:</em><p>
    Anda bertanggung jawab untuk menjaga keamanan dan kerahasiaan kata sandi akun Anda. Segala aktivitas 
    yang terjadi di bawah akun Anda menjadi tanggung jawab Anda.</p>
</p><br>
<p>
    <strong>Kewajiban Pengguna</strong><br>
    <br><em>Penggunaan yang Dilarang:</em><p>
    Anda setuju untuk tidak menggunakan situs ini untuk tujuan yang ilegal atau melanggar hak privasi dan 
    keamanan orang lain.</p>
</p><br>
<p>
    <em>Hak Cipta:</em><p>
    Materi dan konten di situs ini dilindungi oleh hak cipta. Penggunaan tanpa izin dapat mengakibatkan 
    tindakan hukum.</p>
</p><br>
<p>
    <strong>Kebijakan Privasi</strong><br>
    <br><em>Pengumpulan Informasi:</em><p>
    Kami mengumpulkan informasi tertentu saat Anda menggunakan situs kami. Detail lebih lanjut dapat 
    ditemukan dalam Kebijakan Privasi kami.</p>
</p><br>
<p>
    <em>Pemberitahuan Perubahan:</em><p>
    Kami berhak untuk mengubah syarat dan ketentuan ini. Perubahan tersebut akan diberitahukan kepada 
    pengguna melalui email atau pemberitahuan di situs.</p>
</p><br>
<p>
    <strong>Pemutusan Hubungan</strong>
    <br><em>Pemutusan oleh Pengguna:</em><p>
    Anda dapat memutuskan akun Anda kapan saja dengan memberikan pemberitahuan kepada kami.</p>
</p><br>
<p>
    <em>Pemutusan oleh Pihak Kami:</em><p>
    Kami berhak untuk memutuskan hubungan dengan Anda jika kami curiga bahwa Anda melanggar syarat dan 
    ketentuan ini.</p>
</p><br>
<p>
    <strong>Lain-lain</strong><br>
    <br><em>Hak dan Tanggung Jawab:</em><p>
    Anda setuju bahwa penggunaan situs ini sepenuhnya risiko Anda sendiri. Kami tidak bertanggung jawab 
    atas kerugian atau kerusakan yang mungkin terjadi akibat penggunaan situs ini.</p>
</p><br>
<p>
    <em>Hukum yang Berlaku:</em><p>
    Syarat dan ketentuan ini tunduk pada hukum yang berlaku di wilayah yurisdiksi kami.</p>
</p><br>
<p>
    Dengan mendaftar dan menggunakan situs kami, Anda menyatakan bahwa Anda telah membaca, memahami, 
    dan menyetujui syarat dan ketentuan ini. Jika Anda tidak setuju dengan syarat dan ketentuan ini, harap untuk tidak menggunakan situs ini.
</p>

</div>
        </div>
    </div>

                        

                       
<div class="p-t-30">
    <button class="btn btn--radius btn--green" type="button" onclick="validateForm()">Daftar</button>
</div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>

    <script>
          function openModal() {
        document.getElementById('modalContainer').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('modalContainer').style.display = 'none';
    }

    function validateForm() {
        // Check if the checkbox is selected
        var checkbox = document.getElementById('termsCheckbox');
        if (checkbox.checked) {
            // If selected, submit the form
            document.querySelector('form').submit();
        } else {
            // If not selected, show an alert
            alert("Anda harus menyetujui Syarat dan Ketentuan untuk mendaftar.");
        }
    }
    </script>


</body>

</html>
