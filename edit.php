<?php
// edit.php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login_motor'])) { header("Location: login.php"); exit; }

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM motors WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$motor = $stmt->get_result()->fetch_assoc();

if (isset($_POST['update'])) {
    $merk       = $_POST['merk'];
    $tipe       = $_POST['tipe'];
    $plat       = strtoupper($_POST['plat']);
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $string_foto= $motor['foto_paths']; // default foto lama

    // Jika ada upload foto baru banyak sekaligus
    if (!empty($_FILES['foto_motor']['name'][0])) {
        $foto_uploaded = [];
        foreach ($_FILES['foto_motor']['tmp_name'] as $key => $tmp_name) {
            $nama_file = time() . "_" . $_FILES['foto_motor']['name'][$key];
            if (move_uploaded_file($tmp_name, "uploads/" . $nama_file)) {
                $foto_uploaded[] = $nama_file;
            }
        }
        if (count($foto_uploaded) > 0) {
            $string_foto = implode(",", $foto_uploaded);
        }
    }

    $stmt_update = $conn->prepare("UPDATE motors SET merk=?, tipe=?, plat=?, harga_beli=?, harga_jual=?, foto_paths=? WHERE id=?");
    $stmt_update->bind_param("sssiisi", $merk, $tipe, $plat, $harga_beli, $harga_jual, $string_foto, $id);
    $stmt_update->execute();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Motor - Showroom</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 p-6 font-sans">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-2xl shadow border">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Ubah Data Kendaraan</h2>
        <form method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-1">Merk</label>
                    <select name="merk" class="w-full p-2 border rounded-lg bg-white">
                        <option value="Honda" <?= $motor['merk']=='Honda'?'selected':''; ?>>Honda</option>
                        <option value="Yamaha" <?= $motor['merk']=='Yamaha'?'selected':''; ?>>Yamaha</option>
                        <option value="Suzuki" <?= $motor['merk']=='Suzuki'?'selected':''; ?>>Suzuki</option>
                        <option value="Kawasaki" <?= $motor['merk']=='Kawasaki'?'selected':''; ?>>Kawasaki</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Plat Nomor</label>
                    <input type="text" name="plat" value="<?= $motor['plat']; ?>" class="w-full p-2 border rounded-lg uppercase" required>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">Tipe / Seri Kendaraan</label>
                <input type="text" name="tipe" value="<?= $motor['tipe']; ?>" class="w-full p-2 border rounded-lg" required>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold mb-1">Harga Modal (Beli)</label>
                    <input type="number" name="harga_beli" value="<?= $motor['harga_beli']; ?>" class="w-full p-2 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-xs font-bold mb-1">Harga Jual (Pasar)</label>
                    <input type="number" name="harga_jual" value="<?= $motor['harga_jual']; ?>" class="w-full p-2 border rounded-lg" required>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold mb-1">Ganti Foto Baru (Kosongkan jika tidak diubah)</label>
                <input type="file" name="foto_motor[]" multiple accept="image/*" class="w-full text-xs">
            </div>
            <div class="pt-4 border-t flex justify-end gap-2">
                <a href="index.php" class="px-4 py-2 border rounded-lg text-sm">Batal</a>
                <button type="submit" name="update" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold">Update Data</button>
            </div>
        </form>
    </div>
</body>
</html>