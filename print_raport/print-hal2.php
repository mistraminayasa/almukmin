<?php 
session_start();
error_reporting(0);
include "../config/koneksi.php"; 
include "../config/fungsi_indotgl.php"; 
$s = mysql_fetch_array(mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                        LEFT JOIN rb_agama c ON a.id_agama=c.id_agama where a.nisn='$_GET[id]'"));

?>
<html>
<head>
<title>Hal 2 - Raport Siswa</title>
<link rel="stylesheet" href="../bootstrap/css/printer.css">
<style type="text/css">
  td { padding:2px; }
</style>
</head>
<body onload="window.print()">
    <h1 align=center>IDENTITAS SISWA</h1><br>

    <table style='font-size:15px' width='100%'>
        <tr><td width='25px'>1.</td>  <td width='190px'>Nama Siswa (Lengkap) </td>   <td width='10px'> : </td><td> <?php echo "$s[nama]"; ?></td></tr>
        <tr><td>2.</td>  <td width='190px'>Nomor Induk</td>                          <td width='10px'> : </td><td> <?php echo "$s[nipd]"; ?></td></tr>
        <tr><td>3.</td>  <td width='190px'>Tempat danTanggal Lahir</td>                      <td width='10px'> : </td><td> <?php echo "$s[tempat_lahir], ".tgl_raport($s[tanggal_lahir]); ?></td></tr>
        <tr><td>4.</td>  <td width='190px'>Jenis Kelamin</td>                             <td width='10px'> : </td><td> <?php echo "$s[jenis_kelamin]"; ?></td></tr>
        <tr><td>5.</td>  <td width='190px'>Agama</td>                                     <td width='10px'> : </td><td> <?php echo "$s[nama_agama]"; ?></td></tr>
        <tr><td>6.</td>  <td width='190px'>Anak ke</td>                                   <td width='10px'> : </td><td> <?php echo "-"; ?></td></tr>
        <tr><td>7.</td>  <td width='190px'>Status dalam Keluarga</td>                     <td width='10px'> : </td><td> <?php echo "Anak Kandung"; ?></td></tr>
        <tr><td>8.</td>  <td width='190px'>Alamat Siswa</td>                      <td width='10px'> : </td><td> <?php echo "$s[alamat]"; ?></td></tr>
        <tr><td></td>  <td width='190px'>Telepon</td>                       <td width='10px'> : </td><td> <?php echo "$s[telepon]"; ?></td></tr>
        
        <tr><td>9.</td> <td width='190px'>Diterima di sekolah ini</td>                   <td width='10px'> </td><td> <?php echo ""; ?></td></tr>
        <tr><td></td> <td width='190px'>a.Di Kelas</td>                                     <td width='10px'> : </td><td> <?php echo "$s[kode_kelas]"; ?></td></tr>
        <tr><td></td> <td width='190px'>b.Pada Tanggal</td>                                 <td width='10px'> : </td><td> <?php echo tgl_raport(date('Y-m-d')); ?></td></tr>
        <tr><td></td> <td width='190px'>c.Semester</td>                                 <td width='10px'> : </td><td> <?php echo tgl_raport(date('Y-m-d')); ?></td></tr>
        
        <tr><td>10.</td> <td width='190px'>Sekolah Asal</td>                    <td width='10px'> </td><td></td></tr>
        <tr><td></td> <td width='190px'>a.Nama Sekolah</td>                    <td width='10px'> : </td><td> <?php echo ""; ?></td></tr>
        <tr><td></td> <td width='190px'>b.Alamat</td>                    <td width='10px'> : </td><td> <?php echo ""; ?></td></tr>

        <tr><td>11.</td> <td width='190px'>Ijazah SMP/MTs/Paket B</td>                    <td width='10px'> </td><td></td></tr>
        <tr><td></td> <td width='190px'>a.Tahun</td>                    <td width='10px'> : </td><td> <?php echo ""; ?></td></tr>
        <tr><td></td> <td width='190px'>b.Nomor</td>                    <td width='10px'> : </td><td> <?php echo ""; ?></td></tr>

        <tr><td>12.</td> <td width='190px'>Orang Tua</td>                                 <td width='10px'> </td><td></td></tr>
        <tr><td></td> <td width='190px'>a. Nama Ayah</td>                                 <td width='10px'> : </td><td> <?php echo "$s[nama_ayah]"; ?></td></tr>
        <tr><td></td> <td width='190px'>b. Nama Ibu</td>                                  <td width='10px'> : </td><td> <?php echo "$s[nama_ibu]"; ?></td></tr>
        
        <tr><td>13</td> <td width='190px'>Alamat Orang Tua</td>                                    <td width='10px'> : </td><td> <?php echo "$s[alamat]"; ?></td></tr>
        <tr><td></td> <td width='190px'>Telepon</td>                          <td width='10px'> : </td><td> <?php echo "$s[no_telpon_ayah]"; ?></td></tr>
        
        <tr><td>14.</td> <td width='190px'>Pekerjaan Orang Tua</td>                       <td width='10px'> </td><td></td></tr>
        <tr><td></td> <td width='190px'>a. Pekerjaan Ayah</td>                                      <td width='10px'> : </td><td> <?php echo "$s[pekerjaan_ayah]"; ?></td></tr>
        <tr><td></td> <td width='190px'>b. Pekerjaan Ibu</td>                                       <td width='10px'> : </td><td> <?php echo "$s[pekerjaan_ibu]"; ?></td></tr>
        
        <tr><td>15.</td> <td width='190px'>Nama Wali</td>                                 <td width='10px'> : </td><td> <?php echo "$s[nama_wali]"; ?></td></tr>
        <tr><td>16.</td> <td width='190px'>Alamat Wali</td>                                    <td width='10px'> : </td><td> <?php echo "$s[alamat]"; ?></td></tr>
        <tr><td></td> <td width='190px'>Telepon</td>                             <td width='10px'> : </td><td> <?php echo "-"; ?></td></tr>
        <tr><td>17.</td> <td width='190px'>Pekerjaan Wali</td>                                 <td width='10px'> : </td><td> <?php echo "$s[pekerjaan_wali]"; ?></td></tr>
    </table>
    <br><br><br>
    <table border=0 width='70%' style='float:right'>
        <tr><td><?php if (trim($s[foto])==''){
                                  echo "<img style='width:95px; padding:3px; border:1px solid #000' src='../foto_siswa/no-image.jpg'>";
                                }else{
                                  echo "<img style='width:95px; padding:3px; border:1px solid #000' src='../foto_siswa/$s[foto]'>";
                                } ?></td>
            <td width='55%'>Semarang, <?php echo tgl_raport(date('Y-m-d')); ?> <br>
                Kepala SMK IT Robbi Rodliyya,<br><br><br><br>


                <b>Faqihuddin Habibullah, S.S, S.Kom, M.Si<br>
                NIP : 1966 1231 199702 1 022</b></td></tr>
    </table>
</body>
</html>