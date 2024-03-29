<?php
session_start();
include("conn.php");

class User {
    private $conn;
    private $username;
    private $nama_pengguna;

    public function __construct($conn) {
        $this->conn = $conn;

        if (!isset($_SESSION['user_username'])) {
            header("location: login.php");
            exit();
        }

        $this->username = $_SESSION['user_username'];
        $this->ambilDataPengguna();
    }

    private function ambilDataPengguna() {
        $query = "SELECT nama FROM user WHERE nim = '{$this->username}'";
        $result = mysqli_query($this->conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $this->nama_pengguna = $row['nama'];
        } else {
            $this->nama_pengguna = "Pengguna";
        }
    }

    public function perbaruiDataPengguna($newNama, $newEmail, $newNohp) {
        // Validasi bahwa semua kolom diisi
        if (empty($newNama) || empty($newEmail) || empty($newNohp)) {
            $message = "Mohon isi semua kolom";
            // Using JavaScript to show an alert if the form is submitted with empty fields
            echo "<script>alert('$message');</script>";
            return $message;
        }

        $queryPerbarui = "UPDATE user SET nama = '{$newNama}', email = '{$newEmail}', no_hp = '{$newNohp}' WHERE nim = '{$this->username}'";

        if (mysqli_query($this->conn, $queryPerbarui)) {
            $this->redirectDenganPesanSukses();
        } else {
            $message = "Gagal memperbarui data pengguna: " . mysqli_error($this->conn);
            return $message;
        }
    }

    private function redirectDenganPesanSukses() {
        echo "<script>tampilkanPopupSukses();</script>";
        echo "<script>window.location.href = 'profile_user.php';</script>";
        exit();
    }

    public function getNamaPengguna() {
        return $this->nama_pengguna;
    }
}

$user = new User($conn);
$message = ""; // Define $message here to avoid the undefined variable warning

if (isset($_POST['update'])) {
    $newNama = $_POST['new_nama'];
    $newEmail = $_POST['new_email'];
    $newNohp = $_POST['new_no_hp'];

    $message = $user->perbaruiDataPengguna($newNama, $newEmail, $newNohp);

    if (empty($message)) {
        // Data berhasil diperbarui, tambahkan tindakan selanjutnya jika diperlukan
    } else {
        // Tampilkan pesan kesalahan jika ada
        // echo "<script>alert('$message');</script>"; // This line is moved inside the perbaruiDataPengguna method
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">

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
                <a href="dashboard.php" class="sub-menu-link">
                    <img src="images/menu.png">
                    <p>Dashboard</p>
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
    <h3 class="main-title">Edit Data Mahasiswa</h3>
    <!-- <?php
if ($message) {
    echo '<script>
            document.addEventListener("DOMContentLoaded", function () {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "' . $message . '"
                });
            });
         </script>';
}
?> -->
    <div class="student-profile print-only">
        <img src="images/student_default.jpg" alt="user-pic">
    </div>
    <div class="table-container">
        <form method="post">
            <table class="edit-table">
                <tbody>
                    <tr>
                        <td class="whitespace-nowrap font-medium">Nama</td>
                        <td>
                            <input type="text" name="new_nama" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td class="whitespace-nowrap font-medium">Email</td>
                        <td>
                            <input type="email" name="new_email" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td class="whitespace-nowrap font-medium">No HP</td>
                        <td>
                            <input type="no_hp" name="new_no_hp" class="form-control">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-3">
                <button type="submit" class="btn btn-success" style="font-size: 14px; color: white;" name="update" onclick="confirmUpdate();">Simpan Data</button>
                <a href="profile_user.php" class="btn btn-danger" style="font-size: 14px;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var userProfile = document.querySelector(".user-profile");
        var subMenu = document.querySelector(".sub-menu");
        var subMenuVisible = false;

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

    function confirmUpdate() {
        var isConfirmed = confirm('Apakah Anda yakin ingin menyimpan perubahan?');

        if (isConfirmed) {
            document.forms[0].submit(); // Submit form jika dikonfirmasi
        }
    }
</script>
</body>
</html>
