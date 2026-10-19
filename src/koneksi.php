<?php
$koneksi =mysqli_connect("localhost","root","","db_pengarsipan_surat");
if(mysqli_connect_errno()){
    echo "koneksi databse gagal :".mysqli_connect_error();
}
?>