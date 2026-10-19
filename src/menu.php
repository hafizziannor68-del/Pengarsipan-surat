<?php
// Sidebar Surat - dipakai oleh semua halaman (pola sama seperti admin/menu.php di SPP)
// $base          : awalan path ('' untuk dashboard, '../' untuk halaman di dalam folder)
// $halamanAktif  : menu yang disorot (dashboard, surat_masuk, disposisi, surat_keluar, arsip, user)

// jumlah disposisi baru (status 'menunggu') yang ditujukan ke pengguna ini
$disposisiBaru = 0;
if (isset($koneksi)) {
    try {
        $idMenu = (int)$_SESSION['id_user'];
        $r = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM disposisi
            WHERE id_user_tujuan = $idMenu AND status = 'menunggu'");
        $disposisiBaru = (int)mysqli_fetch_assoc($r)['total'];
    } catch (Throwable $e) {
        $disposisiBaru = 0; // kolom belum dibuat / query gagal: sidebar tetap tampil
    }
}
?>
<nav>
    <a href="<?= $base ?>dashboard.php" class="<?= $halamanAktif == 'dashboard' ? 'active' : '' ?>">
        <i class="fa-solid fa-gauge"></i><span>Dashboard</span>
    </a>
    <a href="<?= $base ?>surat_masuk/index.php" class="<?= $halamanAktif == 'surat_masuk' ? 'active' : '' ?>">
        <i class="fa-solid fa-inbox"></i><span>Surat Masuk</span>
    </a>
    <a href="<?= $base ?>disposisi/index.php" class="<?= $halamanAktif == 'disposisi' ? 'active' : '' ?>">
        <i class="fa-solid fa-share-from-square"></i><span>Disposisi</span>
        <?php if ($disposisiBaru > 0): ?><em class="badge"><?= $disposisiBaru ?></em><?php endif; ?>
    </a>
    <a href="<?= $base ?>surat_keluar/index.php" class="<?= $halamanAktif == 'surat_keluar' ? 'active' : '' ?>">
        <i class="fa-solid fa-paper-plane"></i><span>Surat Keluar</span>
    </a>
    <a href="<?= $base ?>arsip/index.php" class="<?= $halamanAktif == 'arsip' ? 'active' : '' ?>">
        <i class="fa-solid fa-box-archive"></i><span>Arsip</span>
    </a>
    <?php if ($_SESSION['role'] == 'admin'): ?>
    <a href="<?= $base ?>user/index.php" class="<?= $halamanAktif == 'user' ? 'active' : '' ?>">
        <i class="fa-solid fa-users"></i><span>Pengguna</span>
    </a>
    <?php endif; ?>
</nav>
<a class="keluar" href="<?= $base ?>logout.php">
    <i class="fa-solid fa-right-from-bracket"></i><span>Logout</span>
</a>
