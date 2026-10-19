<?php
// Penerima disposisi (atau admin) mengubah status: menunggu -> diproses -> selesai
require '../src/cek_session.php';
require '../src/koneksi.php';

$id = (int)$_GET['id'];
$status = $_GET['status'];

if (!in_array($status, ['diproses', 'selesai'])) {
    die("Status tidak valid.");
}

$cek = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_user_tujuan FROM disposisi WHERE id_disposisi='$id'"));
if (!$cek || ($_SESSION['role'] != 'admin' && $cek['id_user_tujuan'] != $_SESSION['id_user'])) {
    die("Anda tidak punya akses untuk menindaklanjuti disposisi ini.");
}

mysqli_query($koneksi, "UPDATE disposisi SET status='$status' WHERE id_disposisi='$id'")
    or die(mysqli_error($koneksi));

header("location: index.php");
exit;
