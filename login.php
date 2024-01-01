<?php
include("conn.php");
session_start();
if (isset($_SESSION['admin_username'])) {
    header("location:dashboard.php");
    exit();
} elseif (isset($_SESSION['user_username'])) {
    header("location:dashboard.php");
    exit();
}

$username = "";
$password = "";
$err = "";

// Memeriksa jika form login disubmit
if (isset($_POST['signin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == '' or $password == '') {
        $err = "Silakan masukkan username dan password";
    }

    if (empty($err)) {
        $sql1 = "SELECT * FROM admin WHERE username = '$username'";
        $q1 = mysqli_query($conn, $sql1);

        if (!$q1) {
            // Tangani kasus di mana kueri gagal
            $err = "Error saat menjalankan kueri admin: " . mysqli_error($conn);
        } else {
            $r1 = mysqli_fetch_array($q1);

            if (!$r1 || $r1['password'] != md5($password)) {
                // Cek jika username tidak ditemukan atau password tidak sesuai
                // Jika tidak ditemukan, lanjut ke pemeriksaan user
                $sql2 =  "SELECT * FROM user WHERE nim = '$username'";
                $q2 = mysqli_query($conn, $sql2);

                if (!$q2) {
                    // Tangani kasus di mana kueri gagal
                    $err = "Error saat menjalankan kueri user: " . mysqli_error($conn);
                } else {
                    $r2 = mysqli_fetch_array($q2);

                    if (!$r2 || $r2['password'] != md5($password)) {
                        $err = "Username atau Password salah";
                    }
                }
            } else {
                $_SESSION['admin_username'] = $username;
                header("location:dashboard_admin.php");
                exit();
            }
        }
    }

    if (empty($err)) {
        $_SESSION['user_username'] = $username;
        header("location:dashboard.php");
        exit();
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
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>

    <div class="main">
        
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-image">
                        <figure><img src="images/loker.jpg" alt="sign up image"></figure>
                        <a href="register.php" class="signup-image-link">Create an account</a>
                    </div>

                    <div class="signin-form">
                        <h2 class="form-title">Sign in</h2>
                        <form method="POST" class="register-form">
                            <div class="form-group">
                                <label for="your_username"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="username" id="username" placeholder="Username"/>
                            </div>
                            <div class="form-group">
                                <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password" id="password" placeholder="Password"/>
                            </div>
                            <?php
                                if ($err) {
                                    echo '<script>showAlert("' . $err . '");</script>';
                                }
                            ?>
                            <div class="form-group">
                                <input type="checkbox" name="remember-me" id="remember-me" class="agree-term" />
                                <label for="remember-me" class="label-agree-term"><span><span></span></span>Remember me</label>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signin" id="signin" class="form-submit" value="Log in"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
