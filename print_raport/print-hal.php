<?php 
session_start();
error_reporting(0);
include "../config/koneksi.php"; 
include "../config/fungsi_indotgl.php"; 
$s = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_GET[id]'"));
?>
<html>
<head>
<title>Cover Raport Siswa</title>
<link rel="stylesheet" href="../bootstrap/css/printer.css">
</head>
<body onload="window.print()">
    <center>
        <img width='170px' src='logo.png'><br><br><br>
        <h1 align=center>LAPORAN HASIL BELAJAR SISWA <br>SEKOLAH MENENGAH KEJURUAN</h1><br><br><br>
        <h1>SMK IT Robbi Rodliyya</h1>
        Alamat Sekolah : Jalan Woltermomginsidi No. 59 Semarang<br><br><br>
        <img width='170px' height='150px' src='sma_logo.jpg'><br><br><br><br><br>

        
        <table>
        	<tr><td width='140px'>Nama Peserta Didik</td><td> : <?php echo $s[nama]; ?></td></tr>
        	<tr><td>No Induk</td><td> : <?php echo "$s[nipd]"; ?></td></tr>
        </table><br><br><br><br>

        <p style='font-size:22px; font-weight:bold'>DEPARTEMEN PENDIDIKAN NASIONAL <br>REPUBLIK INDONESIA</p>
    </center>
</body>
</html>