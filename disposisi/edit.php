<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

$id = (int)$_GET['id'];

// hanya admin atau pembuat disposisi yang boleh mengubah
$cek = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_user FROM disposisi WHERE id_disposisi='$id'"));
if (!$cek || ($_SESSION['role'] != 'admin' && $cek['id_user'] != $_SESSION['id_user'])) {
    die("Anda tidak punya akses untuk mengubah disposisi ini.");
}

if (isset($_POST['simpan'])) {
    $id_surat_masuk = $_POST['id_surat_masuk'];
    $tanggal_disposisi = $_POST['tanggal_disposisi'];
    $id_user_tujuan = (int)$_POST['id_user_tujuan'];
    $instruksi = $_POST['instruksi'];
    $status = $_POST['status'];

    $penerima = mysqli_fetch_array(mysqli_query($koneksi, "SELECT nama FROM users WHERE id_user='$id_user_tujuan'"));
    if (!$penerima) {
        die("Penerima disposisi tidak valid.");
    }
    $tujuan = mysqli_real_escape_string($koneksi, $penerima['nama']);

    mysqli_query($koneksi, "UPDATE disposisi SET
        id_surat_masuk='$id_surat_masuk',
        tanggal_disposisi='$tanggal_disposisi',
        id_user_tujuan='$id_user_tujuan',
        tujuan='$tujuan',
        instruksi='$instruksi',
        status='$status'
        WHERE id_disposisi='$id'")
        or die(mysqli_error($koneksi));

    header("location: index.php");
    exit;
}

$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM disposisi WHERE id_disposisi='$id'"));
$suratMasuk = mysqli_query($koneksi, "SELECT * FROM surat_masuk ORDER BY id_surat_masuk DESC");
$daftarUser = mysqli_query($koneksi, "SELECT id_user, nama, role FROM users ORDER BY nama ASC");
?>
<?php
$base = '../';
$halamanAktif = 'disposisi';
$judulHalaman = 'Edit Disposisi';
include '../src/header.php';
?>
<header><h1>Edit Disposisi</h1></header>
<div class="wrap form-box">
    <a href="index.php" class="kembali">&larr; Kembali</a>
    <form method="post" action="">
        <label>Pilih Surat Masuk</label>
        <select name="id_surat_masuk" required>
            <?php while ($sm = mysqli_fetch_array($suratMasuk)) { ?>
                <option value="<?= $sm['id_surat_masuk'] ?>" <?= $sm['id_surat_masuk'] == $data['id_surat_masuk'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($sm['nomor_surat']) ?> - <?= htmlspecialchars($sm['perihal']) ?>
                </option>
            <?php } ?>
        </select>

        <label>Tanggal Disposisi</label>
        <input type="date" name="tanggal_disposisi" value="<?= $data['tanggal_disposisi'] ?>" required>

        <label>Kirim ke (Penerima Disposisi)</label>
        <select name="id_user_tujuan" required>
            <?php if (!$data['id_user_tujuan']) { ?>
                <option value="">-- Pilih Pengguna (sebelumnya: <?= htmlspecialchars($data['tujuan']) ?>) --</option>
            <?php } ?>
            <?php while ($u = mysqli_fetch_array($daftarUser)) { ?>
                <option value="<?= $u['id_user'] ?>" <?= $u['id_user'] == $data['id_user_tujuan'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['nama']) ?> (<?= htmlspecialchars($u['role']) ?>)
                </option>
            <?php } ?>
        </select>

        <label>Instruksi</label>
        <textarea name="instruksi" rows="3" required><?= htmlspecialchars($data['instruksi']) ?></textarea>

        <label>Status</label>
        <select name="status">
            <option value="menunggu" <?= $data['status'] == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
            <option value="diproses" <?= $data['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
            <option value="selesai" <?= $data['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>

        <input type="submit" name="simpan" value="Update">
    </form>
</div>
<?php include '../src/footer.php'; ?>
