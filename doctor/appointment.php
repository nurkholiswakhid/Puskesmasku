<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
    <title>Janji</title>
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
            if(($_SESSION["user"])=="" or $_SESSION['usertype']!='d'){
                header("location: ../login.php");
            } else {
                $useremail=$_SESSION["user"];
            }
        } else {
            header("location: ../login.php");
        }

        include("../connection.php");
        $userrow = $database->query("select * from doctor where docemail='$useremail'");
        $userfetch=$userrow->fetch_assoc();
        $userid= $userfetch["docid"];
        $username=$userfetch["docname"];
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
                    <td class="menu-btn menu-icon-dashbord " >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Dasbor</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-icon-appoinment  menu-active menu-icon-appoinment-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Janji Saya</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-session">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Sesi Saya</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-patient">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">Pasien Saya</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-icon-settings">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Pengaturan</p></a></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%" >
                        <a href="appointment.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Kembali</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Pengelolaan Janji</p>      
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Tanggal
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                                date_default_timezone_set('Asia/Jakarta');
                                $today = date('Y-m-d');
                                echo $today;
                                $list110 = $database->query("select * from schedule inner join appointment on schedule.scheduleid=appointment.scheduleid inner join patient on patient.pid=appointment.pid inner join doctor on schedule.docid=doctor.docid  where  doctor.docid=$userid ");
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                        <center>
                            <table class="filter-container" border="0" >
                                <tr>
                                    <td width="10%">
                                    </td> 
                                    <td width="5%" style="text-align: center;">
                                        Tanggal:
                                    </td>
                                    <td width="30%">
                                        <form action="" method="post">
                                            <input type="date" name="sheduledate" id="date" class="input-text filter-container-items" style="margin: 0;width: 95%;">
                                    </td>
                                    <td width="20%">
                                        <select name="sessionTitle" class="input-text filter-container-items" style="margin: 0;width: 100%;">
                                            <option value="Pilih Judul Sesi">Pilih Judul Sesi</option>
                                            <?php 
                                                $sqlSessionTitles = "SELECT DISTINCT title FROM schedule WHERE docid=$userid";
                                                $resultSessionTitles = $database->query($sqlSessionTitles);
                                                while ($rowSessionTitle = $resultSessionTitles->fetch_assoc()) {
                                                    $title = $rowSessionTitle['title'];
                                                    echo "<option value='$title'>$title</option>";
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td width="15%">
                                        <select name="status" class="input-text filter-container-items" style="margin: 0;width: 100%;">
                                            <option value="">Semua Status</option>
                                            <option value="Selesai">Selesai</option>
                                            <option value="Menunggu">Menunggu</option>
                                        </select>
                                    </td>
                                    <td width="12%">
                                        <input type="submit" name="filter" value="Filter" class="btn-primary-soft btn button-icon btn-filter" style="padding: 15px; margin: 0; width: 100%;">
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        </center>
                    </td>
                </tr>
                <?php
                    $sqlmain = "SELECT appointment.appoid, schedule.scheduleid, schedule.title, doctor.docname, patient.pname, schedule.scheduledate, schedule.scheduletime, appointment.apponum, appointment.appodate, appointment.status FROM schedule INNER JOIN appointment ON schedule.scheduleid=appointment.scheduleid INNER JOIN patient ON patient.pid=appointment.pid INNER JOIN doctor ON schedule.docid=doctor.docid WHERE doctor.docid=$userid ";

                    if ($_POST) {
                        if (!empty($_POST["sheduledate"])) {
                            $sheduledate = $_POST["sheduledate"];
                            $sqlmain .= " AND schedule.scheduledate='$sheduledate' ";
                        }
                        if (!empty($_POST["sessionTitle"]) && $_POST["sessionTitle"] != "Pilih Judul Sesi") {
                            $sessionTitle = $_POST["sessionTitle"];
                            $sqlmain .= " AND schedule.title='$sessionTitle' ";
                        }
                        if (!empty($_POST["status"])) {
                            $status = $_POST["status"];
                            $sqlmain .= " AND appointment.status='$status' ";
                        }
                    }
                ?>
                <tr>
                    <td colspan="4">
                        <center>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" border="0">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">
                                                Nama Pasien
                                            </th>
                                            <th class="table-headin">
                                                Nomor Janji
                                            </th>
                                            <th class="table-headin">
                                                Judul Sesi
                                            </th>
                                            <th class="table-headin" >
                                                Tanggal & Waktu Sesi
                                            </th>
                                            <th class="table-headin">
                                                Tanggal Daftar
                                            </th>
                                            <th class="table-headin">
                                                Status
                                            </th>
                                            <th class="table-headin">
                                                Fungsi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $result= $database->query($sqlmain);
                                            if($result->num_rows==0){
                                                echo '<tr>
                                                        <td colspan="7">
                                                            <br><br><br><br>
                                                            <center>
                                                                <img src="../img/notfound.svg" width="25%">
                                                                <br>
                                                                <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Kami tidak menemukan apapun terkait kata kunci Anda!</p>
                                                                <a class="non-style-link" href="appointment.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Tampilkan semua Janji &nbsp;</font></button></a>
                                                            </center>
                                                            <br><br><br><br>
                                                        </td>
                                                    </tr>';
                                            } else {
                                                for ($x=0; $x<$result->num_rows; $x++){
                                                    $row=$result->fetch_assoc();
                                                    $appoid=$row["appoid"];
                                                    $scheduleid=$row["scheduleid"];
                                                    $title=$row["title"];
                                                    $docname=$row["docname"];
                                                    $scheduledate=$row["scheduledate"];
                                                    $scheduletime=$row["scheduletime"];
                                                    $pname=$row["pname"];
                                                    $apponum=$row["apponum"];
                                                    $appodate=$row["appodate"];
                                                    $status=$row["status"];
                                                    echo '<tr >
                                                            <td style="font-weight:600;"> &nbsp;'.
                                                                substr($pname,0,25)
                                                                .'</td >
                                                            <td style="text-align:center;font-size:23px;font-weight:500; color: var(--btnnicetext);">
                                                                '.$apponum.'
                                                            </td>
                                                            <td>
                                                                '.substr($title,0,15).'
                                                            </td>
                                                            <td style="text-align:center;;">
                                                                '.substr($scheduledate,0,10).' '.substr($scheduletime,0,5).'
                                                            </td>
                                                            <td style="text-align:center;">
                                                                '.$appodate.'
                                                            </td>
                                                            <td style="text-align:center;">
                                                                '.$status.'
                                                            </td>
                                                            <td>
                                                                <div style="display:flex;justify-content: center;">
                                                                    <form action="update_status.php" method="post">
                                                                        <input type="hidden" name="appoid" value="'.$appoid.'">
                                                                        <button class="btn-primary-soft btn button-icon btn-finish" style="padding-left: 20px; padding-right: 20px; padding-top: 12px; padding-bottom: 12px; margin-top: 10px;"><span class="tn-in-text" style="color: white; text-align: center; display: block;">Selesai</span></button>
                                                                    </form>
                                                                    &nbsp;&nbsp;&nbsp;
                                                                    <a href="?action=drop&id='.$appoid.'&name='.$pname.'&session='.$title.'&apponum='.$apponum.'" class="non-style-link"><button  class="btn-primary-soft btn button-icon btn-delete"  style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Batal</font></button></a>
                                                                    &nbsp;&nbsp;&nbsp;
                                                                </div>
                                                            </td>
                                                        </tr>';
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
</body>
</html>
