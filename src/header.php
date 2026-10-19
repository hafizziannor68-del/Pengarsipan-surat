<?php
// Bagian ATAS layout (head + logo + topbar + sidebar + pembuka <main>)
// Variabel yang harus diisi halaman sebelum include file ini:
//   $base          = '' atau '../'
//   $halamanAktif  = nama menu yang aktif
//   $judulHalaman  = judul tab browser
if (!isset($base)) $base = '';
if (!isset($halamanAktif)) $halamanAktif = '';
if (!isset($judulHalaman)) $judulHalaman = 'Sistem Pengarsipan Surat';
$inisial = strtoupper(substr($_SESSION['username'], 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($judulHalaman) ?> - Sistem Pengarsipan Surat</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <?php if ($base != ''): ?>
    <link rel="stylesheet" href="<?= $base ?>assets/css/module.css">
    <?php endif; ?>
    <!-- layout.css dipanggil TERAKHIR agar aturan layout tidak ditimpa module.css -->
    <link rel="stylesheet" href="<?= $base ?>assets/layout.css">
</head>
<body>

<div class="app">

    <a class="logo" href="<?= $base ?>dashboard.php">
        <i class="fa-solid fa-envelope-open-text"></i>
        <span>Pusat Data</span>
    </a>

    <!-- ===== TOPBAR ===== -->
    <header class="topbar">
        <span class="halo">Halo, <b><?= htmlspecialchars($_SESSION['username']) ?></b></span>
        <span class="peran"><?= htmlspecialchars($_SESSION['role']) ?></span>
        <span class="avatar"><?= htmlspecialchars($inisial) ?></span>
    </header>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">
        <?php include __DIR__ . '/menu.php'; ?>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main">
