<?php
include("conn.php");
session_start();

$email = ""; // Definisikan $email sebelum penggunaan
$err = "";

// Cek apakah formulir untuk mereset password sudah dikirimkan
if (isset($_POST['forgot_password'])) {
    $email = $_POST['email'];
    // Cek apakah email ada di database
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $query = mysqli_query($conn, $sql);

    if (!$query) {
        $err = "Terjadi kesalahan saat mengeksekusi kueri pengguna: " . mysqli_error($conn);
    } else {
        $result = mysqli_fetch_array($query);

        if (!$result) {
            $err = "Email tidak ditemukan";

            // Tambahkan script untuk menampilkan pesan pop-up SweetAlert2
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "' . $err . '",
                    });
                 </script>';
        } else {
            // Simpan email di sesi untuk verifikasi di halaman berikutnya
            $_SESSION['reset_email'] = $email;

            // Alihkan ke halaman verifikasi OTP
            header("location: reset_password.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SMART-LOCKER</title>

    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">

    <!-- Icon web -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/loker4.png">

    <!-- Main css -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<div class="main">
    <section class="sign-in">
        <div class="container">
            <div class="signin-content">
                <div class="signin-image">
                    <figure><img src="images/loker4.png" alt="sign up image"></figure>
                    <a href="index.php" class="signup-image-link">I am already have account</a>
                </div>

                <div class="signin-form">
                    <h2 class="form-title">Forgot Password</h2>
                    <?php
                    if ($err) {
                        echo '<script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: "' . $err . '",
                                });
                             </script>';
                    }
                    ?>
                    <form method="POST" class="register-form">
                        <div class="form-group">
                            <label for="email"><i class="zmdi zmdi-email"></i></label>
                            <input type="email" name="email" id="email" placeholder="Your Email"/>
                        </div>
                        <div class="form-group form-button">
                            <input type="submit" name="forgot_password" id="forgot_password" class="form-submit" value="Reset Password" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>
