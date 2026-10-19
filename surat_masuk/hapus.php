<?php
require '../src/cek_session.php';
require '../src/koneksi.php';

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM surat_masuk WHERE id_surat_masuk='$id'")
    or die("Gagal hapus. Kemungkinan surat ini masih dipakai di Disposisi/Surat Keluar/Arsip, hapus dulu data terkait itu. Detail: " . mysqli_error($koneksi));

header("location: index.php");
exit;
