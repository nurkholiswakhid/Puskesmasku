<?php
session_start();

if(isset($_SESSION["user"])) {
    if($_SESSION["user"] == "" or $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
        exit(); // Exit after redirection
    }
} else {
    header("location: ../login.php");
    exit(); // Exit after redirection
}

if($_POST) {
    // Import database
    include("../connection.php");

    $sname = $_POST["sname"];
    $sql = "INSERT INTO specialties (sname) VALUES ('$sname')";
    $result = $database->query($sql);
    
    if ($result) {
        // Redirect to doctors.php with success message
        header("Location: doctors.php?success=true");
        exit();
    } else {
        // Redirect to doctors.php with failure message
        header("Location: doctors.php?success=false");
        exit();
    }
}
?>
