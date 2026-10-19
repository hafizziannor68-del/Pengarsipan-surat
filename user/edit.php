<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

if ($_SESSION['role'] != 'admin') {
    die("Anda tidak punya akses ke halaman ini.");
}

$id = $_GET['id'];

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    if ($_POST['password'] != '') {
        $password = md5($_POST['password']);
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', username='$username', password='$password', role='$role' WHERE id_user='$id'")
            or die(mysqli_error($koneksi));
    } else {
        mysqli_query($koneksi, "UPDATE users SET nama='$nama', username='$username', role='$role' WHERE id_user='$id'")
            or die(mysqli_error($koneksi));
    }

    header("location: index.php");
    exit;
}

$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM users WHERE id_user='$id'"));
?>
<?php
$base = '../';
$halamanAktif = 'user';
$judulHalaman = 'Edit Pengguna';
include '../src/header.php';
?>
<header><h1>Edit Pengguna</h1></header>
<div class="wrap form-box">
    <a href="index.php" class="kembali">&larr; Kembali</a>
    <form method="post" action="">
        <label>Nama Lengkap</label>
        <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>

        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($data['username']) ?>" required>

        <label>Password (kosongkan jika tidak diganti)</label>
        <input type="password" name="password">

        <label>Role</label>
        <select name="role">
            <option value="petugas" <?= $data['role'] == 'petugas' ? 'selected' : '' ?>>Petugas</option>
            <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <input type="submit" name="simpan" value="Update">
    </form>
</div>
<?php include '../src/footer.php'; ?>
