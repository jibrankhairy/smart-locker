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
// Query untuk mengambil data user dari tabel "user" beserta tanggal pembuatan (tgl_buat)
$query = "SELECT id, nama, nim, email, no_hp FROM user WHERE nim = '$username'";
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

     <!-- Print data -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.js"></script>
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
        <h3 class="main-title">Detail Mahasiswa</h3>
            <div class="student-profile print-only">
            <img src="images/student_default.jpg" alt="user-pic">
                </div>
                <div class="table-container">
                <tbody>
                <?php
                // ...
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    echo "<table>";
                    echo "<tbody>";
                    echo "<tr>";
                    echo '<td class="whitespace-nowrap font-medium w-1/4">ID:</td>';
                    echo "<td>" . $row['id'] . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo '<td class="whitespace-nowrap font-medium w-1/4">Nama:</td>';
                    echo "<td>" . $row['nama'] . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo '<td class="whitespace-nowrap font-medium w-1/4">NIM:</td>';
                    echo "<td>" . $row['nim'] . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo '<td class="whitespace-nowrap font-medium w-1/4">Email:</td>';
                    echo "<td>" . $row['email'] . "</td>";
                    echo "</tr>";
                    echo "<tr>";
                    echo '<td class="whitespace-nowrap font-medium w-1/4">No HP:</td>';
                    echo "<td>" . $row['no_hp'] . "</td>";
                    echo "</tr>";
                    echo "</tbody>";
                    echo "</table>";
                    
                    // Reset posisi pointer
                    mysqli_data_seek($result, 0);
                } else {
                    // Handle jika data tidak ditemukan
                    echo "Data tidak ditemukan.";
                }
                ?>
            <div class="action-buttons no-print">
                <a href="edit_user.php" class="btn btn-edit" style="font-size: 14px;">Edit Data</a>
                <button type="button" class="btn btn-print" style="font-size: 14px;">Print Data</button>
            </div>
        </div>  
    </tbody>

    <script>
    var subMenuVisible = false;

    document.addEventListener("DOMContentLoaded", function () {
        var userProfile = document.querySelector(".user-profile");
        var subMenu = document.querySelector(".sub-menu");

        userProfile.addEventListener("click", function (event) {
            event.stopPropagation();
            console.log("User profile clicked");
            if (subMenuVisible) {
                subMenu.classList.remove("show");
            } else {
                subMenu.classList.add("show");
            }
            subMenuVisible = !subMenuVisible;
        });

        document.addEventListener("click", function (event) {
            console.log("Document clicked");
            if (!userProfile.contains(event.target) && !subMenu.contains(event.target)) {
                subMenu.classList.remove("show");
                subMenuVisible = false;
            }
        });

        // Tangani klik tombol "Print Data"
    var printDataBtn = document.querySelector('.btn-print');
    printDataBtn.addEventListener('click', function () {
    // Panggil fungsi untuk membuat file PDF, dan sertakan kelas "no-print" untuk dikecualikan
    generatePDF('.table-container:not(.no-print)');
    });

    // Fungsi untuk membuat file PDF
    function generatePDF(selector) {
    var tableContainer = document.querySelector(selector);

    // Gunakan html2pdf.js
    html2pdf().from(tableContainer).save();
    }

});
</script>

</body>
</html>
