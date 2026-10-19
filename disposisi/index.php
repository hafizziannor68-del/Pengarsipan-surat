<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

$saya = (int)$_SESSION['id_user'];
$admin = ($_SESSION['role'] == 'admin');

// admin melihat semua; pengguna lain hanya disposisi yang diterima atau yang dia kirim
$filter = $admin ? "" : "WHERE disposisi.id_user_tujuan='$saya' OR disposisi.id_user='$saya'";

// relasi: disposisi -> surat_masuk, pembuat (users), penerima (users)
$sql = mysqli_query($koneksi, "SELECT disposisi.*, surat_masuk.nomor_surat, surat_masuk.perihal,
        pembuat.nama AS nama_pembuat, penerima.nama AS nama_penerima
    FROM disposisi
    JOIN surat_masuk ON disposisi.id_surat_masuk = surat_masuk.id_surat_masuk
    LEFT JOIN users pembuat ON disposisi.id_user = pembuat.id_user
    LEFT JOIN users penerima ON disposisi.id_user_tujuan = penerima.id_user
    $filter
    ORDER BY disposisi.id_disposisi DESC");
?>
<?php
$base = '../';
$halamanAktif = 'disposisi';
$judulHalaman = 'Disposisi';
include '../src/header.php';
?>
<header><h1>Data Disposisi</h1></header>
<div class="wrap">
    <a href="../dashboard.php" class="kembali">&larr; Kembali ke Dashboard</a>
    <a href="tambah.php" class="btn">+ Tambah Disposisi</a>

    <table>
        <tr>
            <th>No</th>
            <th>Nomor Surat Masuk</th>
            <th>Perihal Surat</th>
            <th>Tanggal Disposisi</th>
            <th>Dari</th>
            <th>Kepada</th>
            <th>Instruksi</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        while ($data = mysqli_fetch_array($sql)) {
            $untukSaya = ($data['id_user_tujuan'] == $saya);
            $boleh_ubah = ($admin || $data['id_user'] == $saya);
            $boleh_tindak = ($admin || $untukSaya);
            $kepada = $data['nama_penerima'] ? $data['nama_penerima'] : $data['tujuan'];
            ?>
            <tr>
                <td><?= $no ?></td>
                <td><?= htmlspecialchars($data['nomor_surat']) ?></td>
                <td><?= htmlspecialchars($data['perihal']) ?></td>
                <td><?= $data['tanggal_disposisi'] ?></td>
                <td><?= htmlspecialchars($data['nama_pembuat'] ?? '-') ?></td>
                <td>
                    <?= htmlspecialchars($kepada) ?>
                    <?php if ($untukSaya) echo "<b>(Anda)</b>"; ?>
                </td>
                <td><?= htmlspecialchars($data['instruksi']) ?></td>
                <td><?= htmlspecialchars($data['status']) ?></td>
                <td>
                    <?php if ($boleh_tindak && $data['status'] == 'menunggu') { ?>
                        <a class="btn btn-edit" href="status.php?id=<?= $data['id_disposisi'] ?>&status=diproses">Proses</a>
                    <?php } ?>
                    <?php if ($boleh_tindak && $data['status'] != 'selesai') { ?>
                        <a class="btn btn-edit" href="status.php?id=<?= $data['id_disposisi'] ?>&status=selesai">Selesai</a>
                    <?php } ?>
                    <?php if ($boleh_ubah) { ?>
                        <a class="btn btn-edit" href="edit.php?id=<?= $data['id_disposisi'] ?>">Edit</a>
                        <a class="btn btn-hapus" href="hapus.php?id=<?= $data['id_disposisi'] ?>" onclick="return confirm('Yakin hapus disposisi ini?')">Hapus</a>
                    <?php } ?>
                </td>
            </tr>
            <?php
            $no++;
        }
        ?>
    </table>
</div>
<?php include '../src/footer.php'; ?>
