<?php 
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=export-ledger-kelas-$_GET[id]-mapel-$_GET[kd].xls");
session_start();
error_reporting(0);
include "config/koneksi.php"; 
include "config/fungsi_indotgl.php"; 
$d = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran a 
                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
                              JOIN rb_kelas c ON a.kode_kelas=c.kode_kelas where a.kodejdwl='$_GET[jdwl]'")); 
if (substr($_GET[tahun],4,5)=='1'){ $semester = 'GANJIL'; }else{ $semester = 'GENAP'; }
?>
<head>
<title>Data Siswa Kelas <?php echo $_GET[kelas]; ?></title>
</head>
<body onload="window.print()">
<center><table border=0>
  <tr><td colspan='12'><center><h1>PONDOK PESANTREN AL-MU'MINIEN</h1></center></td></tr>
  <tr><td colspan='12'><center>LEDGER NILAI SEMESTER <?php echo $semester; ?></center></td></tr>
  <tr><td colspan='12'><center>MATA PELAJARAN <?php echo "<span style='text-transform:uppercase'>$d[namamatapelajaran]</span>"; ?></center></td></tr>
  <tr><td colspan='12'><center><?php echo "<span style='text-transform:uppercase'>$d[nama_kelas]</span>"; ?></center></td></tr>
</table></center><br>
<?php
            echo " <table width='100%' border=1>
                    <thead>
                      <tr>
                        <th rowspan='3'>No</th>
                        <th rowspan='3'>NIS</th>
                        <th rowspan='3'>Nama Lengkap</th>
                        <th colspan='9' style='text-transform:uppercase'><center>$d[namamatapelajaran]</center></th>
                      </tr>

                      <tr>
                        <th colspan='2'><center>KKM</center></th>
                        <th><center>$d[kkm]</center></th>
                        <th rowspan=2><center>Deskripsi Ketercapaian Kognitif/Pengetahuan </center></th>
                        <th rowspan=2><center>Deskripsi Psikomotorik/Keterampilan</center></th>
                        <th rowspan=2><center>Deskripsi Afektif/Sikap</center></th>
                        <th colspan='3'><center>Kehadiran</center></th>
                      </tr>

                      <tr>
                        <th><center>Kog</center></th>
                        <th><center>Psiko</center></th>
                        <th><center>Afektif</center></th>

                        <th><center>Alpa</center></th>
                        <th><center>Ijin</center></th>
                        <th><center>Sakit</center></th>
                      </tr>


                    </thead>
                    <tbody>";
                    $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    $rapnk = mysql_fetch_array(mysql_query("SELECT sum(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/count(nisn) as raport FROM rb_nilai_keterampilan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                    $des = mysql_fetch_array(mysql_query("SELECT * FROM rb_ledger where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kelas='$_GET[id]'"));   
                    $i = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kehadiran='I'"));
                    $s = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kehadiran='S'"));
                    $a = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kehadiran='A'"));
                    
                    $rapn = mysql_fetch_array(mysql_query("SELECT sum(nilai1)/count(nisn) as uh, sum(nilai2)/count(nisn) as tugas FROM rb_nilai_pengetahuan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                    $sms = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pengetahuan_semester where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                    $nilai_raport = (($rapn[uh]+$rapn[tugas]+$sms[nilai1])+(2*$sms[nilai2]))/4;   
                    echo "<tr>";
                              echo "<td>$no</td>
                              <td>$r[nipd]</td>
                              <td>$r[nama]</td>
                              <td align=center>".number_format($nilai_raport)."</td>
                              <td align=center>".number_format($rapnk[raport])."</td>
                              <td align=center>$des[sikap]</td>
                              <td>$des[desk_pengetahuan]</td>
                              <td>$des[desk_keterampilan]</td>
                              <td>$des[desk_sikap]</td>
                              <td align=center>$a</td>
                              <td align=center>$i</td>
                              <td align=center>$s</td>
                          </tr>";
                      $no++;
                      }

                  ?>
                    </tbody>
                  </table>

<table border=0 width=100%>
  <tr><td colspan='12'></td></tr>
  <tr>
    <td colspan='8' width="520"align="center"></td>
    <td colspan='4' width="260" align="left">Lohbener, <?php echo tgl_raport(date("Y-m-d")); ?> <br> Guru Mapel</td>
  </tr>
  <tr>
    <td colspan='8' align="center" valign="top"></td>
    <td colspan='4' align="left" valign="top"><br /><br /><br /><br /><br />
      <b>Nama Guru<br />
      Nip Guru</b>
    </td>
  </tr>
</table> 
</body>