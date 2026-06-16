<?php
// koneksi.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "jual_beli_motor";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Membuat folder uploads otomatis jika belum ada
if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
}
?>