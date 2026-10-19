<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

if ($_SESSION['role'] != 'admin') {
    die("Anda tidak punya akses ke halaman ini.");
}

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // gaya sama seperti login_validasi.php referensi
    $role = $_POST['role'];

    mysqli_query($koneksi, "INSERT INTO users (nama, username, password, role)
        VALUES ('$nama', '$username', '$password', '$role')")
        or die(mysqli_error($koneksi));

    header("location: index.php");
    exit;
}
?>
<?php
$base = '../';
$halamanAktif = 'user';
$judulHalaman = 'Tambah Pengguna';
include '../src/header.php';
?>
<header><h1>Tambah Pengguna</h1></header>
<div class="wrap form-box">
    <a href="index.php" class="kembali">&larr; Kembali</a>
    <form method="post" action="">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" required>

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Role</label>
        <select name="role">
            <option value="petugas">Petugas</option>
            <option value="admin">Admin</option>
        </select>

        <input type="submit" name="simpan" value="Simpan">
    </form>
</div>
<?php include '../src/footer.php'; ?>
