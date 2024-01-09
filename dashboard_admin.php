<?php
session_start();
include("conn.php");

if (!isset($_SESSION['admin_username'])) {
  // Jika tidak ada sesi user_username, alihkan ke halaman login
  header("location: login.php");
  exit();
}

// Lanjutkan dengan pengambilan nama pengguna dari database
$username = $_SESSION['admin_username'];
$query = "SELECT username FROM admin WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
  $row = mysqli_fetch_assoc($result);
  $nama_pengguna = $row['username'];
} else {
  // Handle jika data nama tidak ditemukan
  $nama_pengguna = "Pengguna"; // Default
}

if (isset($_POST['delete'])) {
    $userId = $_POST['user_id'];
    $query = "DELETE FROM user WHERE id = $userId";
    if (mysqli_query($conn, $query)) {
        $message = "Pengguna berhasil dihapus!";
    } else {
        $message = "Gagal menghapus pengguna: " . mysqli_error($conn);
    }
}

// Query untuk mengambil data user dari tabel "user" beserta tanggal pembuatan (tgl_buat)
$query = "SELECT id, nama, nim, email, tgl_buat_akun FROM user";
$result = mysqli_query($conn, $query);

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
                        </li>
                    </div>
                    <hr>
                    
                    <!-- <a href="#" class="sub-menu-link">
                        <img src="images/profile.png">
                        <p>Profile</p>  
                    </a> -->

                    <a href="logout.php" class="sub-menu-link">
                        <img src="images/logout.png">
                        <p>Logout</p>  
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="tabular--wrapper">
        <h3 class="main-title">Data Pengguna</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Nim</th>
                        <th>Email</th>
                        <th>Tanggal Buat Akun</th>
                        <th>Aksi</th>
                    </tr>
                    <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nama'] . "</td>";
                        echo "<td>" . $row['nim'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['tgl_buat_akun'] . "</td>";
                        echo "<td>
                        <div class='btn-group btn-group-horizontal'>
                          <form method='post' onsubmit='return confirmDelete()' style='display: inline;'>
                            <input type='hidden' name='user_id' value='" . $row['id'] . "'>
                            <button type='submit' class='btn' style='background-color: #dc3545 !important; color: #fff !important;' name='delete'>Delete</button>
                          </form>
                        </div>
                      </td>";
}
?>
                    </tbody>
                </thead>
            </table>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
        var userProfile = document.querySelector(".user-profile");
        var subMenu = document.querySelector(".sub-menu");

        userProfile.addEventListener("click", function (event) {
        event.stopPropagation();
        subMenu.classList.toggle("show"); // Menggunakan toggle untuk menambah dan menghapus class "show"
        });

        document.addEventListener("click", function (event) {
        if (!userProfile.contains(event.target)) {
            subMenu.classList.remove("show"); // Menghapus class "show" jika user mengklik di luar user profile
        }
    });
        }); 
    </script>
</body>
</html>
