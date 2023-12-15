<div class="dropdown2">
    <div class="user-pic">
        <img src="/nuraga/images/notif.png" alt="Profile Picture" class="user-pic" height="50px">
        <div class="dropdown-option2" id="notificationDropdown">
            <?php
            // Pastikan session sudah dimulai

            // Dapatkan id_relawan dari session
            $id_relawan = isset($_SESSION['id_relawan']) ? $_SESSION['id_relawan'] : null;

            if ($id_relawan) {
                // Mendapatkan data kegiatan yang diikuti oleh relawan
                $query = "SELECT * FROM daftar WHERE id_relawan = $id_relawan";
                $result2 = mysqli_query($conn, $query);

                // Mengecek apakah ada notifikasi
                $notifExist = false;

                while ($row = mysqli_fetch_assoc($result2)) {
                    $id_kegiatan = $row['id_kegiatan'];

                    // Mendapatkan data kegiatan yang mengalami perubahan
                    $query_kegiatan = "SELECT * FROM kegiatan WHERE id_kegiatan = $id_kegiatan";
                    $result_kegiatan = mysqli_query($conn, $query_kegiatan);

                    if ($result_kegiatan) {
                        $row_kegiatan = mysqli_fetch_assoc($result_kegiatan);

                        // Memeriksa apakah ada perubahan dalam kegiatan
                        if (strtotime($row_kegiatan['lastUpdate']) > strtotime($row['lastUpdated'])) {
                            $nama_kegiatan = $row_kegiatan['nama_kegiatan'];

                            // Menampilkan notifikasi perubahan kegiatan dengan fungsi updateLastUpdate
                            echo '<div class="notification-message" style="width: 480px; min-height: 54px; max-height: 82px; position: relative; background-color: #d61212; color: white; border-radius: 5px; box-shadow: 10px 10px 10px 10px rgba(0, 0, 0, 0.4); text-decoration: none; margin-bottom: 10px; overflow-y: auto; display: flex; align-items: center;">
                            <div style="flex-grow: 1; font-size: 16px; font-family: Inter; font-weight: 400; word-wrap: break-word; padding: 10px;">' . $nama_kegiatan . ' telah mengalami perubahan</div>
                            <img class="Cancel1" onclick="handleCancel(' . $id_kegiatan . ')" style="width: 44px; height: 44px; cursor: pointer;" src="/nuraga/images/cancel.png" />
                        </div>';                                                

                            // Set notifikasi ada
                            $notifExist = true;
                        }
                    }
                }

                // Menampilkan pesan jika tidak ada notifikasi
                if (!$notifExist) {
                    echo '<div class="notification-message">Belum ada perubahan dari kegiatan yang Anda ikuti</div>';
                }
            } else {
                // Handle jika id_relawan tidak tersedia dalam session
                echo "ID Relawan tidak tersedia.";
            }
            ?>
        </div>
    </div>
</div>

<script>
function handleCancel(id_kegiatan) {
    // Lakukan AJAX request untuk mengubah lastUpdate di tabel daftar
    fetch('update_last_update.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id_kegiatan=' + id_kegiatan,
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update waktu lastUpdated pada notifikasi yang telah diklik
            const notification = document.querySelector('.notification-message');
            notification.innerHTML = 'Terakhir diubah: ' + data.lastUpdate; // Sesuaikan dengan nama kolom yang sebenarnya
        } else {
            alert('Gagal mengubah lastUpdate kegiatan! ' + data.error);
        }
    })
    .catch(error => console.error('Error:', error));

    // Hapus notifikasi dari tampilan saat tombol cancel ditekan
    const notification = document.querySelector('.notification-message');
    notification.remove();
}

</script>