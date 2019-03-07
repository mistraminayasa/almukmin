<?php 
session_start();
error_reporting(0);
include "../config/koneksi.php"; 
include "../config/fungsi_indotgl.php"; 
$frt = mysql_fetch_array(mysql_query("SELECT * FROM rb_header_print ORDER BY id_header_print DESC LIMIT 1")); 
?>
<head>
<title>Hal 4 - Raport Siswa</title>
<link rel="stylesheet" href="../bootstrap/css/printer.css">
</head>
<body onload="window.print()">
<?php
$t = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik where id_tahun_akademik='$_GET[tahun]'"));
$s = mysql_fetch_array(mysql_query("SELECT a.*, b.*, c.nama_guru as walikelas, c.nip FROM rb_siswa a 
                                      JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                        LEFT JOIN rb_guru c ON b.nip=c.nip where a.nisn='$_GET[id]'"));
if (substr($_GET[tahun],4,5)=='1'){ $semester = 'Ganjil'; }else{ $semester = 'Genap'; }
$iden = mysql_fetch_array(mysql_query("SELECT * FROM rb_identitas_sekolah ORDER BY id_identitas_sekolah DESC LIMIT 1"));

echo "<h1 align=center>LAPORAN HASIL BELAJAR SISWA</h1><br>
      <table width=100%>
        <tr><td>Nama Peserta Didik</td>       <td> : <b>$s[nama]</b> </td>           <td>Kelas / Semester </td> <td>: $s[kode_kelas] (".substr($_GET[tahun],4,5).") $semester</td></tr>
        <tr><td>No Induk</td>            <td> : $s[nipd] / $s[nisn]</td>             <td>Tahun Pelajaran </td> <td>: $t[keterangan]</td></tr>
        <tr><td width=140px>Nama Sekolah</td> <td> : $iden[nama_sekolah] </td>       <td width=140px></td>   <td></td></tr>
        
        
        
      </table><br>";

echo "<table id='tablemodul1' width=100% border=1>
          <tr>
            <th rowspan='3'>No</th>
            <th width='160px' rowspan='3'>Komponen</th>
            <th width='120px' rowspan='3'>Kriteria Ketuntasan Minimal (KKM)</th>
            <th colspan='5'>Nilai Hasil Belajar</th>
          </tr>
          </tr>
            <th colspan='2' style='text-align:center'>Pengetahuan dan Pemahaman Konsep</th>
            <th colspan='2' style='text-align:center'>Praktik</th>
            <th colspan='2' style='text-align:center'>Sikap / Afektif</th>
          </tr>
          <tr>
            <th>Angka</th>
            <th>Huruf</th>
            <th>Angka</th>
            <th>Huruf</th>
            <th>Predikat</th>
          </tr>";

      echo "<tr>
            <td align=center><b>A.</b></td><td><b>Mata Pelajaran</b></td><td></td><td></td><td></td><td></td><td></td><td></td>
          </tr>";
        $mapel = mysql_query("SELECT * FROM  rb_jadwal_pelajaran a JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran 
                                  where a.kode_kelas='$_GET[kelas]' AND a.id_tahun_akademik='$_GET[tahun]'");
        $no = 1;
        while ($m = mysql_fetch_array($mapel)){                                
        $rapn = mysql_fetch_array(mysql_query("SELECT kodejdwl, nisn, (sum(uh)+sum(tugas))/4 as total FROM ((SELECT kodejdwl, nisn, sum(nilai1)/count(nisn) as uh, sum(nilai2)/count(nisn) as tugas FROM `rb_nilai_pengetahuan` where nisn='$_GET[id]' GROUP BY kodejdwl) UNION (SELECT kodejdwl, nisn, nilai1, (nilai2*2) FROM rb_nilai_pengetahuan_semester where nisn='$_GET[id]')) as a where a.kodejdwl='$m[kodejdwl]' GROUP by a.kodejdwl"));

        $cekpredikat = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[kelas]'"));
            if ($cekpredikat >= 1){
                $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($rapn[raport])." >=nilai_a) AND (".number_format($rapn[raport])." <= nilai_b) AND kode_kelas='$_GET[kelas]'"));
            }else{
                $grade3 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($rapn[raport])." >=nilai_a) AND (".number_format($rapn[raport])." <= nilai_b) AND kode_kelas='0'"));
            }

        $rapnk = mysql_fetch_array(mysql_query("SELECT sum(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/count(nisn) as raport FROM rb_nilai_keterampilan where kodejdwl='$m[kodejdwl]' AND nisn='$s[nisn]'"));
        $cekpredikat2 = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[kelas]'"));
            if ($cekpredikat2 >= 1){
                $grade = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($rapnk[raport])." >=nilai_a) AND (".number_format($rapnk[raport])." <= nilai_b) AND kode_kelas='$_GET[kelas]'"));
            }else{
                $grade = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($rapnk[raport])." >=nilai_a) AND (".number_format($rapnk[raport])." <= nilai_b) AND kode_kelas='0'"));
            }
        $des = mysql_fetch_array(mysql_query("SELECT * FROM rb_ledger where kodejdwl='$m[kodejdwl]' AND nisn='$_GET[id]' AND kode_kelas='$_GET[kelas]'"));   
        echo "<tr>
                <td align=center>$no</td>
                <td>$m[namamatapelajaran]</td>
                <td align=center>$m[kkm]</td>
                <td align=center>".number_format($rapn[total])."</td>
                <td>".ucwords(terbilang(number_format($rapn[total])))."</td>
                <td align=center>".number_format($rapnk[raport])."</td>
                <td>".ucwords(terbilang(number_format($rapnk[raport])))."</td>
                <td align=center>$des[sikap]</td>
            </tr>";
        $no++;
        }

        $ta = mysql_fetch_array(mysql_query("SELECT sum(d.total) as total FROM (SELECT kodejdwl, nisn, (sum(uh)+sum(tugas))/4 as total FROM ((SELECT kodejdwl, nisn, sum(nilai1)/count(nisn) as uh, sum(nilai2)/count(nisn) as tugas FROM `rb_nilai_pengetahuan` where nisn='$_GET[id]' GROUP BY kodejdwl) UNION (SELECT kodejdwl, nisn, nilai1, (nilai2*2) FROM rb_nilai_pengetahuan_semester where nisn='$_GET[id]')) as a GROUP by a.kodejdwl) as d"));
        $rapnk = mysql_fetch_array(mysql_query("SELECT sum(raport) as raporttotal FROM (SELECT sum(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/count(nisn) as raport FROM rb_nilai_keterampilan where nisn='$_GET[id]' GROUP BY kodejdwl) as a"));
        $siswa = mysql_num_rows(mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[kelas]'"));
        $rank = mysql_fetch_array(mysql_query("SELECT * FROM rb_peringkat where nisn='$_GET[id]' AND kode_kelas='$_GET[kelas]' AND id_tahun_akademik='$_GET[tahun]'"));
        echo "<tr>
                <td colspan='3' align=center><b>JUMLAH</b></td>
                <td align=center><b>".number_format($ta[total])."</b></td>
                <td align=center></td>
                <td align=center><b>".number_format($rapnk[raporttotal])."</b></td>
                <td align=center></td>
                <td></td>
            </tr>"; 

        echo "<tr>
                <td align=center><b></b></td>
                <td><b>PERINGKAT</b></td>
                <td align=center><b>$rank[rank]</b></td>
                <td colspan='2'><b>Dari $siswa Siswa</b></td>
                <td align=center></td>
                <td align=center></td>
                <td></td>
            </tr>"; 

        echo "</table><br/>";
?>

<table border=0 width=100%>
  <tr>
    <td width="260" align="left">Orang Tua / Wali</td>
    <td width="520"align="center">Mengetahui <br> Kepala SMK IT Robbi Rodliyya</td>
    <td width="260" align="left">Semarang, <?php echo tgl_raport(date("Y-m-d")); ?> <br> Wali Kelas</td>
  </tr>
  <tr>
    <td align="left"><br /><br /><br /><br /><br />
      ................................... <br /><br /></td>

    <td align="center" valign="top"><br /><br /><br /><br /><br />
      <b>Faqihuddin Habibullah, S.S, S.Kom, M.Si<br>
                NIP : 1966 1231 199702 1 022</b>
    </td>

    <td align="left" valign="top"><br /><br /><br /><br /><br />
      <b><?php echo $s[walikelas]; ?><br />
      NIP : <?php echo $s[nip]; ?></b>
    </td>
  </tr>
</table> 
</body>