<?php
require '../src/cek_session.php';
require '../src/koneksi.php';
?>
<?php
$base = '../';
$halamanAktif = 'arsip';
$judulHalaman = 'Arsip';
include '../src/header.php';
?>
<header><h1>Data Arsip</h1></header>
<div class="wrap">
    <a href="../dashboard.php" class="kembali">&larr; Kembali ke Dashboard</a>
    <a href="tambah.php" class="btn">+ Tambah Arsip</a>

    <table>
        <tr>
            <th>No</th>
            <th>Kode Arsip</th>
            <th>Sumber Surat</th>
            <th>Tanggal Arsip</th>
            <th>Lokasi</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = mysqli_query($koneksi, "SELECT arsip.*,
                sm.nomor_surat AS nomor_masuk,
                sk.nomor_surat AS nomor_keluar
            FROM arsip
            LEFT JOIN surat_masuk sm ON arsip.id_surat_masuk = sm.id_surat_masuk
            LEFT JOIN surat_keluar sk ON arsip.id_surat_keluar = sk.id_surat_keluar
            ORDER BY arsip.id_arsip DESC");

        $no = 1;
        while ($data = mysqli_fetch_array($sql)) {
            if ($data['nomor_masuk']) {
                $sumber = "Surat Masuk: " . $data['nomor_masuk'];
            } elseif ($data['nomor_keluar']) {
                $sumber = "Surat Keluar: " . $data['nomor_keluar'];
            } else {
                $sumber = "-";
            }
            echo "<tr>
                <td>$no</td>
                <td>{$data['kode_arsip']}</td>
                <td>$sumber</td>
                <td>{$data['tanggal_arsip']}</td>
                <td>{$data['lokasi_arsip']}</td>
                <td>{$data['keterangan']}</td>
                <td>
                    <a class='btn btn-edit' href='edit.php?id={$data['id_arsip']}'>Edit</a>
                    <a class='btn btn-hapus' href='hapus.php?id={$data['id_arsip']}' onclick=\"return confirm('Yakin hapus arsip ini?')\">Hapus</a>
                </td>
            </tr>";
            $no++;
        }
        ?>
    </table>
</div>
<?php include '../src/footer.php'; ?>
