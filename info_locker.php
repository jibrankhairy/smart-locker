<?php
session_start();
include("conn.php");

class StatusLocker
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getActiveUsers()
    {
        $query = "SELECT id, nama, no_hp, email, status_locker FROM user WHERE status_locker = 'aktif'";
        $result = mysqli_query($this->conn, $query);
        
        $activeUsers = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $activeUsers[] = $row;
        }

        return $activeUsers;
    }

    public function deleteUser($userId)
    {
        $query = "DELETE FROM user WHERE id = $userId";
        return mysqli_query($this->conn, $query);
    }
}

if (!isset($_SESSION['admin_username'])) {
    // Jika tidak ada sesi admin_username, alihkan ke halaman login
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
// instance class
$statusLocker = new StatusLocker($conn);

if (isset($_POST['delete'])) {
    $userId = $_POST['user_id'];
    $deleteResult = $statusLocker->deleteUser($userId);

    if ($deleteResult) {
        $message = "Pengguna berhasil dihapus!";
    } else {
        $message = "Gagal menghapus pengguna: " . mysqli_error($conn);
    }
}

// Mendapatkan data pengguna yang aktif dari StatusLocker
$activeUsers = $statusLocker->getActiveUsers();
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
                    
                    <a href="dashboard_admin.php" class="sub-menu-link">
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
        <h3 class="main-title">Info Locker</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Email</th>
                        <th>Status Locker</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                        foreach ($activeUsers as $user) {
                            echo "<tr>";
                            echo "<td>" . $user['id'] . "</td>";
                            echo "<td>" . $user['nama'] . "</td>";
                            echo "<td>" . $user['no_hp'] . "</td>";
                            echo "<td>" . $user['email'] . "</td>";
                            echo "<td>" . $user['status_locker'] . "</td>";
                            echo "</tr>";
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
