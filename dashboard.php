<?php
session_start();
include("conn.php");

class User
{
    private $conn;
    private $username;
    private $nama_pengguna;
    private $status_locker;

    public function __construct($conn, $username)
    {
        $this->conn = $conn;
        $this->username = $username;
        $this->ambilDataPengguna();
    }

    private function ambilDataPengguna()
    {
        if (!isset($_SESSION['user_username'])) {
            header("location: index.php");
            exit();
        }

        $query = "SELECT nama, status_locker FROM user WHERE nim = '$this->username'";
        $result = mysqli_query($this->conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $this->nama_pengguna = $row['nama'];
            $this->status_locker = $row['status_locker'];
            echo '<script>sessionStorage.setItem("lockerStatus", "' . $this->status_locker . '");</script>';
        } else {
            $this->nama_pengguna = "Pengguna"; // Default
            echo '<script>sessionStorage.setItem("lockerStatus", "nonaktif");</script>';
        }
    }

    public function getNamaPengguna()
    {
        return $this->nama_pengguna;
    }

    public function getStatusLocker()
    {
        return $this->status_locker;
    }
}

class SystemLocker
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getActiveLockersCount()
    {
        $query = "SELECT status_locker FROM user WHERE status_locker = 'aktif'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_num_rows($result);
    }

    public function updateLockerStatus($status)
    {
        // Tambahkan kode untuk memperbarui status locker di sini
        $query = "UPDATE user SET status_locker = '$status' WHERE nim = :nim";
        // ...
    }
}

// Instance of the User class
$user = new User($conn, $_SESSION['user_username']);

// Instance of the SystemLocker class
$systemLocker = new SystemLocker($conn);

// Query tambahan untuk mendapatkan semua loker aktif
$activeLockers = $systemLocker->getActiveLockersCount();
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
                            <div class="font-medium"> <?php echo strtoupper($user->getNamaPengguna()); ?></div>
                            <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">Mahasiswa</div>
                        </li>
                     </div>
                    <hr>
                    <a href="profile_user.php" class="sub-menu-link">
                        <img src="images/user.png">
                        <p>Profile</p>
                    </a>
                    <a href="logout.php" class="sub-menu-link">
                        <img src="images/logout1.png">
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
                        <td>3</td>
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
                        <td>4</td>
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
                        <td>5</td>
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
        if (this.disabled) {
            // Mencegah perubahan jika switch dinonaktifkan
            return;
        }

        var slider = document.querySelector(".slider");
        slider.style.backgroundColor = this.checked ? "#4CAF50" : "#ccc";

        // Simpan status di session storage
        sessionStorage.setItem("lockerStatus", this.checked ? "aktif" : "nonaktif");
    });
    var lockerAktifByUser = <?php echo json_encode($user->getStatusLocker() === 'aktif'); ?>;
    document.addEventListener("DOMContentLoaded", function () {
        var statusSwitch = document.getElementById("statusSwitch");

        // Update toggle switch berdasarkan jumlah loker aktif
        if (<?php echo $activeLockers; ?> > 0) {
            statusSwitch.checked = true;
            var slider = document.querySelector(".slider");
            slider.style.backgroundColor = "#4CAF50";

            // Menonaktifkan switch saat dalam keadaan aktif
            statusSwitch.disabled = true;
        } else {
            statusSwitch.checked = false;
            var slider = document.querySelector(".slider");
            slider.style.backgroundColor = "#ccc";
        }
    });
    document.getElementById("kodeAButton").addEventListener("click", function () {
        if (lockerAktifByUser || statusSwitch.checked) {
        // Locker sudah aktif atau terisi, beri pesan atau tindakan lain yang sesuai
        console.log("Anda tidak dapat mengaktifkan locker yang sudah aktif atau terisi.");
        return;
    }
        // Fetch request ke server untuk menjalankan kode A
        fetch("http://192.168.21.108/eksekusi-kode-A", { method: 'GET' })
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

                // Simpan status di session storage
                sessionStorage.setItem("lockerStatus", "aktif");

                // Menonaktifkan toggle switch agar tidak bisa diklik
                statusSwitch.disabled = true;

                // Menonaktifkan button registrasi setelah dijalankan
                document.getElementById("kodeAButton").disabled = true;

                // Mengaktifkan kembali toggle switch setelah tombol Deactive diklik
                document.getElementById("kodeBButton").disabled = false;

                // Update status_locker pada database
                updateStatusLocker('aktif');
            })
            .catch(error => {
                console.error("Ada kesalahan:", error);

                // Handle kegagalan registrasi di sini
            });
    });

    document.getElementById("kodeBButton").addEventListener("click", function () {
        // Fetch request ke server untuk menjalankan kode B (mengembalikan ke status awal)
       if (!lockerAktifByUser || !statusSwitch.checked) {
        // Locker tidak aktif atau belum terisi, beri pesan atau tindakan lain yang sesuai
        console.log("Anda tidak dapat menonaktifkan locker yang tidak aktif atau belum terisi.");
        return;
    }
        fetch("http://192.168.21.108/eksekusi-kode-B", { method: 'GET' })
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

                // Update status_locker pada database
                updateStatusLocker('nonaktif');
            })
            .catch(error => {
                console.error("Ada kesalahan:", error);

                // Handle kegagalan deaktivasi di sini
            });
    });

    function updateStatusLocker(status) {
        // Fetch request untuk mengupdate status_locker pada database
        fetch("update_status_locker.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                status: status,
            }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Status locker berhasil diupdate:", data);
        })
        .catch(error => {
            console.error("Ada kesalahan:", error);
        });
    }

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