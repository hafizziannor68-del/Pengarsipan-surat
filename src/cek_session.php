<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("location: index.php?pesan=gagal");
    exit;
}