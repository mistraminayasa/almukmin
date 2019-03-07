<?php 
session_start();
error_reporting(0);
include "../config/koneksi.php"; 
include "../config/fungsi_indotgl.php"; 
$s = mysql_fetch_array(mysql_query("SELECT * FROM rb_identitas_sekolah ORDER BY id_identitas_sekolah DESC LIMIT 1"));
$si = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa where nisn='$_GET[id]'"));
?>
<html>
<head>
<title>Hal 1 - Raport Siswa</title>
<link rel="stylesheet" href="../bootstrap/css/printer.css">
<style type="text/css">
  td { padding:3px; }
</style>
</head>
<body onload="window.print()">
    <center><img width='170px' src='logo.png'></center><br>
    <h1 align=center>LAPORAN HASIL BELAJAR SISWA <br>SEKOLAH MENENGAH KEJURUAN <br> (SMK)</h1><br><br><br><br><br>

    <table border=0 style='font-size:23px; padding:0px 40px' width='100%'>
        <tr><td width='200px'>Nama Peserta Didik</td>   <td width='10px'> : </td><td> <?php echo "$si[nama]"; ?></td></tr>
        <tr><td>No Induk</td>             <td width='10px'> : </td><td> <?php echo "$si[nipd]"; ?></td></tr>
        <tr><td>Nama Sekolah</td>         <td width='10px'> : </td><td> <?php echo "$s[nama_sekolah]"; ?></td></tr>
        <tr><td>NSS</td>                  <td width='10px'> : </td><td> <?php echo "$s[nss]"; ?></td></tr>
        <tr><td>Alamat Sekolah</td>       <td width='10px'> : </td><td> <?php echo "$s[alamat_sekolah]"; ?></td></tr>
        <tr><td></td>                     <td width='10px'>   </td><td> <?php echo "Kode Pos $s[kode_pos], Telp. $s[no_telpon]"; ?></td></tr>
        <tr><td>Desa/Kelurahan</td>       <td width='10px'> : </td><td> <?php echo "$s[kelurahan]"; ?></td></tr>
        <tr><td>Kecamatan/Distrik</td>    <td width='10px'> : </td><td> <?php echo "$s[kecamatan]"; ?></td></tr>
        <tr><td>Kabupaten/Kota</td>       <td width='10px'> : </td><td> <?php echo "$s[kabupaten_kota]"; ?></td></tr>
        <tr><td>Provinsi</td>             <td width='10px'> : </td><td> <?php echo "$s[provinsi]"; ?></td></tr>
    </table>
</body>
</html>