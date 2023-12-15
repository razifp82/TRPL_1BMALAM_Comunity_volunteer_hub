<?php
session_start();

include 'koneksi.php';
// Fungsi untuk melakukan login
function login($username, $password)
{
    $conn = connectDB();

    // Hindari SQL Injection
    $username = $conn->real_escape_string($username);

    // Tentukan tabel login berdasarkan username
    $tables = ['admin', 'relawan', 'organisasi'];

    foreach ($tables as $table) {
        $query = "SELECT * FROM $table WHERE username='$username'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            // Ambil data dari hasil query
            $row = $result->fetch_assoc();

            // Verifikasi status akun untuk organisasi
            if ($table === 'organisasi' && $row['status'] === 'none') {
                // Tampilkan pesan alert jika verifikasi akun belum disetujui oleh admin
                echo "<script>alert('Verifikasi akun anda belum di setujui oleh admin. Silahkan tunggu beberapa saat.'); window.location.href = 'index.php';</script>";
                exit();
            } elseif ($table === 'organisasi' && $row['status'] === 'no') {
                // Tampilkan pesan alert jika verifikasi akun ditolak oleh admin
                echo "<script>alert('Verifikasi akun anda ditolak oleh admin. Silahkan hubungi admin untuk informasi lebih lanjut melalui email yang tesedia.'); window.location.href = 'index.php';</script>";
                exit();
            }
            if ($table === 'relawan' && $row['status'] === 'ban') {
                // Tampilkan pesan alert jika akun relawan dibanned
                echo "<script>alert('Akun relawan anda dibanned karena melanggar peraturan yang ada di website. Silahkan hubungi admin untuk informasi lebih lanjut melalui email yang sudah tersedia.'); window.location.href = 'index.php';</script>";
                exit();
            }

            // Verify the entered password against the stored hashed password
            if (password_verify($password, $row['password'])) {
                // Login berhasil
                $_SESSION['user'] = $username;
                $_SESSION['userType'] = $table;

                // Set cookies
                setcookie('username', $username, time() + (86400 * 30), "/"); // 86400 detik = 1 hari
                setcookie('userType', $table, time() + (86400 * 30), "/");

                switch ($table) {
                    case 'admin':
                        $_SESSION['nama'] = $row['nama'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['no_telp'] = $row['no_telp'];
                        header("Location: admin/admin.php");
                        break;
                    case 'relawan':
                        $_SESSION['id_relawan'] = $row['id_relawan'];
                        $_SESSION['nama'] = $row['nama'];
                        $_SESSION['tanggal_lahir'] = $row['tanggal_lahir'];
                        $_SESSION['alamat'] = $row['alamat'];
                        $_SESSION['email'] = $row['email'];
                        $_SESSION['jenis_kelamin'] = $row['jenis_kelamin'];
                        header("Location: relawan/relawan.php");
                        break;
                    case 'organisasi':
                        $_SESSION['id_organisasi'] = $row['id_organisasi'];
                        $_SESSION['nama_organisasi'] = $row['nama_organisasi'];
                        $_SESSION['sosial_media '] = $row['sosial_media'];
                        $_SESSION['deskripsi_organisasi'] = $row['deskripsi_organisasi'];
                        $_SESSION['email_organisasi'] = $row['email_organisasi'];
                        header("Location: organisasi/organisasi.php");
                        break;
                }
                exit();
            } else {
                // Password tidak cocok, login gagal
                echo "<script>alert('Login gagal. Periksa username dan password Anda.'); window.location.href = 'login.php';</script>'";
                exit();
            }
        }
    }

    // Jika tidak ada kesesuaian, login gagal
    echo "<script>alert('Login gagal. Periksa username dan password Anda.'); window.location.href = 'login.php';</script>'";

    $conn->close();
}

// Proses formulir login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Panggil fungsi login
    login($username, $password);
}

?>





<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NURAGA</title>
    <link rel="icon" href="images/logo/icon.mrh.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <section class="login">
        <div class="login_box">
            <?php

            if (isset($_SESSION["user"], $_SESSION["userType"])) {

                switch ($_SESSION["userType"]) {
                    case 'admin':
                        header("Location: admin/admin.php?username=" . $_SESSION["user"]);
                        break;
                    case 'relawan':
                        header("Location: relawan/relawan.php?username=" . $_SESSION["user"]);
                        break;
                    case 'organisasi':
                        header("Location: organisasi/organisasi.php?username=" . $_SESSION["user"]);
                        break;
                    default:

                        break;
                }
                exit();
            }

            ?>
            <div class="left">
                <div class="top_link"><a href="index.php"><img src="https://drive.google.com/u/0/uc?id=16U__U5dJdaTfNGobB_OpwAJ73vM50rPV&export=download" alt="">Kembali ke halaman utama</a></div>
                <div class="contact">
                    <form action="" method="post">
                        <h3>MASUK</h3>
                        <input type="text" value="<?php echo isset($username) ? $username : ''; ?>" name="username" class="input" placeholder="USERNAME">
                        <input type="password" name="password" class="input" placeholder="PASSWORD">
                        <br><br>
                        <p>Belum punya akun? <a href="regis/relawan/index.php   ">Daftar Sekarang</a></p>
                        <button type="submit" value="login" class="submit">LET'S GO</button>

                    </form>
                </div>
            </div>
            <div class="right">
                <div class="right-text">
                    <h2><img src="images/logo/logo.pth.png" alt=""></h2>
                    <h5>PEDULI BERBAGI BERAKSI</h5>
                </div>
            </div>
        </div>
    </section>
</body>

</html>