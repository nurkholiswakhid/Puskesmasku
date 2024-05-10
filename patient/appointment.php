<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Appointments</title>
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
session_start();

if(isset($_SESSION["user"])){
    if($_SESSION["user"] == "" || $_SESSION['usertype'] != 'p'){
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}

//import database
include("../connection.php");
$sqlmain = "SELECT * FROM patient WHERE pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$userrow = $stmt->get_result();
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

$sqlmain = "SELECT appointment.appoid, appointment.status, schedule.scheduleid, schedule.title, doctor.docname, patient.pname, schedule.scheduledate, schedule.scheduletime, appointment.apponum, appointment.appodate FROM schedule INNER JOIN appointment ON schedule.scheduleid = appointment.scheduleid INNER JOIN patient ON patient.pid = appointment.pid INNER JOIN doctor ON schedule.docid = doctor.docid  WHERE patient.pid = $userid ";

if($_POST){
    if(!empty($_POST["sheduledate"])){
        $sheduledate = $_POST["sheduledate"];
        $sqlmain .= " AND schedule.scheduledate='$sheduledate' ";
    };
}

$sqlmain .= " ORDER BY appointment.appodate  ASC";
$result = $database->query($sqlmain);
?>
<div class="container">
    <div class="menu">
        <table class="menu-container" border="0">
            <tr>
                <td style="padding:10px" colspan="2">
                    <table border="0" class="profile-container">
                        <tr>
                            <td width="30%" style="padding-left:20px">
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
                <td class="menu-btn menu-icon-home ">
                    <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></a></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-doctor ">
                    <a href="doctors.php" class="non-style-link-menu "><div><p class="menu-text">Dokter</p></a></div>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-session">
                    <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Jadwal Praktek</p></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-appoinment-active">
                    <a href="appointment.php" class="non-style-link-menu-active"><div><p class="menu-text">Antrian</p></a></div>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-settings">
                    <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Pengaturan</p></a></div>
                </td>
            </tr>
        </table>
    </div>
    <div class="dash-body">
        <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:12px; ">
            <tr>
                <td width="13%">
                    <button onclick="window.location.href='index.php'" class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Kembali</font></button>
                </td>
                <td>
                <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Riwayat Janji Temu Saya</p>
                </td>
                <td width="15%">
                    <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">Tanggal</p>
                    <p class="heading-sub12" style="padding: 0;margin: 0;">
                        <?php 
                            date_default_timezone_set('Asia/Jakarta');
                            $today = date('Y-m-d');
                            echo $today;
                        ?>
                    </p>
                </td>
                <td width="10%">
                    <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top:10px;width: 100%;" >
                    <center>
                        <table class="filter-container" border="0" >
                            <tr>
                                <td width="10%"></td> 
                                <td width="5%" style="text-align: center;">Tanggal:</td>
                                <td width="30%">
                                    <form action="" method="post">
                                        <input type="date" name="sheduledate" id="date" class="input-text filter-container-items" style="margin: 0;width: 95%;">
                                </td>
                                <td width="12%">
                                    <input type="submit"  name="filter" value=" Filter" class=" btn-primary-soft btn button-icon btn-filter"  style="padding: 15px; margin :0;width:100%">
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </center>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top:0px;width: 100%;" >
                    <center>
                        <table width="93%" class="sub-table scrolldown" border="0" style="border:none">
                            <tbody>
                                <?php
                                    if($result->num_rows == 0){
                                        echo '<tr>
                                            <td colspan="7">
                                                <br><br><br><br>
                                                <center>
                                                    <img src="../img/notfound.svg" width="25%">
                                                    <br>
                                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Kami tidak menemukan apa pun yang terkait dengan kata kunci Anda!</p>
                                                    <a class="non-style-link" href="appointment.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Tampilkan Semua Janji Temu &nbsp;</font></button></a>
                                                </center>
                                                <br><br><br><br>
                                            </td>
                                        </tr>';
                                    } else {
                                        for ($x = 0; $x < $result->num_rows; $x++){
                                            echo "<tr>";
                                            for ($q = 0; $q < 3; $q++){
                                                $row = $result->fetch_assoc();
                                                if (!isset($row)){
                                                    break;
                                                };
                                                $scheduleid = $row["scheduleid"];
                                                $title = $row["title"];
                                                $docname = $row["docname"];
                                                $scheduledate = $row["scheduledate"];
                                                $scheduletime = $row["scheduletime"];
                                                $apponum = $row["apponum"];
                                                $appodate = $row["appodate"];
                                                $appoid = $row["appoid"];

                                                if($scheduleid == ""){
                                                    break;
                                                }

                                                echo '
                                                <td style="width: 25%;">
                                                    <div class="dashboard-items search-items">
                                                        <div style="width:100%;">
                                                            <div class="h3-search">
                                                                Tanggal janian: '.substr($appodate,0,30).'<br>
                                                                Kode Booking: OC-000-'.$appoid.'
                                                            </div>
                                                            <div class="h1-search">
                                                                '.substr($title,0,21).'<br>
                                                                <div>
                                                                    <div class="h3-search">
                                                                        No Antrian:<div class="h1-search">0'.$apponum.'</div>
                                                                    </div>
                                                                    <div class="h3-search">
                                                                        ' . substr($docname, 0, 30) . '
                                                                    </div>
                                                                    <div class="h4-search">
                                                                        Tanggal: ' . $scheduledate . '<br>Jam: <b>' . substr($scheduletime, 0, 5) . '</b> 
                                                                    </div>
                                                                    <div class="h4-search">
                                                                        Status: ' . $row["status"] . '
                                                                    </div>
                                                                    <br>
                                                                    <a href="?action=drop&id=' . $appoid . '&title=' . $title . '&doc=' . $docname . '" ><button  class="login-btn btn-primary-soft btn "  style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Batalkan Janjian</font></button></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>';
                                            }
                                            echo "</tr>";
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </center>
                </td> 
            </tr>
        </table>
    </div>
</div>
<?php
if($_GET){
    $id = $_GET["id"];
    $action = $_GET["action"];
    if($action == 'booking-added'){
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <br><br>
                    <h2>Janji Temu Dengan Dokter <br> Berhasil Dibuat</h2>
                    <a class="close" href="appointment.php">&times;</a>
                    <div class="content">
                        Nomer Antrian Anda '.$id.'.<br><br>
                    </div>
                    <div style="display: flex;justify-content: center;">
                        <a href="appointment.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                        <br><br><br><br>
                    </div>
                </center>
            </div>
        </div>
        ';
    } elseif($action == 'drop'){
        $title = $_GET["title"];
        $docname = $_GET["doc"];
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <a class="close" href="appointment.php">&times;</a>
                    <h2><br>Apakah Anda Yakin?</h2>
                    <div class="content">                            
                        Apakah Anda ingin membatalkan janji temu ini?<br><br>
                        Jadwal: &nbsp;<b>'.substr($title,0,40).'</b><br>
                        Dokter name&nbsp; : <b>'.substr($docname,0,40).'</b><br><br>
                    </div>
                    <div style="display: flex;justify-content: center;">
                        <a href="delete-appointment.php?id='.$id.'" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;Iya&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                        <a href="appointment.php" class="non-style-link"><button  class="btn-primary btn"  style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;Tidak&nbsp;&nbsp;</font></button></a>
                    </div>
                </center>
            </div>
        </div>
        '; 
    }
}
?>
<script>
    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>
