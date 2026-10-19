<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

if (isset($_POST['simpan'])) {
    $id_user = $_SESSION['id_user'];
    // kalau checkbox "balasan" dicentang dan surat masuk dipilih, simpan id-nya. kalau tidak, NULL
    $id_surat_masuk = ($_POST['balasan'] == 'ya' && $_POST['id_surat_masuk'] != '')
        ? "'" . $_POST['id_surat_masuk'] . "'"
        : "NULL";

    $nomor_surat = $_POST['nomor_surat'];
    $tanggal_surat = $_POST['tanggal_surat'];
    $tujuan = $_POST['tujuan'];
    $perihal = $_POST['perihal'];
    $isi_ringkas = $_POST['isi_ringkas'];
    $status = $_POST['status'];

    $namaFile = '';
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

    mysqli_query($koneksi, "INSERT INTO surat_keluar
        (id_surat_masuk, id_user, nomor_surat, tanggal_surat, tujuan, perihal, isi_ringkas, file_surat, status)
        VALUES
        ($id_surat_masuk, '$id_user', '$nomor_surat', '$tanggal_surat', '$tujuan', '$perihal', '$isi_ringkas', '$namaFile', '$status')")
        or die(mysqli_error($koneksi));

    header("location: index.php");
    exit;
}

$suratMasuk = mysqli_query($koneksi, "SELECT * FROM surat_masuk ORDER BY id_surat_masuk DESC");
?>
<?php
$base = '../';
$halamanAktif = 'surat_keluar';
$judulHalaman = 'Tambah Surat Keluar';
include '../src/header.php';
?>
    <script>
        function toggleSuratMasuk() {
            var balasan = document.querySelector('input[name="balasan"]:checked').value;
            document.getElementById('pilihanSuratMasuk').style.display = (balasan == 'ya') ? 'block' : 'none';
        }
    </script>
<header><h1>Tambah Surat Keluar</h1></header>
<div class="wrap form-box">
    <a href="index.php" class="kembali">&larr; Kembali</a>
    <form method="post" action="" enctype="multipart/form-data">
        <label>Apakah surat ini balasan dari surat masuk?</label>
        <div>
            <input type="radio" name="balasan" value="ya" onclick="toggleSuratMasuk()"> Ya
            <input type="radio" name="balasan" value="tidak" checked onclick="toggleSuratMasuk()"> Tidak
        </div>

        <div id="pilihanSuratMasuk" style="display:none; margin-top:10px;">
            <label>Pilih Surat Masuk</label>
            <select name="id_surat_masuk">
                <option value="">-- Pilih Surat --</option>
                <?php while ($sm = mysqli_fetch_array($suratMasuk)) { ?>
                    <option value="<?= $sm['id_surat_masuk'] ?>">
                        <?= htmlspecialchars($sm['nomor_surat']) ?> - <?= htmlspecialchars($sm['perihal']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <label>Nomor Surat</label>
        <input type="text" name="nomor_surat" required>

        <label>Tanggal Surat</label>
        <input type="date" name="tanggal_surat" required>

        <label>Tujuan</label>
        <input type="text" name="tujuan" required>

        <label>Perihal</label>
        <input type="text" name="perihal" required>

        <label>Isi Ringkas</label>
        <textarea name="isi_ringkas" rows="3"></textarea>

        <label>File Surat</label>
        <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png">

        <label>Status</label>
        <select name="status">
            <option value="draft">Draft</option>
            <option value="terkirim">Terkirim</option>
        </select>

        <input type="submit" name="simpan" value="Simpan">
    </form>
</div>
<?php include '../src/footer.php'; ?>
