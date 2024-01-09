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
    $query = "SELECT id, nama, nim, email FROM user";
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
                        <ul> <!-- Tambahkan elemen ul di sini -->
                            <li class="p-2">
                                <div class="font-medium"> <?php echo strtoupper($nama_pengguna); ?></div>
                                <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">Mahasiswa</div>
                            </li>
                        </ul>
                    </div>
                        <hr>
                        <a href="#" class="sub-menu-link">
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
            <h3 class="main-title">Detail Mahasiswa</h3>
            <div class="student-profile print-only">
                <img src="images/student_default.jpg" alt="user-pic">
            </div>
            <div class="table-container">
                <table>
                    <tbody>
                        <tr>
                            <td class="whitespace-nowrap font-medium w-1/4">ID</td>
                            <td class="whitespace-nowrap w-3/4"> :1 </td>
                        </tr>
                        <tr>
                            <td class="whitespace-nowrap font-medium w-1/4">Nama</td>
                            <td class="whitespace-nowrap w-3/4"> : JIBRAN KHAIRY AKRAM</td>
                        </tr>
                        <tr>
                            <td class="whitespace-nowrap font-medium w-1/4">NIM</td>
                            <td class="whitespace-nowrap w-3/4"> : 2207421011</td>
                        </tr>
                        <tr>
                            <td class="whitespace-nowrap font-medium w-1/4">Email</td>
                            <td class="whitespace-nowrap w-3/4"> : jibrankhairyy@gmail.com</td>
                        </tr>
                    </tbody>
                </table>
                </table>
                <div class="action-buttons no-print">
                    <button type="button" class="btn btn-edit">Edit Data</button>
                    <button type="button" class="btn btn-print">Print Data</button>
                </div>
            </div>
        </div>

    <script>
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
            
        // sub menu profile
            document.addEventListener("DOMContentLoaded", function () {
        var userProfile = document.querySelector(".user-profile");
        var subMenu = document.querySelector(".sub-menu");
        var subMenuVisible = false; // Deklarasi variabel subMenuVisible

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