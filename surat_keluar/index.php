<?php
require '../src/cek_session.php';
require '../src/koneksi.php';
?>
<?php
$base = '../';
$halamanAktif = 'surat_keluar';
$judulHalaman = 'Surat Keluar';
include '../src/header.php';
?>
<header><h1>Data Surat Keluar</h1></header>
<div class="wrap">
    <a href="../dashboard.php" class="kembali">&larr; Kembali ke Dashboard</a>
    <a href="tambah.php" class="btn">+ Tambah Surat Keluar</a>

    <table>
        <tr>
            <th>No</th>
            <th>Nomor Surat</th>
            <th>Tanggal</th>
            <th>Tujuan</th>
            <th>Perihal</th>
            <th>Balasan Dari</th>
            <th>Status</th>
            <th>Arsip</th>
            <th>File</th>
            <th>Aksi</th>
        </tr>
        <?php
        // LEFT JOIN karena id_surat_masuk boleh NULL (surat keluar mandiri)
        $sql = mysqli_query($koneksi, "SELECT surat_keluar.*, surat_masuk.nomor_surat AS nomor_surat_masuk,
            (SELECT kode_arsip FROM arsip WHERE arsip.id_surat_keluar = surat_keluar.id_surat_keluar LIMIT 1) AS kode_arsip
            FROM surat_keluar
            LEFT JOIN surat_masuk ON surat_keluar.id_surat_masuk = surat_masuk.id_surat_masuk
            ORDER BY surat_keluar.id_surat_keluar DESC");

        $no = 1;
        while ($data = mysqli_fetch_array($sql)) {
            $balasan = $data['nomor_surat_masuk'] ? $data['nomor_surat_masuk'] : '-';
            echo "<tr>
                <td>$no</td>
                <td>{$data['nomor_surat']}</td>
                <td>{$data['tanggal_surat']}</td>
                <td>{$data['tujuan']}</td>
                <td>{$data['perihal']}</td>
                <td>$balasan</td>
                <td>{$data['status']}</td>
                <td>";
            if ($data['kode_arsip']) {
                echo "<span class='tag tag-ya'>Sudah diarsipkan</span><br><small>" . htmlspecialchars($data['kode_arsip']) . "</small>";
            } else {
                echo "<span class='tag tag-belum'>Belum diarsipkan</span><br>
                    <a class='btn btn-edit' href='../arsip/tambah.php?sumber=keluar&id={$data['id_surat_keluar']}'>Arsipkan</a>";
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
                    <a class='btn btn-edit' href='edit.php?id={$data['id_surat_keluar']}'>Edit</a>
                    <a class='btn btn-hapus' href='hapus.php?id={$data['id_surat_keluar']}' onclick=\"return confirm('Yakin hapus surat ini?')\">Hapus</a>
                </td>
            </tr>";
            $no++;
        }
        ?>
    </table>
</div>
<?php include '../src/footer.php'; ?>
