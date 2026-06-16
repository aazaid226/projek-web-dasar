<?php
// login.php
session_start();
include 'koneksi.php';

if (isset($_SESSION['login_motor'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Proses validasi login
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['login_motor'] = $username;
        echo "<script>alert('Login Berhasil!'); window.location.href='index.php';</script>";
        exit;
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Jual Beli Motor</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-950 flex items-center justify-center min-h-screen">

    <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100 mx-4">
        <h2 class="text-3xl font-extrabold text-center text-slate-800 mb-8 tracking-tight">Showroom Login</h2>
        
        <?php if(isset($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl text-sm mb-5 text-center font-medium animate-shake">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Username</label>
                <input type="text" name="username" class="w-full p-3 border border-slate-300 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 font-medium text-slate-800 transition" placeholder="Masukkan username" required>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input type="password" name="password" class="w-full p-3 border border-slate-300 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 font-medium text-slate-800 transition" placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-amber-500/30 active:scale-[0.99] transition duration-150 text-base mt-2">
                Masuk
            </button>
        </form>
    </div>

</body>
</html>