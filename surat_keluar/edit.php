<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

$id = $_GET['id'];

if (isset($_POST['simpan'])) {
    $id_surat_masuk = ($_POST['balasan'] == 'ya' && $_POST['id_surat_masuk'] != '')
        ? "'" . $_POST['id_surat_masuk'] . "'"
        : "NULL";

    $nomor_surat = $_POST['nomor_surat'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $tujuan = $_POST['tujuan'];
    $perihal = $_POST['perihal'];
    $isi_ringkas = $_POST['isi_ringkas'];
    $status = $_POST['status'];

    $lama = mysqli_fetch_array(mysqli_query($koneksi, "SELECT file_surat FROM surat_keluar WHERE id_surat_keluar='$id'"));
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

    mysqli_query($koneksi, "UPDATE surat_keluar SET
        id_surat_masuk=$id_surat_masuk,
        nomor_surat='$nomor_surat',
        tanggal_surat='$tanggal_surat',
        tujuan='$tujuan',
        perihal='$perihal',
        isi_ringkas='$isi_ringkas',
        file_surat='$namaFile',
        status='$status'
        WHERE id_surat_keluar='$id'")
        or die(mysqli_error($koneksi));

    header("location: index.php");
    exit;
}

$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM surat_keluar WHERE id_surat_keluar='$id'"));
$suratMasuk = mysqli_query($koneksi, "SELECT * FROM surat_masuk ORDER BY id_surat_masuk DESC");
$isBalasan = $data['id_surat_masuk'] ? 'ya' : 'tidak';
?>
<?php
$base = '../';
$halamanAktif = 'surat_keluar';
$judulHalaman = 'Edit Surat Keluar';
include '../src/header.php';
?>
    <script>
        function toggleSuratMasuk() {
            var balasan = document.querySelector('input[name="balasan"]:checked').value;
            document.getElementById('pilihanSuratMasuk').style.display = (balasan == 'ya') ? 'block' : 'none';
        }
    </script>
<header><h1>Edit Surat Keluar</h1></header>
<div class="wrap form-box">
    <a href="index.php" class="kembali">&larr; Kembali</a>
    <form method="post" action="" enctype="multipart/form-data">
        <label>Apakah surat ini balasan dari surat masuk?</label>
        <div>
            <input type="radio" name="balasan" value="ya" <?= $isBalasan == 'ya' ? 'checked' : '' ?> onclick="toggleSuratMasuk()"> Ya
            <input type="radio" name="balasan" value="tidak" <?= $isBalasan == 'tidak' ? 'checked' : '' ?> onclick="toggleSuratMasuk()"> Tidak
        </div>

        <div id="pilihanSuratMasuk" style="margin-top:10px; display:<?= $isBalasan == 'ya' ? 'block' : 'none' ?>;">
            <label>Pilih Surat Masuk</label>
            <select name="id_surat_masuk">
                <option value="">-- Pilih Surat --</option>
                <?php while ($sm = mysqli_fetch_array($suratMasuk)) { ?>
                    <option value="<?= $sm['id_surat_masuk'] ?>" <?= $sm['id_surat_masuk'] == $data['id_surat_masuk'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sm['nomor_surat']) ?> - <?= htmlspecialchars($sm['perihal']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <label>Nomor Surat</label>
        <input type="text" name="nomor_surat" value="<?= htmlspecialchars($data['nomor_surat']) ?>" required>

        <label>Tanggal Surat</label>
        <input type="date" name="tanggal_surat" value="<?= $data['tanggal_surat'] ?>" required>

        <label>Tujuan</label>
        <input type="text" name="tujuan" value="<?= htmlspecialchars($data['tujuan']) ?>" required>

        <label>Perihal</label>
        <input type="text" name="perihal" value="<?= htmlspecialchars($data['perihal']) ?>" required>

        <label>Isi Ringkas</label>
        <textarea name="isi_ringkas" rows="3"><?= htmlspecialchars($data['isi_ringkas']) ?></textarea>

        <label>File saat ini: <?= $data['file_surat'] ? $data['file_surat'] : '(belum ada)' ?></label>
        <label>Ganti File (kosongkan jika tidak ingin ganti)</label>
        <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png">

        <label>Status</label>
        <select name="status">
            <option value="draft" <?= $data['status'] == 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="terkirim" <?= $data['status'] == 'terkirim' ? 'selected' : '' ?>>Terkirim</option>
        </select>

        <input type="submit" name="simpan" value="Update">
    </form>
</div>
<?php include '../src/footer.php'; ?>
