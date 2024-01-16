<?php
session_start();
include("conn.php");

if (!isset($_SESSION['user_username'])) {
    // Jika tidak ada sesi user_username, berikan respons sesuai kebutuhan
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit();
}

// Lanjutkan dengan pengambilan nama pengguna dari sesi
$username = $_SESSION['user_username'];

// Ambil data status dari body request
$data = json_decode(file_get_contents("php://input"));

if ($data && isset($data->status)) {
    $status = $data->status;

    // Update status_locker pada database
    $query = "UPDATE user SET status_locker = '$status' WHERE nim = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(["status" => "success", "message" => "Status locker berhasil diupdate"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal mengupdate status locker"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
}
?>
