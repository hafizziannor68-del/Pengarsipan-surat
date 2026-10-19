<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

$id = $_GET['id'];

if (isset($_POST['simpan'])) {
    $sumber = $_POST['sumber'] ?? '';
    // dua dropdown punya nama berbeda; ambil sesuai sumber yang dipilih
    $id_surat_masuk = ($sumber == 'masuk') ? (int)($_POST['id_surat_masuk'] ?? 0) : "NULL";
    $id_surat_keluar = ($sumber == 'keluar') ? (int)($_POST['id_surat_keluar'] ?? 0) : "NULL";
    if (($sumber == 'masuk' && !$id_surat_masuk) || ($sumber == 'keluar' && !$id_surat_keluar)) {
        die("Pilih surat terlebih dahulu. (Jika daftar surat kosong, tambahkan suratnya dulu.)");
    }

    // satu surat hanya boleh punya satu arsip (arsip ini sendiri dikecualikan)
    $cekDuplikat = mysqli_fetch_array(mysqli_query($koneksi, $sumber == 'masuk'
        ? "SELECT kode_arsip FROM arsip WHERE id_surat_masuk=$id_surat_masuk AND id_arsip != '$id' LIMIT 1"
        : "SELECT kode_arsip FROM arsip WHERE id_surat_keluar=$id_surat_keluar AND id_arsip != '$id' LIMIT 1"));
    if ($cekDuplikat) {
        die("Surat ini sudah diarsipkan dengan kode " . htmlspecialchars($cekDuplikat['kode_arsip']) . ". <a href='index.php'>Kembali</a>");
    }

    $kode_arsip = $_POST['kode_arsip'];
    $tanggal_arsip = $_POST['tanggal_arsip'];
    $lokasi_arsip = $_POST['lokasi_arsip'];
    $keterangan = $_POST['keterangan'];

    // kode arsip harus unik (kolom kode_arsip di database bersifat UNIQUE)
    $kodeEsc = mysqli_real_escape_string($koneksi, $kode_arsip);
    $kodeSama = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_arsip FROM arsip WHERE kode_arsip='$kodeEsc' AND id_arsip != '$id' LIMIT 1"));
    if ($kodeSama) {
        die("Kode arsip <b>" . htmlspecialchars($kode_arsip) . "</b> sudah dipakai. Gunakan kode yang berbeda. <a href='javascript:history.back()'>Kembali</a>");
    }

    mysqli_query($koneksi, "UPDATE arsip SET
        id_surat_masuk=$id_surat_masuk,
        id_surat_keluar=$id_surat_keluar,
        kode_arsip='$kode_arsip',
        tanggal_arsip='$tanggal_arsip',
        lokasi_arsip='$lokasi_arsip',
        keterangan='$keterangan'
        WHERE id_arsip='$id'")
        or die(mysqli_error($koneksi));

    header("location: index.php");
    exit;
}

$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM arsip WHERE id_arsip='$id'"));
$suratMasuk = mysqli_query($koneksi, "SELECT * FROM surat_masuk ORDER BY id_surat_masuk DESC");
$suratKeluar = mysqli_query($koneksi, "SELECT * FROM surat_keluar ORDER BY id_surat_keluar DESC");
$sumberAktif = $data['id_surat_masuk'] ? 'masuk' : 'keluar';
?>
<?php
$base = '../';
$halamanAktif = 'arsip';
$judulHalaman = 'Edit Arsip';
include '../src/header.php';
?>
    <script>
        function toggleSumber() {
            var sumber = document.querySelector('input[name="sumber"]:checked').value;
            document.getElementById('pilihMasuk').style.display = (sumber == 'masuk') ? 'block' : 'none';
            document.getElementById('pilihKeluar').style.display = (sumber == 'keluar') ? 'block' : 'none';
        }
    </script>
<header><h1>Edit Arsip</h1></header>
<div class="wrap form-box">
    <a href="index.php" class="kembali">&larr; Kembali</a>
    <form method="post" action="">
        <label>Sumber Surat</label>
        <div>
            <input type="radio" name="sumber" value="masuk" <?= $sumberAktif == 'masuk' ? 'checked' : '' ?> onclick="toggleSumber()"> Surat Masuk
            <input type="radio" name="sumber" value="keluar" <?= $sumberAktif == 'keluar' ? 'checked' : '' ?> onclick="toggleSumber()"> Surat Keluar
        </div>

        <div id="pilihMasuk" style="margin-top:10px; display:<?= $sumberAktif == 'masuk' ? 'block' : 'none' ?>;">
            <label>Pilih Surat Masuk</label>
            <select name="id_surat_masuk">
                <?php while ($sm = mysqli_fetch_array($suratMasuk)) { ?>
                    <option value="<?= $sm['id_surat_masuk'] ?>" <?= $sm['id_surat_masuk'] == $data['id_surat_masuk'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sm['nomor_surat']) ?> - <?= htmlspecialchars($sm['perihal']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div id="pilihKeluar" style="margin-top:10px; display:<?= $sumberAktif == 'keluar' ? 'block' : 'none' ?>;">
            <label>Pilih Surat Keluar</label>
            <select name="id_surat_keluar">
                <?php while ($sk = mysqli_fetch_array($suratKeluar)) { ?>
                    <option value="<?= $sk['id_surat_keluar'] ?>" <?= $sk['id_surat_keluar'] == $data['id_surat_keluar'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sk['nomor_surat']) ?> - <?= htmlspecialchars($sk['perihal']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <label>Kode Arsip</label>
        <input type="text" name="kode_arsip" value="<?= htmlspecialchars($data['kode_arsip']) ?>" required>

        <label>Tanggal Arsip</label>
        <input type="date" name="tanggal_arsip" value="<?= $data['tanggal_arsip'] ?>" required>

        <label>Lokasi Arsip</label>
        <input type="text" name="lokasi_arsip" value="<?= htmlspecialchars($data['lokasi_arsip']) ?>" required>

        <label>Keterangan</label>
        <textarea name="keterangan" rows="3"><?= htmlspecialchars($data['keterangan']) ?></textarea>

        <input type="submit" name="simpan" value="Update">
    </form>
</div>
<?php include '../src/footer.php'; ?>
