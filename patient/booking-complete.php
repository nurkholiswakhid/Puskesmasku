<?php
    session_start();

    // Memeriksa apakah pengguna sudah login dan memiliki akses sebagai pasien
    if(isset($_SESSION["user"])) {
        if($_SESSION["user"] == "" or $_SESSION['usertype'] != 'p') {
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }

    // Mengimpor koneksi ke database
    include("../connection.php");

    // Mengambil informasi pengguna dari database
    $sqlmain = "SELECT * FROM patient WHERE pemail=?";
    $stmt = $database->prepare($sqlmain);
    $stmt->bind_param("s", $useremail);
    $stmt->execute();
    $userrow = $stmt->get_result();
    $userfetch = $userrow->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];

    // Memproses permintaan buat janji
    if($_POST){
        if(isset($_POST["booknow"])){
            $apponum = $_POST["apponum"];
            $scheduleid = $_POST["scheduleid"];
            $date = $_POST["date"];

            // Memasukkan status "Menunggu" ke dalam database
            $status = "Menunggu";
            $sql2 = "INSERT INTO appointment (pid, apponum, scheduleid, appodate, status) VALUES (?, ?, ?, ?, ?)";
            $stmt = $database->prepare($sql2);
            $stmt->bind_param("iiiss", $userid, $apponum, $scheduleid, $date, $status);
            $stmt->execute();

            // Memeriksa apakah penambahan berhasil sebelum mengarahkan pengguna
            if($stmt->affected_rows > 0) {
                header("location: appointment.php?action=booking-added&id=".$apponum."&titleget=none");
            } else {
                // Handle kesalahan jika penambahan gagal
                echo "Gagal menambahkan janji. Silakan coba lagi.";
            }
        }
    }
?>
