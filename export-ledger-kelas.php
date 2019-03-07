<?php 
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=export-ledger-kelas-$_GET[kelas].xls");
session_start();
error_reporting(0);
include "config/koneksi.php"; 
include "config/fungsi_indotgl.php"; 
$d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[kelas]'")); 
if (substr($_GET[tahun],4,5)=='1'){ $semester = 'GANJIL'; }else{ $semester = 'GENAP'; }
?>
<html>
<head>
<title>Data Siswa Kelas <?php echo $_GET[kelas]; ?></title>
<link rel="stylesheet" href="bootstrap/css/printer.css">
</head>
<body onload="window.print()">
<center><table border=0>
  <tr><td colspan='21'><center><h1>SMA NEGERI 1 KURIK <br><span style='font-size:12px'>Jl. Ahmad Yani, Kab. Marauke, Papua</span></h1></center></td></tr>
  <tr><td colspan='21'><center>LEDGER NILAI SEMESTER <?php echo $semester; ?></center></td></tr>
  <tr><td colspan='21'><center><?php echo "<span style='text-transform:uppercase'>$d[nama_kelas]</span>"; ?></center></td></tr>
</table></center><br>
<?php
            echo " <table width='100%' border=1>
                    <thead>
                      <tr>
                        <th rowspan='3'>No</th>
                        <th rowspan='3'>NIS</th>
                        <th rowspan='3'>Nama Lengkap</th>";
                      $jdwl = mysql_query("SELECT * FROM rb_jadwal_pelajaran a JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran where a.kode_kelas='$_GET[kelas]' AND a.id_tahun_akademik='$_GET[tahun]'");
                      while ($j = mysql_fetch_array($jdwl)){
                        echo "<th colspan='3'><center>$j[namamatapelajaran]</center></th>";
                      }
                      echo "<th colspan='4' rowspan='2'>ABSEN</th>
                            <th colspan='4'>Rekap Akhir</th>
                            <th rowspan='3'>Keterangan</th>
                      </tr>


                      <tr>";
                      $jdwl1 = mysql_query("SELECT * FROM rb_jadwal_pelajaran a JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran where a.kode_kelas='$_GET[kelas]' AND a.id_tahun_akademik='$_GET[tahun]'");
                      while ($j = mysql_fetch_array($jdwl1)){
                        echo "<th colspan='2'><center>KKM</center></th>
                              <th><center>$j[kkm]</center></th>";
                      }
                      echo "<th colspan='2'>Analisis</th>
                            <th colspan='2'>Total Nilai</th>
                      </tr>

                      <tr>";
                      $jdwl2 = mysql_query("SELECT * FROM rb_jadwal_pelajaran a JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran where a.kode_kelas='$_GET[kelas]' AND a.id_tahun_akademik='$_GET[tahun]'");
                      while ($j = mysql_fetch_array($jdwl2)){
                        echo "<th><center>K</center></th>
                              <th><center>P</center></th>
                              <th><center>A</center></th>";
                      }
                      echo "<th>S</th>
                            <th>I</th>
                            <th>A</th>
                            <th>JML</th>
                            <th>Jumlah</th>
                            <th>Peringkat</th>
                            <th>K</th>
                            <th>P</th>
                      </tr>
                      <tr>";
                        for ($i = 1; $i < 22; $i++){
                            echo "<th align=center>$i</th>";
                        }
                      echo "</tr>
                    </thead>
                    <tbody>";
                    $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[kelas]' ORDER BY id_siswa");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    $rank = mysql_fetch_array(mysql_query("SELECT * FROM rb_peringkat where nisn='$r[nisn]' AND kode_kelas='$r[kode_kelas]' AND id_tahun_akademik='$_GET[tahun]'"));
                    $rapna = mysql_fetch_array(mysql_query("SELECT sum(d.total) as total FROM (SELECT kodejdwl, nisn, (sum(uh)+sum(tugas))/4 as total FROM ((SELECT kodejdwl, nisn, sum(nilai1)/count(nisn) as uh, sum(nilai2)/count(nisn) as tugas FROM `rb_nilai_pengetahuan` where nisn='$r[nisn]' GROUP BY kodejdwl) UNION (SELECT kodejdwl, nisn, nilai1, (nilai2*2) FROM rb_nilai_pengetahuan_semester where nisn='$r[nisn]')) as a GROUP by a.kodejdwl) as d"));
                    $rapnka = mysql_fetch_array(mysql_query("SELECT sum(raport) as raporttotal FROM (SELECT sum(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/count(nisn) as raport FROM rb_nilai_keterampilan where nisn='$r[nisn]' GROUP BY kodejdwl) as a"));

                    $i = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where nisn='$r[nisn]' AND kode_kehadiran='I'"));
                    $s = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where nisn='$r[nisn]' AND kode_kehadiran='S'"));
                    $a = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where nisn='$r[nisn]' AND kode_kehadiran='A'"));
                    $jml = $i+$a+$s;
                        echo "<tr>";
                              echo "<td>$no</td>
                              <td>$r[nipd]</td>
                              <td>$r[nama]</td>";

                            $jdwl3 = mysql_query("SELECT * FROM rb_jadwal_pelajaran a JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran where a.kode_kelas='$_GET[kelas]' AND a.id_tahun_akademik='$_GET[tahun]'");
                            while ($j = mysql_fetch_array($jdwl3)){
                            $rapn = mysql_fetch_array(mysql_query("SELECT sum(nilai1)/count(nisn) as uh, sum(nilai2)/count(nisn) as tugas FROM rb_nilai_pengetahuan where kodejdwl='$j[kodejdwl]' AND nisn='$r[nisn]'"));
                            $sms = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pengetahuan_semester where kodejdwl='$j[kodejdwl]' AND nisn='$r[nisn]'"));
                            $nilai_raport = (($rapn[uh]+$rapn[tugas]+$sms[nilai1])+(2*$sms[nilai2]))/4;  

                            $rapnk = mysql_fetch_array(mysql_query("SELECT sum(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/count(nisn) as raport FROM rb_nilai_keterampilan where kodejdwl='$j[kodejdwl]' AND nisn='$r[nisn]'"));
                            $des = mysql_fetch_array(mysql_query("SELECT * FROM rb_ledger where kodejdwl='$j[kodejdwl]' AND nisn='$r[nisn]' AND kode_kelas='$j[kode_kelas]'"));   
                              echo "<td align=center>".number_format($nilai_raport)."</td>
                                    <td align=center>".number_format($rapnk[raport])."</td>
                                    <td align=center>$des[sikap]</td>";
                            }
                        echo "<td align=center>$s</td>
                              <td align=center>$i</td>
                              <td align=center>$a</td>
                              <td align=center>$jml</td>
                              <td align=center>$rank[nilai_total]</td>
                              <td align=center>$rank[rank]</td>
                              <td align=center>".number_format($rapna[total])."</td>
                              <td align=center>".number_format($rapnka[raporttotal])."</td>
                              <td></td>
                        </tr>";
                      $no++;
                      }

                  ?>
                    </tbody>
                  </table>
</body>
</html>