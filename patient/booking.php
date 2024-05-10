<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Sessions</title>
    <style>
        .popup {
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table {
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>
<body>
<?php
// Learn from w3schools.com
session_start();

if(isset($_SESSION["user"])) {
    if($_SESSION["user"] == "" or $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}

// Import database
include("../connection.php");

$sqlmain = "SELECT * FROM patient WHERE pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are results
if ($result->num_rows > 0) {
    $userfetch = $result->fetch_assoc();
    $userid = $userfetch["pid"];
    $username = $userfetch["pname"];
} else {
    // Handle the case where no user data is found
    // Redirect to an appropriate page or show an error message
    exit("No user data found");
}

date_default_timezone_set('Asia/Jakarta');

$today = date('Y-m-d');
?>
<div class="container">
    <div class="menu">
        <table class="menu-container" border="0">
            <tr>
                <td style="padding:10px" colspan="2">
                    <table border="0" class="profile-container">
                        <tr>
                            <td width="30%" style="padding-left:20px" >
                                <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                            </td>
                            <td style="padding:0px;margin:0px;">
                                <p class="profile-title"><?php echo substr($username, 0, 13) ?>..</p>
                                <p class="profile-subtitle"><?php echo substr($useremail, 0, 22) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-home">
                    <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-doctor">
                    <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">Dokter</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-session-active">
                    <a href="schedule.php" class="non-style-link-menu-active"><div><p class="menu-text">Jadwal Praktek</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-appoinment">
                    <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Antrian</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-settings">
                    <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Pengaturan</p></div></a>
                </td>
            </tr>
        </table>
    </div>
        
    <div class="dash-body">
        <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
            <tr>
                <td width="13%">
                    <a href="schedule.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Kembali</font></button></a>
                </td>
                <td>
                    <form action="schedule.php" method="post" class="header-search">
                        <input type="search" name="search" class="input-text header-searchbar" placeholder="Cari Nama Dokter, Email, atau Tanggal (YYYY-MM-DD)" list="doctors">&nbsp;&nbsp;
                        <datalist id="doctors">
                            <?php
                            $list11 = $database->query("SELECT DISTINCT * FROM doctor;");
                            $list12 = $database->query("SELECT DISTINCT * FROM schedule GROUP BY title;");

                            while ($row00 = $list11->fetch_assoc()) {
                                $d = $row00["docname"];
                                echo "<option value='$d'><br/>";
                            }

                            while ($row00 = $list12->fetch_assoc()) {
                                $d = $row00["title"];
                                echo "<option value='$d'><br/>";
                            }
                            ?>
                        </datalist>
                        <input type="Submit" value="Cari" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                    </form>
                </td>
                <td width="15%">
                    <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">Tanggal</p>
                    <p class="heading-sub12" style="padding: 0;margin: 0;"><?php echo $today; ?></p>
                </td>
                <td width="10%">
                    <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top:10px;width: 100%;">
                    <!-- <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49);font-weight:400;">Scheduled Sessions / Booking / <b>Review Booking</b></p> -->
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <center>
                        <div class="abc scroll">
                            <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                                <tbody>
                                    <?php
                                    if(isset($_GET["id"])) {
                                        $id = $_GET["id"];
                                        $sqlmain = "SELECT * FROM schedule INNER JOIN doctor ON schedule.docid = doctor.docid WHERE schedule.scheduleid=? ORDER BY schedule.scheduledate DESC";
                                        $stmt = $database->prepare($sqlmain);
                                        $stmt->bind_param("i", $id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();
                                        $scheduleid = $row["scheduleid"];
                                        $title = $row["title"];
                                        $docname = $row["docname"];
                                        $docemail = $row["docemail"];
                                        $scheduledate = $row["scheduledate"];
                                        $scheduletime = $row["scheduletime"];
                                        $sql2 = "SELECT * FROM appointment WHERE scheduleid=$id";
                                        $result12 = $database->query($sql2);
                                        $apponum = ($result12->num_rows) + 1;
                                    ?>
                                        <form action="booking-complete.php" method="post">
                                            <input type="hidden" name="scheduleid" value="<?php echo $scheduleid; ?>">
                                            <input type="hidden" name="apponum" value="<?php echo $apponum; ?>">
                                            <input type="hidden" name="date" value="<?php echo $today; ?>">
                                            <tr>
                                                <td style="width: 50%;" rowspan="2">
                                                    <div class="dashboard-items search-items">
                                                        <div style="width:100%">
                                                        <div class="h1-search" style="font-size:25px;">Jadwal Praktek</div>
                                                        <div class="h3-search" style="font-size:18px;line-height:30px">
                                                            <span class="label">Nama Dokter </span>: <b><?php echo $docname; ?></b><br>
                                                            <span class="label">Alamat Email </span>: <b><?php echo $docemail; ?></b><br>
                                                            <div class="h3-search" style="font-size:18px;">
                                                                <span class="label">Jadwal </span>: <?php echo $title; ?><br>
                                                                <span class="label">Tanggal </span>: <?php echo $scheduledate; ?><br>
                                                                <span class="label">Jam </span>: <?php echo $scheduletime; ?><br>
                                                                <span class="label">Biaya pendaftaran </span>: <b>Rp. 20.000</b>
                                                            </div>
                                                        </div>
                                                            <br>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td style="width: 25%;">
                                                    <div class="dashboard-items search-items">
                                                        <div style="width:100%;padding-top: 15px;padding-bottom: 15px;">
                                                            <div class="h1-search" style="font-size:20px;line-height: 35px;margin-left:8px;text-align:center;">Nomer Antrian</div>
                                                            <center>
                                                                <div class="dashboard-icons" style="margin-left: 0px;width:90%;font-size:70px;font-weight:800;text-align:center;color:var(--btnnictext);background-color: var(--btnice)"><?php echo $apponum; ?></div>
                                                            </center>
                                                        </div>
                                                        <br>
                                                        <br>
                                                        <br>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <input type="Submit" class="login-btn btn-primary btn btn-book" style="margin-left:10px;padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;width:95%;text-align: center;" value="Buat Janji" name="booknow">
                                                </td>
                                            </tr>
                                        </form>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </center>
                </td>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
