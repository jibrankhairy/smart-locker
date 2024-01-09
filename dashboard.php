<?php
session_start();
include("conn.php");

if (!isset($_SESSION['user_username'])) {
  // Jika tidak ada sesi user_username, alihkan ke halaman login
  header("location: login.php");
  exit();
}

// Lanjutkan dengan pengambilan nama pengguna dari database
$username = $_SESSION['user_username'];
$query = "SELECT nama FROM user WHERE nim = '$username'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $nama_pengguna = $row['nama'];
} else {
  // Handle jika data nama tidak ditemukan
  $nama_pengguna = "Pengguna"; // Default
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMART-LOCKER</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-GLhlTQ8iN17PdjfKT29fBkNLAOTveMlM7sHjNFOlMxj1H/ZL/Z9tZ5C+AiSb3DHD" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-GLhlTQ8iN17PdjfKT29fBkNLAOTveMlM7sHjNFOlMxj1H/ZL/Z9tZ5C+AiSb3DHD" crossorigin="anonymous">

     <!-- Icon web -->
     <link rel="icon" type="image/png" sizes="16x16" href="images/loker4.png">

     <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav>
        <div class="logo">
            <img src="images/loker4.png" alt="Logo">
            <h4>SmartLocker</h4>
        </div>
    
        <div class="user-profile"> 
            <img src="images/default_picture.png" alt="user-pic">
            <div class="sub-menu-wrap">
                <div class="sub-menu">
                    <div class="user-info">
                        <li class="p-2">
                            <div class="font-medium"> <?php echo strtoupper($nama_pengguna); ?></div>
                            <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">Mahasiswa</div>
                        </li>
                     </div>
                    <hr>
                    <a href="profile_user.php" class="sub-menu-link">
                        <img src="images/profile.png">
                        <p>Profile</p>
                    </a>
                    <a href="logout.php" class="sub-menu-link">
                        <img src="images/logout.png">
                        <p>Logout</p>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="tabular--wrapper">
        <h3 class="main-title">Informasi Locker</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><span class="tanggalwaktu"></span></td>
                        <td>Perpustakaan</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" id="statusSwitch">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <!-- Tombol untuk Registrasi Locker -->
                            <button id="kodeAButton">Registrasi</button>
                            <button id="kodeBButton">Deactive</button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><span class="tanggalwaktu"></span></td>
                        <td>Perpustakaan</td>
                        <td>
                            <label class="switch">
                                <input type="checkbox" class="statusSwitch">
                                <span class="slider"></span>
                            </label>
                        </td>
                        <td>
                            <!-- Tombol untuk Registrasi Locker -->
                            <button class="kodeAButton">Registrasi</button>
                            <button class="kodeBButton">Deactive</button>
                            <!-- Tombol untuk Aktivasi Locker -->
                            <!-- <button onclick="aktivasiLocker()">Aktivasi</button> -->
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
    var dt = new Date();
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    var locale = 'id-ID'; 
    var tanggalwaktuElements = document.getElementsByClassName("tanggalwaktu");

    for (var i = 0; i < tanggalwaktuElements.length; i++) {
    tanggalwaktuElements[i].innerHTML = dt.toLocaleDateString(locale, options);
}


    var statusSwitch = document.getElementById("statusSwitch");
    var subMenuVisible = false;

    statusSwitch.addEventListener("change", function () {
        var slider = document.querySelector(".slider");
        slider.style.backgroundColor = this.checked ? "#4CAF50" : "#ccc";
    });

    document.getElementById("kodeAButton").addEventListener("click", function () {
    // Fetch request ke server untuk menjalankan kode A
    fetch("http://192.168.64.108/eksekusi-kode-A")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Kode A berhasil dijalankan:", data);

            // Set toggle switch ke aktif setelah berhasil melakukan registrasi
            statusSwitch.checked = true;
            var slider = document.querySelector(".slider");
            slider.style.backgroundColor = "#4CAF50";

            // Menonaktifkan toggle switch agar tidak bisa diklik
            statusSwitch.disabled = true;

            // Menonaktifkan button registrasi setelah dijalankan
            document.getElementById("kodeAButton").disabled = true;

            // Mengaktifkan kembali toggle switch setelah tombol Deactive diklik
            document.getElementById("kodeBButton").disabled = false;
        })
        .catch(error => {
            console.error("Ada kesalahan:", error);

            // Handle kegagalan registrasi di sini
        });
});

document.getElementById("kodeBButton").addEventListener("click", function () {
    // Fetch request ke server untuk menjalankan kode B (mengembalikan ke status awal)
    fetch("http://192.168.64.108/eksekusi-kode-B")
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Kode B berhasil dijalankan:", data);

            // Set toggle switch ke tidak aktif setelah berhasil melakukan deaktivasi
            statusSwitch.checked = false;
            var slider = document.querySelector(".slider");
            slider.style.backgroundColor = "#ccc";

            // Mengaktifkan kembali toggle switch
            statusSwitch.disabled = false;

            // Menonaktifkan button Deactive setelah dijalankan
            document.getElementById("kodeBButton").disabled = true;

            // Mengaktifkan kembali button Registrasi setelah tombol Deactive diklik
            document.getElementById("kodeAButton").disabled = false;
        })
        .catch(error => {
            console.error("Ada kesalahan:", error);

            // Handle kegagalan deaktivasi di sini
        });
});

    document.addEventListener("DOMContentLoaded", function () {
        var userProfile = document.querySelector(".user-profile");
        var subMenu = document.querySelector(".sub-menu");

        userProfile.addEventListener("click", function (event) {
            event.stopPropagation();
            if (subMenuVisible) {
                subMenu.classList.remove("show");
            } else {
                subMenu.classList.add("show");
            }
            subMenuVisible = !subMenuVisible;
        });

        document.addEventListener("click", function (event) {
            if (!userProfile.contains(event.target) && !subMenu.contains(event.target)) {
                subMenu.classList.remove("show");
                subMenuVisible = false;
            }
        });
    });
</script>
</body>
</html>
