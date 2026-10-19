<?php
require '../src/cek_session.php';
require '../src/koneksi.php';
?>
<?php
$base = '../';
$halamanAktif = 'surat_masuk';
$judulHalaman = 'Surat Masuk';
include '../src/header.php';
?>
<header><h1>Data Surat Masuk</h1></header>
<div class="wrap">
    <a href="../dashboard.php" class="kembali">&larr;Kembali ke dashboard</a>
    <a href="tambah.php" class="btn">+ Tambah Surat Masuk</a>

    <form method="get" action="">
        cari (nomor/pengirim/perihal):
        <input type="text" name="cari" style="width:250px" value="<?= isset($_GET['cari']) ? htmlspecialchars($_GET['cari']) : '' ?>">
        <input type="submit" value="Cari">
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Nomor Surat</th>
            <th>Tanggal Surat</th>
            <th>Tanggal Diterima</th>
            <th>Pengirim</th>
            <th>Perihal</th>
            <th>Status</th>
            <th>Arsip</th>
            <th>File</th>
            <th>Tools</th>
        </tr>
        <?php
        if (isset($_GET['cari']) && $_GET['cari'] != '') {
            $cari = $_GET['cari'];
            $sql = mysqli_query($koneksi, "SELECT *, (SELECT kode_arsip FROM arsip WHERE arsip.id_surat_masuk = surat_masuk.id_surat_masuk LIMIT 1) AS kode_arsip
                FROM surat_masuk
                WHERE nomor_surat LIKE '%$cari%' OR pengirim LIKE '%$cari%' OR perihal LIKE '%$cari%'
                ORDER BY id_surat_masuk DESC");
        } else {
            $sql = mysqli_query($koneksi, "SELECT *, (SELECT kode_arsip FROM arsip WHERE arsip.id_surat_masuk = surat_masuk.id_surat_masuk LIMIT 1) AS kode_arsip FROM surat_masuk ORDER BY id_surat_masuk DESC");
        }

        $no = 1;
        while ($data = mysqli_fetch_array($sql)) {
            echo "<tr>
                <td>$no</td>
                <td>{$data['nomor_surat']}</td>
                <td>{$data['tanggal_surat']}</td>
                <td>{$data['tanggal_diterima']}</td>
                <td>{$data['pengirim']}</td>
                <td>{$data['perihal']}</td>
                <td>{$data['status']}</td>
                <td>";
            if ($data['kode_arsip']) {
                echo "<span class='tag tag-ya'>Sudah diarsipkan</span><br><small>" . htmlspecialchars($data['kode_arsip']) . "</small>";
            } else {
                echo "<span class='tag tag-belum'>Belum diarsipkan</span><br>
                    <a class='btn btn-edit' href='../arsip/tambah.php?sumber=masuk&id={$data['id_surat_masuk']}'>Arsipkan</a>";
            }
            echo "</td>
                <td>";
            if ($data['file_surat']) {
                echo "<a href='../uploads/{$data['file_surat']}' target='_blank'>Lihat</a>";
            } else {
                echo "-";
            }
            echo "</td>
                <td>
                    <a class='btn btn-edit' href='edit.php?id={$data['id_surat_masuk']}'>Edit</a>
                    <a class='btn btn-hapus' href='hapus.php?id={$data['id_surat_masuk']}' onclick=\"return confirm('Yakin hapus surat ini?')\">Hapus</a>
                </td>
            </tr>";
            $no++;
        }
        ?>
    </table>
</div>
<?php include '../src/footer.php'; ?>
