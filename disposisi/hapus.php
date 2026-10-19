<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

$id = (int)$_GET['id'];

// hanya admin atau pembuat disposisi yang boleh menghapus
$cek = mysqli_fetch_array(mysqli_query($koneksi, "SELECT id_user FROM disposisi WHERE id_disposisi='$id'"));
if (!$cek || ($_SESSION['role'] != 'admin' && $cek['id_user'] != $_SESSION['id_user'])) {
    die("Anda tidak punya akses untuk menghapus disposisi ini.");
}

mysqli_query($koneksi, "DELETE FROM disposisi WHERE id_disposisi='$id'")
    or die(mysqli_error($koneksi));

header("location: index.php");
exit;
