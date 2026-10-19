<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

if (isset($_POST['simpan'])) {
    $id_surat_masuk = $_POST['id_surat_masuk'];
    $id_user = $_SESSION['id_user'];
    $tanggal_disposisi = $_POST['tanggal_disposisi'];
    $id_user_tujuan = (int)$_POST['id_user_tujuan'];
    $instruksi = $_POST['instruksi'];
    $status = $_POST['status'];

    // nama penerima juga disimpan di kolom lama `tujuan` agar data lama tetap konsisten
    $penerima = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nama FROM users WHERE id_user='$id_user_tujuan'"));
    if (!$penerima) {
        die("Penerima disposisi tidak valid.");
    }
    $tujuan = mysqli_real_escape_string($koneksi, $penerima['nama']);

    mysqli_query($koneksi, "INSERT INTO disposisi
        (id_surat_masuk, id_user, id_user_tujuan, tanggal_disposisi, tujuan, instruksi, status)
        VALUES
        ('$id_surat_masuk', '$id_user', '$id_user_tujuan', '$tanggal_disposisi', '$tujuan', '$instruksi', '$status')")
        or die(mysqli_error($koneksi));

    header("location: index.php");
    exit;
}

// ambil daftar surat masuk untuk dropdown
$suratMasuk = mysqli_query($koneksi, "SELECT * FROM surat_masuk ORDER BY id_surat_masuk DESC");
// ambil daftar pengguna untuk dropdown penerima
$daftarUser = mysqli_query($koneksi, "SELECT id_user, nama, role FROM users ORDER BY nama ASC");
?>
<?php
$base = '../';
$halamanAktif = 'disposisi';
$judulHalaman = 'Tambah Disposisi';
include '../src/header.php';
?>
<header><h1>Tambah Disposisi</h1></header>
<div class="wrap form-box">
    <a href="index.php" class="kembali">&larr; Kembali</a>
    <form method="post" action="">
        <label>Pilih Surat Masuk</label>
        <select name="id_surat_masuk" required>
            <option value="">-- Pilih Surat --</option>
            <?php while ($sm = mysqli_fetch_array($suratMasuk)) { ?>
                <option value="<?= $sm['id_surat_masuk'] ?>">
                    <?= htmlspecialchars($sm['nomor_surat']) ?> - <?= htmlspecialchars($sm['perihal']) ?>
                </option>
            <?php } ?>
        </select>

        <label>Tanggal Disposisi</label>
        <input type="date" name="tanggal_disposisi" required>

        <label>Kirim ke (Penerima Disposisi)</label>
        <select name="id_user_tujuan" required>
            <option value="">-- Pilih Pengguna --</option>
            <?php while ($u = mysqli_fetch_array($daftarUser)) { ?>
                <option value="<?= $u['id_user'] ?>">
                    <?= htmlspecialchars($u['nama']) ?> (<?= htmlspecialchars($u['role']) ?>)
                </option>
            <?php } ?>
        </select>

        <label>Instruksi</label>
        <textarea name="instruksi" rows="3" required></textarea>

        <label>Status</label>
        <select name="status">
            <option value="menunggu">Menunggu</option>
            <option value="diproses">Diproses</option>
            <option value="selesai">Selesai</option>
        </select>

        <input type="submit" name="simpan" value="Simpan">
    </form>
</div>
<?php include '../src/footer.php'; ?>
