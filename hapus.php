<?php
// hapus.php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login_motor'])) { header("Location: login.php"); exit; }

$id = $_GET['id'] ?? 0;

// Ambil info nama file gambar untuk dihapus dari folder lokal
$stmt = $conn->prepare("SELECT foto_paths FROM motors WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if ($data) {
    if (!empty($data['foto_paths'])) {
        $kumpulan_file = explode(",", $data['foto_paths']);
        foreach ($kumpulan_file as $nama_file) {
            $path_fisik = "uploads/" . $nama_file;
            if (file_exists($path_fisik)) {
                unlink($path_fisik); // Hapus foto fisik dari penyimpanan
            }
        }
    }
    
    // Hapus baris data dari database MySQL
    $delete_stmt = $conn->prepare("DELETE FROM motors WHERE id = ?");
    $delete_stmt->bind_param("i", $id);
    $delete_stmt->execute();
}

header("Location: index.php");
exit;
?>