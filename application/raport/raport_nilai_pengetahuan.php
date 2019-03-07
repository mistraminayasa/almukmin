<?php 
    if (isset($_POST[simpan])){
            if ($_POST[status]=='Update'){
              mysql_query("UPDATE rb_nilai_pengetahuan SET id_kompetensi_dasar='$_POST[a]', nilai1='$_POST[b]', nilai2='$_POST[c]' where id_nilai_pengetahuan='$_POST[id]'");
            }else{
              mysql_query("INSERT INTO rb_nilai_pengetahuan VALUES('','$_GET[jdwl]','$_POST[nisn]','$_POST[a]','$_POST[b]','$_POST[c]','$_SESSION[id]','".date('Y-m-d H:i:s')."')");
            }
        echo "<script>document.location='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]#$_POST[nisn]';</script>";
    }

    if (isset($_GET[delete])){
        mysql_query("DELETE FROM rb_nilai_pengetahuan where id_nilai_pengetahuan='$_GET[delete]'");
        echo "<script>document.location='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]#$_GET[nisn]';</script>";
    }

    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Input Nilai Pengetahuan Siswa</b></h3>
                </div>
            
        <div class='box-body'>
              <div class='col-md-12'>
              <table class='table table-condensed table-hover'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[kodekelas]'>
                    <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[kode_kelas]</td></tr>
                    <tr><th scope='row'>Nama Kelas</th>               <td>$d[nama_kelas]</td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
                  </tbody>
              </table>
              </div>

          <div class='panel-body'>
              <table class='table table-bordered table-striped'>
                                <tr>
                                  <th style='border:1px solid #e3e3e3' width='30px' rowspan='2'>No</th>
                                  <th style='border:1px solid #e3e3e3' width='170px' rowspan='2'>Nama Lengkap</th>
                                  <th style='border:1px solid #e3e3e3;' rowspan='2'><center>Kompetensi Dasar</center></th>
                                  <th style='border:1px solid #e3e3e3' colspan='2'><center>Penilaian</center></th>
                                  <th style='border:1px solid #e3e3e3; width:65px' rowspan='2'><center>Action</center></th>
                                </tr>
                                <tr>
                                  <th style='border:1px solid #e3e3e3; width:55px'><center>UH</center></th>
                                  <th style='border:1px solid #e3e3e3; width:55px'><center>TU</center></th>
                                </tr>
                              <tbody>";
                              $no = 1;
                              $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                              while($r=mysql_fetch_array($tampil)){
                                  if (isset($_GET[edit])){
                                      $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pengetahuan where id_nilai_pengetahuan='$_GET[edit]'"));
                                      $name = 'Update';
                                  }else{
                                      $name = 'Insert';
                                  }
                                  if ($_GET[nisn]==$r[nisn]){
                                    echo "<form action='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                                      <tr>
                                        <td>$no</td>
                                        <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                                        <input type='hidden' name='nisn' value='$r[nisn]'>
                                        <input type='hidden' name='id' value='$e[id_nilai_pengetahuan]'>
                                        <input type='hidden' name='status' value='$name'>
                                        <td align=center>
                                          <select class='form-control' name='a' style='border:1px solid red'><option value='0' selected>- Pilih -</option>"; 
                                                $kdasar1 = mysql_query("SELECT * FROM rb_kompetensi_dasar where kode_pelajaran='$_GET[kd]'");
                                                while($a = mysql_fetch_array($kdasar1)){
                                                  if ($e[id_kompetensi_dasar]==$a[id_kompetensi_dasar]){
                                                    echo "<option value='$a[id_kompetensi_dasar]' selected>$a[kd]. $a[kompetensi_dasar]</option>";
                                                  }else{
                                                    echo "<option value='$a[id_kompetensi_dasar]'>$a[kd]. $a[kompetensi_dasar]</option>";
                                                  }
                                                }
                                          echo "</select></td>
                                        <td align=center><input type='text' name='b' class='form-control' value='$e[nilai1]' style='width:35px; text-align:center; padding:0px; border:1px solid red'></td>
                                        <td align=center><input type='text' name='c' class='form-control' value='$e[nilai2]' style='width:35px; text-align:center; padding:0px; border:1px solid red'></td>
                                        <td align=center><input type='submit' name='simpan' class='btn btn-sm btn-primary' style='width:65px' value='$name'></td>
                                      </tr>
                                      </form>";
                                  }else{
                                    echo "<form action='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                                      <tr>
                                        <td>$no</td>
                                        <td style='font-size:12px' id='$r[nisn]'>$r[nama]</td>
                                        <input type='hidden' name='nisn' value='$r[nisn]'>
                                        <input type='hidden' name='id' value='$e[id_nilai_pengetahuan]'>
                                        <input type='hidden' name='status' value='$name'>
                                        <td align=center>
                                          <select class='form-control' name='a'><option value='0' selected>- Pilih -</option>"; 
                                                $kdasar = mysql_query("SELECT * FROM rb_kompetensi_dasar where kode_pelajaran='$_GET[kd]'");
                                                while($a = mysql_fetch_array($kdasar)){
                                                    echo "<option value='$a[id_kompetensi_dasar]'>$a[kd]. $a[kompetensi_dasar]</option>";
                                                }
                                          echo "</select></td>
                                        <td align=center><input type='text' name='b' class='form-control' style='width:35px; text-align:center; padding:0px'></td>
                                        <td align=center><input type='text' name='c' class='form-control' style='width:35px; text-align:center; padding:0px'></td>
                                        <td align=center><input type='submit' name='simpan' class='btn btn-sm btn-primary' style='width:65px' value='$name'></td>
                                      </tr>
                                      </form>";
                                  }

                                    $pe = mysql_query("SELECT * FROM rb_nilai_pengetahuan a LEFT JOIN rb_kompetensi_dasar b ON a.id_kompetensi_dasar=b.id_kompetensi_dasar where a.kodejdwl='$_GET[jdwl]' AND a.nisn='$r[nisn]'");
                                    while ($n = mysql_fetch_array($pe)){
                                    $ratarata = average(array($n[nilai1],$n[nilai2],$n[nilai3],$n[nilai4],$n[nilai5]));
                                    $cekpredikat = mysql_num_rows(mysql_query("SELECT * FROM rb_predikat where kode_kelas='$_GET[id]'"));
                                    if ($cekpredikat >= 1){
                                      $grade1 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($ratarata)." >=nilai_a) AND (".number_format($ratarata)." <= nilai_b) AND kode_kelas='$_GET[id]'"));
                                    }else{
                                      $grade1 = mysql_fetch_array(mysql_query("SELECT * FROM `rb_predikat` where (".number_format($ratarata)." >=nilai_a) AND (".number_format($ratarata)." <= nilai_b) AND kode_kelas='0'"));
                                    }
                                    
                                      echo "<tr>
                                        <td></td>
                                        <td></td>
                                        <td>$n[kd]. $n[kompetensi_dasar]</td>
                                        <td align=center>$n[nilai1]</td>
                                        <td align=center>$n[nilai2]</td>
                                        <td align=center><a href='index.php?view=raport&act=listsiswa&jdwl=".$_GET[jdwl]."&kd=".$_GET[kd]."&id=".$_GET[id]."&tahun=".$_GET[tahun]."&edit=".$n[id_nilai_pengetahuan]."&nisn=".$r[nisn]."#$r[nisn]' class='btn btn-xs btn-success'><span class='glyphicon glyphicon-edit'></span></a>
                                                        <a href='index.php?view=raport&act=listsiswa&jdwl=".$_GET[jdwl]."&kd=".$_GET[kd]."&id=".$_GET[id]."&tahun=".$_GET[tahun]."&delete=".$n[id_nilai_pengetahuan]."&nisn=".$r[nisn]."' class='btn btn-xs btn-danger' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a></td>
                                      </tr>";
                                    }
                                      $ta = mysql_fetch_array(mysql_query("SELECT kodejdwl, nisn, sum(nilai1)/count(nisn) as uh, sum(nilai2)/count(nisn) as tugas FROM `rb_nilai_pengetahuan` where nisn='$r[nisn]' AND kodejdwl='$_GET[jdwl]' GROUP BY kodejdwl"));
                                      $rapn = mysql_fetch_array(mysql_query("SELECT kodejdwl, nisn, (sum(uh)+sum(tugas))/4 as total FROM ((SELECT kodejdwl, nisn, sum(nilai1)/count(nisn) as uh, sum(nilai2)/count(nisn) as tugas FROM `rb_nilai_pengetahuan` where nisn='$r[nisn]' GROUP BY kodejdwl) UNION (SELECT kodejdwl, nisn, nilai1, (nilai2*2) FROM rb_nilai_pengetahuan_semester where nisn='$r[nisn]')) as a where a.kodejdwl='$_GET[jdwl]' GROUP by a.kodejdwl"));
                                      $ee = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pengetahuan_semester where id_nilai_pengetahuan_semester='$_GET[editsemester]'"));
                                      $sms = mysql_fetch_array(mysql_query("SELECT * FROM rb_nilai_pengetahuan_semester where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                                      echo "<tr>
                                              <td></td><td></td>
                                              <td align=center class='pull-right'>Nilai Rata-Rata</td>
                                              <td align=center>".number_format($ta[uh])."</td>
                                              <td align=center>".number_format($ta[tugas])."</td>
                                            </tr>";

                                          if ($_GET[nis]==$r[nisn]){
                                              echo "<form action='' method='POST'>
                                              <tr>  <input type='hidden' value='$_GET[jdwl]' name='jdwl'>
                                                <td><input type='hidden' value='$r[nisn]' name='nis'></td>
                                                <td><input type='hidden' value='$ee[id_nilai_pengetahuan_semester]' name='id'></td>
                                                <td align=center class='pull-right'>Nilai UTS</td>
                                                <td align=center colspan='2'><input type='text' name='a' class='form-control' style='width:95px; text-align:center; padding:0px; border:1px solid red' value='$ee[nilai1]'></td>
                                                <td rowspan='2' align=center colspan='2'>
                                                  <button type='submit' name='updatesemester' title='Simpan Nilai UTS atau UAS' class='btn btn-lg btn-default' style='margin-top:5px'><span class='glyphicon glyphicon-ok'></span></button></a>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td></td><td></td>
                                                <td align=center class='pull-right'>Nilai UAS</td>
                                                <td align=center colspan='2'><input type='text' name='b' class='form-control' style='width:95px; text-align:center; padding:0px; border:1px solid red' value='$ee[nilai2]'></td>
                                              </tr>
                                              </form>";
                                          }else{
                                              echo "<tr>
                                                <td></td><td></td>
                                                <td align=center class='pull-right'>Nilai UTS</td>
                                                <td align=center colspan='2'>".number_format($sms[nilai1])."</td>
                                                <td rowspan='2' align=center colspan='2'>
                                                  <a title='Ubah Nilai UTS atau UAS' href='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]&nis=$r[nisn]&editsemester=$sms[id_nilai_pengetahuan_semester]#$r[nisn]' class='btn btn-lg btn-default' style='margin-top:5px'><span class='glyphicon glyphicon-edit'></span></a>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td></td><td></td>
                                                <td align=center class='pull-right'>Nilai UAS</td>
                                                <td align=center colspan='2'>".number_format($sms[nilai2])."</td>
                                              </tr>";
                                          }

                                            echo "<tr>
                                              <td></td><td></td>
                                              <td align=center class='pull-right'><b style='color:blue'>Nilai Raport</b></td>
                                              <td align=center colspan='2'><b style='color:blue'>".number_format($rapn[total])."</b></td>
                                            </tr>";
                                  $no++;
                                }

                                if (isset($_POST[updatesemester])){
                                    if (trim($_POST[id])==''){
                                      $query = mysql_query("INSERT INTO rb_nilai_pengetahuan_semester VALUES('','$_POST[jdwl]','$_POST[nis]','$_POST[a]','$_POST[b]','$_SESSION[id]','".date('Y-m-d H:i:s')."')");
                                    }else{
                                      $query = mysql_query("UPDATE rb_nilai_pengetahuan_semester SET nilai1 = '$_POST[a]', nilai2 = '$_POST[b]' where id_nilai_pengetahuan_semester='$_POST[id]'");
                                    }

                                    if ($query){
                                      echo "<script>document.location='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]&sukses#$_POST[nis]';</script>";
                                    }else{
                                      echo "<script>document.location='index.php?view=raport&act=listsiswa&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]&gagal#$_POST[nis]';</script>";
                                    }
                                }

                                echo "</tbody>
                            </table>
              </div>
          </div>
        </div>
      </div>";
?>