<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

// hanya admin yang boleh akses halaman ini
if ($_SESSION['role'] != 'admin') {
    die("Anda tidak punya akses ke halaman ini.");
}
?>
<?php
$base = '../';
$halamanAktif = 'user';
$judulHalaman = 'Pengguna';
include '../src/header.php';
?>
<header><h1>Data Pengguna</h1></header>
<div class="wrap">
    <a href="../dashboard.php" class="kembali">&larr; Kembali ke Dashboard</a>
    <a href="tambah.php" class="btn">+ Tambah Pengguna</a>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Role</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = mysqli_query($koneksi, "SELECT * FROM users ORDER BY id_user DESC");
        $no = 1;
        while ($data = mysqli_fetch_array($sql)) {
            echo "<tr>
                <td>$no</td>
                <td>{$data['nama']}</td>
                <td>{$data['username']}</td>
                <td>{$data['role']}</td>
                <td>
                    <a class='btn btn-edit' href='edit.php?id={$data['id_user']}'>Edit</a>
                    <a class='btn btn-hapus' href='hapus.php?id={$data['id_user']}' onclick=\"return confirm('Yakin hapus pengguna ini?')\">Hapus</a>
                </td>
            </tr>";
            $no++;
        }
        ?>
    </table>
</div>
<?php include '../src/footer.php'; ?>
