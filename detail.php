<?php
// detail.php
include 'koneksi.php';

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM motors WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<p class='text-red-500 p-4 text-center font-semibold'>Data tidak ditemukan.</p>";
    exit;
}
?>
<div class="flex justify-between items-start border-b pb-3 mb-4">
    <h3 class="text-lg font-bold text-gray-900"><i class="fas fa-info-circle text-amber-500 mr-2"></i>Spesifikasi Detail Unit</h3>
    <button onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 text-xl font-bold transition">&times;</button>
</div>

<div class="space-y-4 text-sm">
    <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 space-y-1">
        <p class="text-gray-700"><strong>Model / Tipe:</strong> <?= htmlspecialchars($data['tipe']); ?> (<?= htmlspecialchars($data['merk']); ?>)</p>
        <p class="text-gray-700"><strong>Plat Nomor:</strong> <span class="bg-gray-200 border border-gray-300 font-mono px-2 py-0.5 rounded text-xs font-bold"><?= htmlspecialchars($data['plat']); ?></span></p>
    </div>
    
    <div>
        <label class="block font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Galeri Foto Kendaraan (Multi-File)</label>
        <div class="flex flex-wrap gap-2">
            <?php if(!empty($data['foto_paths'])): ?>
                <?php foreach(explode(',', $data['foto_paths']) as $foto): ?>
                    <img src="uploads/<?= htmlspecialchars($foto); ?>" class="w-20 h-16 object-cover rounded-lg border shadow-sm hover:scale-105 transition duration-150 cursor-zoom-in">
                <?php endforeach; ?>
            <?php else: ?>
                <span class="text-xs text-gray-400 italic bg-gray-50 px-3 py-1.5 rounded-lg border block w-full">Tidak ada foto terunggah.</span>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <label class="block font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Video / Animasi Review Unit</label>
        <?php if(!empty($data['video_paths'])): ?>
            <div class="grid grid-cols-2 gap-2">
                <?php foreach(explode(',', $data['video_paths']) as $vid): ?>
                    <div class="relative rounded-lg overflow-hidden border bg-black shadow-sm">
                        <video width="100%" height="auto" controls class="w-full h-24 object-cover">
                            <source src="uploads/videos/<?= htmlspecialchars($vid); ?>" type="video/mp4">
                            Browser Anda tidak mendukung tag video.
                        </video>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <span class="text-xs text-gray-400 italic bg-gray-50 px-3 py-1.5 rounded-lg border block">Tidak ada rekaman video terunggah.</span>
        <?php endif; ?>
    </div>

    <div>
        <label class="block font-bold text-xs text-gray-500 uppercase tracking-wider mb-1">Dokumen Surat Kendaraan (STNK/BPKB)</label>
        <?php if(!empty($data['dokumen_paths'])): ?>
            <div class="space-y-1.5 mt-2">
                <?php foreach(explode(',', $data['dokumen_paths']) as $doc): ?>
                    <a href="uploads/docs/<?= htmlspecialchars($doc); ?>" target="_blank" class="flex items-center gap-2 p-2 bg-blue-50 border border-blue-200 text-blue-700 rounded-lg text-xs font-semibold hover:bg-blue-100 transition">
                        <i class="fas fa-file-pdf text-sm text-red-500"></i>
                        <span class="truncate">Buka Dokumen (<?= htmlspecialchars($doc); ?>)</span>
                        <i class="fas fa-external-link-alt ml-auto text-[10px] opacity-70"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <span class="text-xs text-gray-400 italic bg-gray-50 px-3 py-1.5 rounded-lg border block">Tidak ada berkas dokumen digital terunggah.</span>
        <?php endif; ?>
    </div>

    <div class="pt-2 border-t border-gray-100">
        <label class="block font-bold text-xs text-gray-500 uppercase tracking-wider mb-2">Lembar E-Signature (Canvas Terdaftar)</label>
        <?php if(!empty($data['ttd_base64'])): ?>
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-2 flex justify-center shadow-inner">
                <img src="<?= $data['ttd_base64']; ?>" class="w-full h-20 object-contain" alt="Tanda Tangan Digital">
            </div>
        <?php else: ?>
            <span class="text-xs text-gray-400 italic bg-gray-50 px-3 py-1.5 rounded-lg border block">Belum ditandatangani oleh konsumen/sales.</span>
        <?php endif; ?>
    </div>
</div>

<div class="pt-4 mt-4 border-t flex justify-end">
    <button onclick="tutupModal()" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-xs font-bold transition shadow-sm">
        Tutup Jendela
    </button>
</div>