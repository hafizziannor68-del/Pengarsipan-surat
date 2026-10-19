<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM arsip WHERE id_arsip='$id'")
    or die(mysqli_error($koneksi));

header("location: index.php");
exit;
