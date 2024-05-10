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
session_start();

if(isset($_SESSION["user"])) {
    if($_SESSION["user"] == "" || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}

include("../connection.php");

$sqlmain = "SELECT * FROM patient WHERE pemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s", $useremail);
$stmt->execute();
$result = $stmt->get_result();
$userfetch = $result->fetch_assoc();
$userid = $userfetch["pid"];
$username = $userfetch["pname"];

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
                                <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
                                <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="menu-row" >
                <td class="menu-btn menu-icon-home " >
                    <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></a></div></a>
                </td>
            </tr>
            <tr class="menu-row">
                <td class="menu-btn menu-icon-doctor">
                    <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">Dokter</p></a></div>
                </td>
            </tr>
            <tr class="menu-row" >
                <td class="menu-btn menu-icon-session-active">
                    <a href="schedule.php" class="non-style-link-menu-active"><div><p class="menu-text">Jadwal Praktek</p></div></a>
                </td>
            </tr>
            <tr class="menu-row" >
                <td class="menu-btn menu-icon-appoinment">
                    <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Antrian</p></a></div>
                </td>
            </tr>
            <tr class="menu-row" >
                <td class="menu-btn menu-icon-settings">
                    <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Pengaturan</p></a></div>
                </td>
            </tr>
        </table>
    </div>
    <?php
    $sqlmain = "SELECT schedule.*, doctor.docname, specialties.sname 
                FROM schedule 
                INNER JOIN doctor ON schedule.docid=doctor.docid 
                INNER JOIN specialties ON doctor.specialties=specialties.id 
                WHERE schedule.scheduledate>='$today' 
                ORDER BY schedule.scheduledate ASC";
    $insertkey = "";
    $searchtype = "Semua";

    if($_POST) {
        if(!empty($_POST["search"])) {
            $keyword = $_POST["search"];
            $sqlmain = "SELECT schedule.*, doctor.docname, specialties.sname 
                        FROM schedule 
                        INNER JOIN doctor ON schedule.docid=doctor.docid 
                        INNER JOIN specialties ON doctor.specialties=specialties.id 
                        WHERE schedule.scheduledate>='$today' 
                        AND (doctor.docname='$keyword' OR doctor.docname LIKE '$keyword%' OR doctor.docname LIKE '%$keyword' OR doctor.docname LIKE '%$keyword%' OR schedule.title='$keyword' OR schedule.title LIKE '$keyword%' OR schedule.title LIKE '%$keyword' OR schedule.title LIKE '%$keyword%' OR schedule.scheduledate LIKE '$keyword%' OR schedule.scheduledate LIKE '%$keyword' OR schedule.scheduledate LIKE '%$keyword%' OR schedule.scheduledate='$keyword') 
                        ORDER BY schedule.scheduledate ASC";
            $insertkey = $keyword;
            $searchtype = "hasil penelusuran: ";
        }
    }

    $result = $database->query($sqlmain);
    ?>
    <div class="dash-body">
        <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
            <tr>
            <td width="13%">
                <button onclick="window.location.href='index.php'" class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Kembali</font></button>
            </td>
                <td>
                    <form action="" method="post" class="header-search">
                        <input type="search" name="search" class="input-text header-searchbar" placeholder="Cari Nama Dokter, Email, atau Tanggal (YYYY-MM-DD)" list="doctors" value="<?php echo $insertkey ?>">&nbsp;&nbsp;
                        <datalist id="doctors">
                            <?php
                            $list11 = $database->query("SELECT DISTINCT * FROM doctor;");
                            $list12 = $database->query("SELECT DISTINCT * FROM schedule GROUP BY title;");

                            for ($y = 0; $y < $list11->num_rows; $y++) {
                                $row00 = $list11->fetch_assoc();
                                $d = $row00["docname"];
                                echo "<option value='$d'><br/>";
                            }

                            for ($y = 0; $y < $list12->num_rows; $y++) {
                                $row00 = $list12->fetch_assoc();
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
                    <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                        date_default_timezone_set('Asia/Jakarta');
                        $date = date('Y-m-d');
                        echo $date;
                        ?>
                        </p>
                        </td>
                        <td width="10%">
                            <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                        </td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top:10px;width: 100%;">
                    <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)"><?php echo $searchtype." Jadwal"."(".$result->num_rows.")"; ?></p>
                    <p class="heading-main12" style="margin-left: 45px;font-size:22px;color:rgb(49, 49, 49)"><?php echo $insertkey; ?></p>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <center>
                        <div class="abc scroll">
                            <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                                <tbody>
                                <?php
                                if($result->num_rows == 0) {
                                    echo '<tr>
                                              <td colspan="4">
                                                  <br><br><br><br>
                                                  <center>
                                                      <img src="../img/notfound.svg" width="25%">
                                                      <br>
                                                      <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Kami tidak menemukan apa pun yang terkait dengan kata kunci Anda!</p>
                                                      <a class="non-style-link" href="schedule.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Tampilkan Semua Jadwal &nbsp;</button></a>
                                                  </center>
                                                  <br><br><br><br>
                                              </td>
                                          </tr>';
                                } else {
                                    while ($row = $result->fetch_assoc()) {
                                        $scheduleid = $row["scheduleid"];
                                        $title = $row["title"];
                                        $docname = $row["docname"];
                                        $scheduledate = $row["scheduledate"];
                                        $scheduletime = $row["scheduletime"];
                                        $specialty = $row["sname"];
                                        
                                        echo '<tr>';
                                        echo '<td style="width: 25%;">
                                                <div class="dashboard-items search-items">
                                                    <div style="width:100%">
                                                        <div class="h1-search">
                                                            Jadwal '.substr($title, 0, 21).'
                                                        <div>
                                                        <div class="h3-search">
                                                            <b>'.substr($docname, 0, 30).'</b>
                                                        </div>
                                                        <div class="h3-search">
                                                            Spesialisasi: '.$specialty.'
                                                        </div>
                                                        <div class="h4-search">
                                                            '.$scheduledate.'<br>Mulai: <b>'.substr($scheduletime, 0, 5).'</b>
                                                        <div>
                                                       
                                                       </div>   
                                                        <a href="booking.php?id='.$scheduleid.'"><button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Buat Janji</font></button></a>
                                                    </div>
                                                </div>
                                            </td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </center>
                </td> 
            </tr>
        </table>
    </div>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>
