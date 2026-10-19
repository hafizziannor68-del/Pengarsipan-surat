<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM surat_keluar WHERE id_surat_keluar='$id'")
    or die("Gagal hapus. Kemungkinan surat ini masih dipakai di Arsip, hapus dulu data arsip terkait. Detail: " . mysqli_error($koneksi));

header("location: index.php");
exit;
