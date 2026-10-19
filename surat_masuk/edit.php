<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

$id = $_GET['id'];

if (isset($_POST['simpan'])) {
    $nomor_surat = $_POST['nomor_surat'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $tanggal_diterima = $_POST['tanggal_diterima'];
    $pengirim = $_POST['pengirim'];
    $perihal = $_POST['perihal'];
    $isi_ringkas = $_POST['isi_ringkas'];
    $status = $_POST['status'];

    // ambil nama file lama supaya tidak hilang kalau tidak upload file baru
    $lama = mysqli_fetch_array(mysqli_query($koneksi, "SELECT file_surat FROM surat_masuk WHERE id_surat_masuk='$id'"));
    $namaFile = $lama['file_surat'];

    if ($_FILES['file_surat']['name'] != '') {
        $file = $_FILES['file_surat'];
        $ekstensiValid = ['pdf', 'jpg', 'jpeg', 'png'];
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ekstensi, $ekstensiValid)) {
            die("Tipe file tidak diizinkan.");
        }
        $namaFile = time() . '_' . $file['name'];
        move_uploaded_file($file['tmp_name'], '../uploads/' . $namaFile);
    }

    mysqli_query($koneksi, "UPDATE surat_masuk SET
        nomor_surat='$nomor_surat',
        tanggal_surat='$tanggal_surat',
        tanggal_diterima='$tanggal_diterima',
        pengirim='$pengirim',
        perihal='$perihal',
        isi_ringkas='$isi_ringkas',
        file_surat='$namaFile',
        status='$status'
        WHERE id_surat_masuk='$id'")
        or die(mysqli_error($koneksi));

    header("location: index.php");
    exit;
}

$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM surat_masuk WHERE id_surat_masuk='$id'"));
?>
<?php
$base = '../';
$halamanAktif = 'surat_masuk';
$judulHalaman = 'Edit Surat Masuk';
include '../src/header.php';
?>
<header><h1>Edit Surat Masuk</h1></header>
<div class="wrap form-box">
    <a href="index.php" class="kembali">&larr; Kembali</a>
    <form method="post" action="" enctype="multipart/form-data">
        <label>Nomor Surat</label>
        <input type="text" name="nomor_surat" value="<?= htmlspecialchars($data['nomor_surat']) ?>" required>

        <label>Tanggal Surat</label>
        <input type="date" name="tanggal_surat" value="<?= $data['tanggal_surat'] ?>" required>

        <label>Tanggal Diterima</label>
        <input type="date" name="tanggal_diterima" value="<?= $data['tanggal_diterima'] ?>" required>

        <label>Pengirim</label>
        <input type="text" name="pengirim" value="<?= htmlspecialchars($data['pengirim']) ?>" required>

        <label>Perihal</label>
        <input type="text" name="perihal" value="<?= htmlspecialchars($data['perihal']) ?>" required>

        <label>Isi Ringkas</label>
        <textarea name="isi_ringkas" rows="3"><?= htmlspecialchars($data['isi_ringkas']) ?></textarea>

        <label>File saat ini: <?= $data['file_surat'] ? $data['file_surat'] : '(belum ada)' ?></label>
        <label>Ganti File (kosongkan jika tidak ingin ganti)</label>
        <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png">

        <label>Status</label>
        <select name="status">
            <option value="baru" <?= $data['status'] == 'baru' ? 'selected' : '' ?>>Baru</option>
            <option value="diproses" <?= $data['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
            <option value="selesai" <?= $data['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>

        <input type="submit" name="simpan" value="Update">
    </form>
</div>
<?php include '../src/footer.php'; ?>
