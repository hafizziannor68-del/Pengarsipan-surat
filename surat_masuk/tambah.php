<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

if (isset($_POST['simpan'])) {
    $id_user = $_SESSION['id_user'];
    $nomor_surat = $_POST['nomor_surat'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $tanggal_diterima = $_POST['tanggal_diterima'];
    $pengirim = $_POST['pengirim'];
    $perihal = $_POST['perihal'];
    $isi_ringkas = $_POST['isi_ringkas'];
    $status = $_POST['status'];

    $namaFile = '';
    if ($_FILES['file_surat']['name'] != '') {
        $file = $_FILES['file_surat'];
        $ekstensiValid = ['pdf', 'jpg', 'jpeg', 'png'];
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ekstensi, $ekstensiValid)) {
            die("Tipe file tidak diizinkan. Gunakan PDF/JPG/PNG.");
        }
        $namaFile = time() . '_' . $file['name'];
        move_uploaded_file($file['tmp_name'], '../uploads/' . $namaFile);
    }

    mysqli_query($koneksi, "INSERT INTO surat_masuk
        (id_user, nomor_surat, tanggal_surat, tanggal_diterima, pengirim, perihal, isi_ringkas, file_surat, status)
        VALUES
        ('$id_user', '$nomor_surat', '$tanggal_surat', '$tanggal_diterima', '$pengirim', '$perihal', '$isi_ringkas', '$namaFile', '$status')")
        or die(mysqli_error($koneksi));

    header("location: index.php");
    exit;
}
?>
<?php
$base = '../';
$halamanAktif = 'surat_masuk';
$judulHalaman = 'Tambah Surat Masuk';
include '../src/header.php';
?>
<header><h1>Tambah Surat Masuk</h1></header>
<div class="wrap form-box">
    <a href="index.php" class="kembali">&larr; Kembali</a>
    <form method="post" action="" enctype="multipart/form-data">
        <label>Nomor Surat</label>
        <input type="text" name="nomor_surat" required>

        <label>Tanggal Surat</label>
        <input type="date" name="tanggal_surat" required>

        <label>Tanggal Diterima</label>
        <input type="date" name="tanggal_diterima" required>

        <label>Pengirim</label>
        <input type="text" name="pengirim" required>

        <label>Perihal</label>
        <input type="text" name="perihal" required>

        <label>Isi Ringkas</label>
        <textarea name="isi_ringkas" rows="3"></textarea>

        <label>File Surat (PDF/JPG/PNG)</label>
        <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png">

        <label>Status</label>
        <select name="status">
            <option value="baru">Baru</option>
            <option value="diproses">Diproses</option>
            <option value="selesai">Selesai</option>
        </select>

        <input type="submit" name="simpan" value="Simpan">
    </form>
</div>
<?php include '../src/footer.php'; ?>
