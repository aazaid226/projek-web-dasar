<?php
// tambah.php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login_motor'])) { 
    header("Location: login.php"); 
    exit; 
}

// Fungsi pembantu untuk mengolah upload berkas (Foto, Video, Dokumen)
function prosesUpload($files, $folder, $allowedExtensions) {
    // Membuat sub-folder otomatis jika belum ada di server
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $uploaded = [];
    if (!empty($files['name'][0])) {
        foreach ($files['tmp_name'] as $key => $tmp_name) {
            $fileName = $files['name'][$key];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            // Validasi Ekstensi Berkas
            if (in_array($fileExt, $allowedExtensions)) {
                $newName = time() . "_" . uniqid() . "." . $fileExt;
                if (move_uploaded_file($tmp_name, $folder . $newName)) {
                    $uploaded[] = $newName;
                }
            }
        }
    }
    return implode(",", $uploaded);
}

// Eksekusi ketika tombol Simpan ditekan
if (isset($_POST['simpan'])) {
    $merk       = $_POST['merk'];
    $tipe       = $_POST['tipe'];
    $plat       = strtoupper($_POST['plat']);
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $ttd_base64 = $_POST['ttd_base64'];

    // 1. Proses Upload Banyak Gambar Unit
    $string_foto = prosesUpload($_FILES['foto_motor'], 'uploads/', ['jpg', 'jpeg', 'png', 'webp']);

    // 2. Proses Upload Banyak Video / Animasi Review
    $video_paths = prosesUpload($_FILES['video_motor'], 'uploads/videos/', ['mp4', 'webm', 'mov']);
    
    // 3. Proses Upload Banyak Dokumen Surat (PDF/DOC)
    $doc_paths = prosesUpload($_FILES['doc_motor'], 'uploads/docs/', ['pdf', 'doc', 'docx']);

    // 4. Query Prepared Statement Simpan ke Database MySQL
    $stmt = $conn->prepare("INSERT INTO motors (merk, tipe, plat, harga_beli, harga_jual, foto_paths, ttd_base64, video_paths, dokumen_paths) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiissss", $merk, $tipe, $plat, $harga_beli, $harga_jual, $string_foto, $ttd_base64, $video_paths, $doc_paths);
    $stmt->execute();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Motor - Showroom</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 p-6 font-sans">

    <div class="max-w-xl mx-auto bg-white p-6 rounded-2xl shadow border border-gray-200">
        <h2 class="text-xl font-bold mb-4 text-gray-800">Registrasi Unit Motor Baru</h2>
        
        <form method="POST" enctype="multipart/form-data" onsubmit="siapkanDataSubmit(event)" class="space-y-4">
            <input type="hidden" name="ttd_base64" id="input_ttd">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Merk</label>
                    <select name="merk" class="w-full p-2 border rounded-lg bg-white focus:outline-amber-500">
                        <option value="Honda">Honda</option>
                        <option value="Yamaha">Yamaha</option>
                        <option value="Suzuki">Suzuki</option>
                        <option value="Kawasaki">Kawasaki</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Plat Nomor</label>
                    <input type="text" name="plat" class="w-full p-2 border rounded-lg uppercase focus:outline-amber-500" placeholder="B 1234 ABC" required>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Tipe / Seri Kendaraan</label>
                <input type="text" name="tipe" class="w-full p-2 border rounded-lg focus:outline-amber-500" placeholder="Scoopy Prestige 2024" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Harga Modal (Beli)</label>
                    <input type="number" name="harga_beli" class="w-full p-2 border rounded-lg focus:outline-amber-500" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1">Harga Jual (Pasar)</label>
                    <input type="number" name="harga_jual" class="w-full p-2 border rounded-lg focus:outline-amber-500" required>
                </div>
            </div>

            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                <label class="block text-xs font-bold text-slate-700 mb-1">Upload Foto Unit (Multiple Gambar)</label>
                <input type="file" name="foto_motor[]" id="multiFotoInput" multiple accept="image/*" onchange="previewFoto(event)" class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100" required>
                <div id="boxPreview" class="flex flex-wrap gap-2 mt-2"></div>
            </div>

            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                <label class="block text-xs font-bold text-slate-700 mb-1">Upload Video / Animasi Review (.mp4, .webm)</label>
                <input type="file" name="video_motor[]" multiple accept="video/*" class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                <label class="block text-xs font-bold text-slate-700 mb-1">Upload Dokumen Surat Berkas / BPKB (.pdf, .doc, .docx)</label>
                <input type="file" name="doc_motor[]" multiple accept=".pdf,.doc,.docx" class="w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Tanda Tangan Digital Pembeli / Sales (Canvas)</label>
                <canvas id="canvasTTD" width="450" height="120" class="w-full bg-slate-50 border rounded-lg cursor-crosshair shadow-inner"></canvas>
                <button type="button" onclick="hapusCanvas()" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-700 px-2 py-1 rounded mt-1 transition">Reset TTD</button>
            </div>

            <div class="pt-4 border-t flex justify-end gap-2">
                <a href="index.php" class="px-4 py-2 border rounded-lg text-sm text-gray-600 hover:bg-gray-50 transition">Batal</a>
                <button type="submit" name="simpan" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-semibold shadow transition">Simpan Data</button>
            </div>
        </form>
    </div>

    <script>
        const canvas = document.getElementById('canvasTTD');
        const ctx = canvas.getContext('2d');
        let drawing = false;

        // Set karakteristik goresan tinta ttd
        ctx.strokeStyle = "#1e3a8a"; ctx.lineWidth = 3; ctx.lineCap = "round";
        
        const getPos = (e) => {
            const r = canvas.getBoundingClientRect();
            return { x: (e.clientX || e.touches[0].clientX) - r.left, y: (e.clientY || e.touches[0].clientY) - r.top };
        };

        // Event listener mouse untuk coretan canvas
        canvas.addEventListener('mousedown', (e) => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
        canvas.addEventListener('mousemove', (e) => { if(!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
        window.addEventListener('mouseup', () => drawing = false);

        // Event listener touch screen perangkat mobile
        canvas.addEventListener('touchstart', (e) => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
        canvas.addEventListener('touchmove', (e) => { if(!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
        canvas.addEventListener('touchend', () => drawing = false);

        function hapusCanvas() { ctx.clearRect(0, 0, canvas.width, canvas.height); }

        // Preview Realtime Thumbnail Foto Sebelum Diupload
        function previewFoto(event) {
            const container = document.getElementById('boxPreview');
            container.innerHTML = '';
            Array.from(event.target.files).forEach(file => {
                const r = new FileReader();
                r.onload = (e) => {
                    const img = document.createElement('img'); 
                    img.src = e.target.result;
                    img.className = "w-12 h-12 object-cover rounded border border-gray-300 shadow-sm";
                    container.appendChild(img);
                };
                r.readAsDataURL(file);
            });
        }

        // Intersepsi Form saat Submit untuk Ekspor Data Canvas ke Input Hidden
        function siapkanDataSubmit(e) {
            const blank = document.createElement('canvas');
            blank.width = canvas.width; blank.height = canvas.height;
            
            // Periksa jika canvas tidak kosong, konversikan goresan ke string Base64
            if(canvas.toDataURL() !== blank.toDataURL()) {
                document.getElementById('input_ttd').value = canvas.toDataURL();
            }
        }
    </script>
</body>
</html>