<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

if ($_SESSION['role'] != 'admin') {
    die("Anda tidak punya akses ke halaman ini.");
}

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM users WHERE id_user='$id'")
    or die(mysqli_error($koneksi));

header("location: index.php");
exit;
