<?php
include("conn.php");

// Memeriksa jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $username = $_POST['name'];
    $nim = $_POST['nim'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk menyimpan data ke dalam tabel 'user'
    $sql = "INSERT INTO user (nama, nim, email, password) VALUES ('$username', '$nim', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registrasi berhasil!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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

        <!-- Sign up form -->
        <section class="signup">
            <div class="container">
                <div class="signup-content">
                    <div class="signup-form">
                        <h2 class="form-title">Sign up</h2>
                        <form method="POST" class="register-form" id="register-form">
                            <div class="form-group">
                                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="name" id="name" placeholder="Name"/>
                            </div>
                            <div class="form-group">
                                <label for="nim"><i class="zmdi zmdi-account material-icons-name"></i></label>
                                <input type="text" name="nim" id="nim" placeholder="Nim"/>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="zmdi zmdi-email"></i></label>
                                <input type="email" name="email" id="email" placeholder="Email"/>
                            </div>
                            <div class="form-group">
                                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="password" id="password" placeholder="Password"/>
                            </div>
                            <div class="form-group">
                                <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                                <input type="password" name="password" id="password" placeholder="Repeat your password"/>
                            </div>
                            <div class="form-group form-button">
                                <input type="submit" name="signup" id="signup" class="form-submit" value="Register"/>
                            </div>
                        </form>
                    </div>
                    <div class="signup-image">
                        <figure><img src="images/loker1.png" alt="sing up image"></figure>
                        <a href="login.php" class="signup-image-link">I am already have account</a>
                    </div>
                </div>
            </div>
        </section>
</div>

<!-- JS -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="js/main.js"></script>
</body><!-- This templates was made by Colorlib (https://colorlib.com) -->
</html>

<?php
$conn->close();
?>