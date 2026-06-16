<?php
// index.php
session_start();
include 'koneksi.php';

// Proteksi halaman login
if (!isset($_SESSION['login_motor'])) {
    header("Location: login.php");
    exit;
}

$query = $conn->query("SELECT * FROM motors ORDER BY id DESC");
$data_motor = [];
while ($row = $query->fetch_assoc()) {
    $data_motor[] = $row;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showroom Master</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .spinner {
            border: 4px solid rgba(0,0,0,0.1); border-left-color: #f59e0b;
            width: 40px; height: 40px; border-radius: 50%; animation: spin 0.8s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }
    </style>
</head>
<body class="bg-[#f8f9fa] min-h-screen font-sans">

    <nav class="bg-white border-b border-gray-200 px-8 py-4 flex justify-between items-center sticky top-0 z-40">
        <span class="font-bold text-xl text-slate-800 flex items-center gap-2">
            <i class="fas fa-motorcycle text-amber-500"></i> Showroom Master
        </span>
        <div class="flex items-center gap-4">
            <span class="text-sm font-medium text-slate-600 bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 flex items-center gap-2">
                <i class="fas fa-user-shield text-slate-400"></i> Mode: Admin Showroom Master
            </span>
            <a href="logout.php" onclick="return confirm('Yakin ingin keluar?')" class="text-xs bg-red-50 text-red-600 px-3 py-2 rounded-xl font-bold hover:bg-red-100 transition">Keluar</a>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 md:p-8">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Data Stok & Inventaris Showroom</h1>
            <a href="tambah.php" class="bg-[#ff9f00] hover:bg-amber-600 text-white px-5 py-2.5 rounded-2xl text-sm font-bold shadow-md shadow-amber-500/20 transition flex items-center gap-1.5">
                <i class="fas fa-plus-circle"></i> Tambah Motor Baru
            </a>
        </div>

        <div class="flex items-center gap-2 mb-4 flex-wrap">
            <button onclick="window.print()" class="bg-red-50 text-red-600 hover:bg-red-100 border border-red-100 px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fas fa-file-pdf"></i> Export PDF
            </button>
            <button onclick="alert('Memproses Export Excel...')" class="bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-100 px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fas fa-file-excel"></i> Export Excel
            </button>
            <button onclick="window.print()" class="bg-slate-50 text-slate-600 hover:bg-slate-100 border border-slate-200 px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="fas fa-print"></i> Print
            </button>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-gray-100 mb-6 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 text-sm text-slate-600 w-full md:w-auto">
                <span>Show</span>
                <select id="entriHalaman" onchange="gantiJumlahEntri()" class="border border-gray-300 rounded-xl p-1.5 bg-white font-semibold focus:outline-none focus:border-amber-500 text-xs">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span>entries</span>
            </div>

            <div class="relative w-full md:w-96">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400 text-sm"></i>
                <input type="text" id="inputCari" oninput="resetKeHalamanSatuDanCari()" class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-amber-500 transition font-medium bg-slate-50/50" placeholder="Cari Berdasarkan Tipe, Merk, atau Plat...">
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#f8f9fa] text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-gray-100">
                        <tr>
                            <th class="p-4">Model Motor</th>
                            <th class="p-4">Plat Nomor</th>
                            <th class="p-4">Harga Beli</th>
                            <th class="p-4">Harga Jual</th>
                            <th class="p-4">Estimasi Profit</th>
                            <th class="p-4">Kelengkapan Media</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="bodyTabelMotor" class="text-sm text-slate-700 divide-y divide-gray-50 font-semibold bg-white">
                        </tbody>
                </table>
            </div>

            <div class="bg-white px-6 py-4 border-t border-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                <div id="datatableInfo" class="font-medium text-slate-600"></div>
                <div id="datatablePagination" class="flex items-center gap-1"></div>
            </div>
        </div>
    </main>

    <div id="modalDetail" class="fixed inset-0 bg-black/40 items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-3xl p-6 max-w-lg w-full relative shadow-xl">
            <div id="isiDetailModal"></div>
        </div>
    </div>

    <div id="loadingOverlay" class="fixed inset-0 bg-black/70 flex flex-col items-center justify-center hidden z-50">
        <div class="spinner mb-3"></div>
        <p class="text-white text-xs font-semibold tracking-wide">Sinkronisasi Berkas Unit...</p>
    </div>

    <audio id="audioEfek" src="https://assets.mixkit.co/active_storage/sfx/2013/2013-84.wav" preload="auto"></audio>

    <script>
        const databaseLokal = <?php echo json_encode($data_motor); ?>;
        let halamanSekarang = 1;
        let dataPerHalaman = 10; // Nilai default dropdown entri

        function gantiJumlahEntri() {
            dataPerHalaman = parseInt(document.getElementById('entriHalaman').value);
            halamanSekarang = 1; // Reset ke halaman awal
            cariDataTabel();
        }

        function resetKeHalamanSatuDanCari() {
            halamanSekarang = 1; 
            cariDataTabel();
        }

        function cariDataTabel() {
            const keyword = document.getElementById('inputCari').value.toLowerCase();
            const tbody = document.getElementById('bodyTabelMotor');
            tbody.innerHTML = '';

            const dataTerfilter = databaseLokal.filter(item => {
                return item.tipe.toLowerCase().includes(keyword) || 
                       item.merk.toLowerCase().includes(keyword) || 
                       item.plat.toLowerCase().includes(keyword);
            });

            const totalData = dataTerfilter.length;
            const totalHalaman = Math.ceil(totalData / dataPerHalaman);
            
            const indeksAwal = (halamanSekarang - 1) * dataPerHalaman;
            const indeksAkhir = Math.min(indeksAwal + dataPerHalaman, totalData);

            const dataHalamanIni = dataTerfilter.slice(indeksAwal, indeksAkhir);

            dataHalamanIni.forEach(item => {
                const formatRp = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
                const profit = item.harga_jual - item.harga_beli;
                const hitungFile = (str) => (str && str.trim() !== '') ? str.split(',').length : 0;
                
                const jmlFoto = hitungFile(item.foto_paths);
                const jmlVideo = hitungFile(item.video_paths);
                const jmlDocs = hitungFile(item.dokumen_paths);
                const adaTtd = (item.ttd_base64 && item.ttd_base64.length > 50) ? 1 : 0;

                // Media Indicators List Sesuai Gambar 2
                let mediaBadges = `<div class="flex flex-col gap-0.5 text-[11px] font-medium text-slate-500">`;
                mediaBadges += `<span><i class="fas fa-image text-blue-500 mr-1.5 w-3"></i> ${jmlFoto} Foto</span>`;
                mediaBadges += `<span><i class="fas fa-video text-amber-500 mr-1.5 w-3"></i> ${jmlVideo} Video</span>`;
                mediaBadges += `<span><i class="fas fa-file-alt text-emerald-500 mr-1.5 w-3"></i> ${jmlDocs} Dokumen</span>`;
                mediaBadges += `<span><i class="fas fa-signature ${adaTtd ? 'text-emerald-600' : 'text-gray-400'} mr-1.5 w-3"></i> ${adaTtd ? 'TTD Ready' : 'Belum TTD'}</span>`;
                mediaBadges += `</div>`;

                // Aksi Button Warna Soft Sesuai Gambar 2
                tbody.innerHTML += `
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4">
                            <div class="text-slate-900 font-extrabold text-sm">${item.tipe.toUpperCase()}</div>
                            <div class="text-xs text-slate-400 font-normal mt-0.5">${item.merk}</div>
                        </td>
                        <td class="p-4"><span class="bg-slate-50 border text-slate-600 font-mono text-xs px-2 py-1 rounded-lg font-bold">${item.plat}</span></td>
                        <td class="p-4 text-slate-400 font-normal">${formatRp(item.harga_beli)}</td>
                        <td class="p-4 text-amber-500 font-bold">${formatRp(item.harga_jual)}</td>
                        <td class="p-4 text-emerald-600 font-bold">+ ${formatRp(profit)}</td>
                        <td class="p-4">${mediaBadges}</td>
                        <td class="p-4 text-center space-x-1.5 whitespace-nowrap">
                            <button onclick="pemicuModalDetail(${item.id})" class="bg-blue-50 hover:bg-blue-100 text-blue-600 px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-sm">Detail</button>
                            <a href="edit.php?id=${item.id}" class="bg-amber-50 hover:bg-amber-100 text-amber-600 px-3 py-1.5 rounded-xl text-xs font-bold transition inline-block shadow-sm">Edit</a>
                            <a href="hapus.php?id=${item.id}" onclick="return confirm('Hapus unit motor ini beserta medianya?')" class="bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-xl text-xs font-bold transition inline-block shadow-sm">Hapus</a>
                        </td>
                    </tr>
                `;
            });

            if(totalData === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="text-center p-8 text-gray-400 font-normal italic">Data kendaraan kosong.</td></tr>`;
            }

            renderKontrolPaginasi(totalData, totalHalaman, indeksAwal, indeksAkhir);
        }

        // FITUR BARU (Gambar 4): Render Navigasi Paginasi Kotak Biru Solid
        function renderKontrolPaginasi(totalData, totalHalaman, indeksAwal, indeksAkhir) {
            const infoBox = document.getElementById('datatableInfo');
            const paginationBox = document.getElementById('datatablePagination');
            
            if (totalData > 0) {
                infoBox.innerHTML = `Showing <span class="font-semibold text-slate-700">${indeksAwal + 1}</span> to <span class="font-semibold text-slate-700">${indeksAkhir}</span> of <span class="font-semibold text-slate-700">${totalData}</span> entries`;
            } else {
                infoBox.innerHTML = "Showing 0 to 0 of 0 entries";
            }

            paginationBox.innerHTML = '';

            // Tombol Previous
            const btnPrev = document.createElement('button');
            btnPrev.innerText = 'Previous';
            btnPrev.className = `px-3 py-1.5 border border-gray-200 text-xs font-bold rounded-l-xl transition ${halamanSekarang === 1 ? 'text-gray-300 bg-gray-50/50 cursor-not-allowed' : 'text-gray-500 bg-white hover:bg-gray-50'}`;
            if (halamanSekarang > 1) {
                btnPrev.onclick = () => { halamanSekarang--; cariDataTabel(); };
            }
            paginationBox.appendChild(btnPrev);

            // Nomor Halaman (Kotak Biru Solid Sesuai Gambar 4)
            for (let i = 1; i <= totalHalaman; i++) {
                const btnPage = document.createElement('button');
                btnPage.innerText = i;
                btnPage.className = `px-3 py-1.5 border text-xs font-bold transition ${halamanSekarang === i ? 'bg-blue-600 border-blue-600 text-white shadow-sm' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'}`;
                btnPage.onclick = () => { halamanSekarang = i; cariDataTabel(); };
                paginationBox.appendChild(btnPage);
            }

            // Tombol Next
            const btnNext = document.createElement('button');
            btnNext.innerText = 'Next';
            btnNext.className = `px-3 py-1.5 border border-gray-200 text-xs font-bold rounded-r-xl transition ${halamanSekarang === totalHalaman || totalHalaman === 0 ? 'text-gray-300 bg-gray-50/50 cursor-not-allowed' : 'text-gray-500 bg-white hover:bg-gray-50'}`;
            if (halamanSekarang < totalHalaman) {
                btnNext.onclick = () => { halamanSekarang++; cariDataTabel(); };
            }
            paginationBox.appendChild(btnNext);
        }

        function pemicuModalDetail(id) {
            const overlay = document.getElementById('loadingOverlay');
            const audio = document.getElementById('audioEfek');
            
            overlay.classList.remove('hidden'); 
            audio.play().catch(() => {});       

            setTimeout(() => {
                fetch('detail.php?id=' + id)
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('isiDetailModal').innerHTML = html;
                        overlay.classList.add('hidden'); 
                        const modal = document.getElementById('modalDetail');
                        modal.classList.replace('hidden', 'flex');
                    });
            }, 700);
        }

        function tutupModal() {
            document.getElementById('modalDetail').classList.replace('flex', 'hidden');
        }

        // Jalankan render awal
        cariDataTabel();
    </script>
</body>
</html>