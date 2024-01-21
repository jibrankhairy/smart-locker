<?php
include("conn.php");
session_start();

$err = "";

// Cek apakah formulir untuk mereset password sudah dikirimkan
if (isset($_POST['reset_password'])) {
    $enteredEmail = $_POST['email'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Cek apakah email yang dimasukkan sesuai dengan nilai yang disimpan di sesi
    if ($_SESSION['reset_email'] == $enteredEmail) {
        // Validasi password dan konfirmasi
        if (empty($newPassword) || empty($confirmPassword)) {
            $err = "Mohon isi semua kolom";
        } elseif ($newPassword != $confirmPassword) {
            $err = "Kata sandi dan konfirmasi tidak cocok";
        } else {
            // Perbarui kata sandi pengguna dalam database
            $hashedPassword = md5($newPassword); // Catatan: MD5 tidak disarankan untuk keamanan, gunakan fungsi hash yang lebih kuat
            $updateSql = "UPDATE user SET password = '$hashedPassword' WHERE email = '$enteredEmail'";
            $updateQuery = mysqli_query($conn, $updateSql);

            if (!$updateQuery) {
                $err = "Error saat memperbarui kata sandi: " . mysqli_error($conn);
            } else {
                // Reset nilai sesi
                unset($_SESSION['reset_email']);

                // Tampilkan pesan konfirmasi setelah berhasil memperbarui kata sandi
                echo '<script>
                        document.addEventListener("DOMContentLoaded", function () {
                            var isConfirmed = window.confirm("Kata Sandi Diperbarui. Apakah Anda ingin menuju ke halaman login?");
                            if (isConfirmed) {
                                window.location.href = "index.php";
                            }
                        });
                     </script>';
                exit();
            }
        }
    } else {    
        $err = "Email tidak valid";
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
                        <h2 class="form-title">Reset Password</h2>
                        <?php
                        if ($err) {
                            echo '<script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        Swal.fire({
                                            icon: "error",
                                            title: "Error",
                                            text: "' . $err . '"
                                        });
                                    });
                                 </script>';
                        }
                        ?>
                        <form method="POST" class="register-form">
                            <div class="form-group">
                                <label for="your_email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Email" value="<?php echo $_SESSION['reset_email']; ?>" readonly />
                            </div>
                            <div class="form-group">
                                <label for="new_password"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="new_password" id="new_password" placeholder="New Password" />
                            </div>
                            <div class="form-group">
                                <label for="confirm_password"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" />
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="reset_password" id="reset_password" class="form-submit" value="Submit" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
