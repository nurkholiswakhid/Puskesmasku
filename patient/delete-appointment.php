<?php
session_start();

// Tidak perlu login untuk menghapus janji temu, tetapi tetapkan tipe pengguna sebagai 'a' (admin) jika tidak ada pengguna yang masuk
$_SESSION['usertype'] = isset($_SESSION['usertype']) ? $_SESSION['usertype'] : 'a';

if($_GET){
    //import database
    include("../connection.php");
    $id=$_GET["id"];
    
    // Hapus janji temu dengan id tertentu dari database
    $sql = "DELETE FROM appointment WHERE appoid='$id'";
    $result = $database->query($sql);

    // Redirect kembali ke halaman appointment.php setelah penghapusan berhasil
    header("location: appointment.php");
}
?>