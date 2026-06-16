Deskripsi Singkat Aplikasi

Aplikasi Showroom Master ini adalah website pencatatan barang dan stok motor yang dibikin pakai PHP Native dan database MySQL. Tampilannya sudah kekinian banget karena pakai Tailwind CSS v4 dan ikon dari FontAwesome v6, jadi kalau dibuka di HP atau laptop bakal otomatis menyesuaikan ukurannya (responsif).  

Website ini mempermudah admin showroom untuk memantau sisa stok motor, menghitung keuntungan secara otomatis, menyimpan dokumen digital (BPKB/STNK), sampai mencetak laporan inventaris dengan cepat.
Progres Fitur yang Sudah Selesai 100%
Berikut adalah semua fitur keren yang sudah berhasil kita pasang dan berjalan lancar di aplikasi kamu:

1. Keamanan Aplikasi (Login & Logout)
Kunci Halaman: Orang asing tidak bisa asal masuk ke dashboard. Kalau belum login, otomatis akan diusir kembali ke halaman login.
  
Form Login Bersih: Kotak input username dan password sudah kosong bersih tanpa teks tersimpan, jadi lebih aman dari intipan orang.

Logout Aman: Sekali klik tombol keluar, semua sesi admin langsung dihapus total agar akun tidak bisa dibajak.  

3. Olah Data Barang (CRUD Lengkap)
Tambah Data: Bisa mendaftarkan motor baru lengkap dengan merk, tipe, plat nomor, harga modal, dan harga jualnya.
  
Lihat Data: Semua motor langsung muncul di tabel utama, lengkap dengan hitungan otomatis bonus profitnya (Harga Jual dikurangi Harga Beli).

Ubah Data: Kalau ada salah ketik tipe atau plat nomor, data bisa diedit kapan saja tanpa merusak file foto yang sudah ada sebelumnya.

Hapus Data Otomatis: Kalau data motor dihapus, sistem pintar akan langsung menghapus file foto fisiknya di dalam folder laptop kamu agar penyimpanan tidak penuh.  

5. Fitur Multimedia (Multi-File Upload)
Banyak Foto: Sekali upload, bisa langsung memasukkan banyak foto sekaligus untuk memperlihatkan semua sisi kondisi motor.

Nonton Video: Admin bisa upload video review atau animasi motor, dan videonya bisa langsung diputar di dalam website.

Simpan Berkas Digital: File penting seperti scan STNK atau BPKB (.pdf, .doc, .docx) bisa disimpan aman, dan bisa di-download kapan saja.  

7. Tanda Tangan Digital (Canvas HTML5)
Coret Tanda Tangan: Ada papan tulis mini (canvas) di form tambah data, sehingga pembeli atau sales bisa tanda tangan langsung pakai jari (di HP) atau pakai mouse (di laptop).

Simpan Otomatis: Coretan tanda tangan tadi akan langsung diubah menjadi kode teks khusus dan disimpan aman di database.  

9. Tabel Pintar (Datatable & Paginasi)
Ketik Langsung Cari: Tidak perlu pusing mencari motor; cukup ketik plat nomor, merk, atau tipenya, data yang dicari langsung muncul saat itu juga.
  
Atur Jumlah Baris: Ada menu pilihan (Show entries) untuk membatasi tabel agar hanya menampilkan 10, 25, atau 50 baris data saja agar rapi. 

Tombol Halaman Kotak Biru: Dilengkapi tombol halaman (Previous, 1, 2, Next) berwarna biru solid yang rapi di bawah tabel seperti standar aplikasi profesional.  

11. Tampilan Modal (Pop-up) & Cetak Dokumen
Pop-up Detail Cantik: Pas tombol "Detail" diklik, semua spesifikasi, galeri foto, video, dokumen, dan tanda tangan digital akan langsung muncul dalam bentuk kotak melayang (modal) tanpa perlu loading pindah halaman.
 
Suara & Efek Loading: Ada animasi muter (spinner) dan efek suara bel sukses setiap kali kamu membuka detail data motor.  

Ekspor Laporan Semudah 1 Klik: Sudah ada tombol khusus untuk cetak dokumen langsung via printer, serta tombol siap pakai untuk Export PDF dan Export Excel.  

Susunan File di Laptop Kamu
koneksi.php ➡️ Yang menghubungkan website dengan database MySQL kamu.  

login.php & logout.php ➡️ Gerbang pengaman masuk dan keluar sistem.  

index.php ➡️ Halaman utama (tempat tabel pintar, pencarian, dan tombol ekspor berada).  

tambah.php & edit.php ➡️ Form untuk memasukkan motor baru (ada canvas TTD) dan mengubah data.  

detail.php ➡️ Isi konten pop-up modal untuk menampilkan foto, video, dan dokumen motor.  

hapus.php ➡️ Eksekutor untuk menghapus data di database beserta file fotonya.  

uploads/ ➡️ Folder otomatis tempat menampung semua file foto, video (/videos), dan berkas dokumen (/docs) yang diupload.  
