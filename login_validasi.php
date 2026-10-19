<?php
session_start();
include 'src/koneksi.php';

$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$password = mysqli_real_escape_string($koneksi, $_POST['password']);
$password_md5 = md5($_POST['password']); // sama seperti SPP; halaman Pengguna menyimpan password dengan md5

// cocokkan dengan md5 (akun dari halaman Pengguna) ATAU teks biasa (akun lama yang dibuat manual di database)
$login = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND (password='$password_md5' OR password='$password')")
        or die(mysqli_error($koneksi));
$cek = mysqli_num_rows($login);

if ($cek > 0) {
    $data = mysqli_fetch_assoc($login);
    if ($data['role'] == "admin") {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = "admin";
        $_SESSION['id_user'] = $data['id_user'];
        echo '<script>alert("Anda berhasil Login Admin!"); document.location="dashboard.php";</script>';
        exit;
    } else if ($data['role'] == "petugas") {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = "petugas";
        $_SESSION['id_user'] = $data['id_user'];
        echo '<script>alert("Anda berhasil login petugas!"); document.location="dashboard.php";</script>';
        exit;
    } else {
        header("location: index.php?pesan=gagal");
        exit;
    }
} else {
    header("location: index.php?pesan=gagal");
    exit;
}