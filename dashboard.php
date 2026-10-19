<?php
require 'src/cek_session.php';
require 'src/koneksi.php';

$totalSuratMasuk = $koneksi->query("SELECT COUNT(*) AS total FROM surat_masuk")->fetch_assoc()['total'];
$totalSuratKeluar = $koneksi->query("SELECT COUNT(*) AS total FROM surat_keluar")->fetch_assoc()['total'];
$totalDisposisi = $koneksi->query("SELECT COUNT(*) AS total FROM disposisi WHERE status != 'selesai'")->fetch_assoc()['total'];
$totalArsip = $koneksi->query("SELECT COUNT(*) AS total FROM arsip")->fetch_assoc()['total'];

$belumArsip = $koneksi->query("SELECT
    (SELECT COUNT(*) FROM surat_masuk sm WHERE NOT EXISTS (SELECT 1 FROM arsip a WHERE a.id_surat_masuk = sm.id_surat_masuk))
  + (SELECT COUNT(*) FROM surat_keluar sk WHERE NOT EXISTS (SELECT 1 FROM arsip a WHERE a.id_surat_keluar = sk.id_surat_keluar))
  AS total")->fetch_assoc()['total'];

$base = '';
$halamanAktif = 'dashboard'; // dipakai untuk highlight menu aktif di sidebar
$judulHalaman = 'Dashboard';
include 'src/header.php';
?>
        <div class="judul">
            <h1>Dashboard</h1>
            <span class="ikon"><i class="fa-solid fa-gauge"></i></span>
        </div>

        <div class="section-title">Ringkasan</div>
        <div class="folders">
            <a class="folder" href="surat_masuk/index.php">
                <span class="label"><i class="fa-solid fa-inbox"></i>Surat Masuk</span>
                <span class="jumlah"><?= $totalSuratMasuk ?></span>
            </a>
            <a class="folder" href="surat_keluar/index.php">
                <span class="label"><i class="fa-solid fa-paper-plane"></i>Surat Keluar</span>
                <span class="jumlah"><?= $totalSuratKeluar ?></span>
            </a>
            <a class="folder utama" href="disposisi/index.php">
                <span class="label"><i class="fa-solid fa-share-from-square"></i>Disposisi Belum Selesai</span>
                <span class="jumlah"><?= $totalDisposisi ?></span>
            </a>
            <a class="folder" href="arsip/index.php">
                <span class="label"><i class="fa-solid fa-box-archive"></i>Total Arsip</span>
                <span class="jumlah"><?= $totalArsip ?><?php if ($belumArsip > 0): ?><small><?= $belumArsip ?> surat belum diarsipkan</small><?php endif; ?></span>
            </a>
        </div>

        <div class="section-title">Akses Cepat</div>
        <div class="daftar">
            <div class="daftar-kepala">
                <span>Menu</span><span class="kolom-desk">Keterangan</span><span></span>
            </div>
            <a class="baris" href="surat_masuk/index.php">
                <span class="nama"><i class="fa-solid fa-inbox i-1"></i>Surat Masuk</span>
                <span class="desk">Catat dan lihat surat yang diterima</span>
                <span class="buka"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <a class="baris" href="disposisi/index.php">
                <span class="nama"><i class="fa-solid fa-share-from-square i-2"></i>Disposisi</span>
                <span class="desk">Pantau tindak lanjut surat masuk</span>
                <span class="buka"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <a class="baris" href="surat_keluar/index.php">
                <span class="nama"><i class="fa-solid fa-paper-plane i-3"></i>Surat Keluar</span>
                <span class="desk">Catat surat yang dikirim</span>
                <span class="buka"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <a class="baris" href="arsip/index.php">
                <span class="nama"><i class="fa-solid fa-box-archive i-4"></i>Arsip</span>
                <span class="desk">Simpan dan cari surat lama</span>
                <span class="buka"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <?php if ($_SESSION['role'] == 'admin'): ?>
            <a class="baris" href="user/index.php">
                <span class="nama"><i class="fa-solid fa-users i-5"></i>Pengguna</span>
                <span class="desk">Kelola akun dan peran</span>
                <span class="buka"><i class="fa-solid fa-chevron-right"></i></span>
            </a>
            <?php endif; ?>
        </div>
<?php include 'src/footer.php'; ?>
