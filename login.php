<?php
include("conn.php");

// Memeriksa jika form login disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin'])) {
    $name = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Query untuk memeriksa apakah data sesuai dengan yang ada di database
    if (!empty($name) && !empty($password)) {
        $sql = "SELECT * FROM user WHERE nama='$name' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // Login berhasil
            header("Location: dashboard.php");
            exit(); // Pastikan untuk menghentikan eksekusi skrip setelah header redirect
        } else {
            // Login gagal
            echo "Login gagal. Periksa kembali nama pengguna dan kata sandi.";
        }
    } else {
        echo "Nama pengguna dan kata sandi tidak boleh kosong.";
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
    <link rel="stylesheet" href="static/style.css">
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
<!-- nyoba -->
