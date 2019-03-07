<?php 
if ($_GET[act]==''){ 
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Data Ledger Mata Pelajaran pada <?php echo $tahunakademik[id_tahun_akademik]; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Jadwal Pelajaran</th>
                        <th>Kelas</th>
                        <th>Guru</th>
                        <th>Hari</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Ruang</th>
                        <?php
                        if (isset($_GET[tahun]) AND isset($_GET[tahun])){ 
                          if($_SESSION[level]!='kepala'){ 
                            echo "<th><center>Penilaian</center></th>";
                          }
                        }  
                        ?>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.kode_kurikulum, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$tahunakademik[id_tahun_akademik]' 
                                                      AND b.kode_kurikulum='$kurikulum[kode_kurikulum]' ORDER BY a.hari DESC");
                    
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[nama_kelas]</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[hari]</td>
                              <td>$r[jam_mulai]</td>
                              <td>$r[jam_selesai]</td>
                              <td>$r[nama_ruangan]</td>";
                                  echo "<td style='width:80px !important'><center>
                                          <a class='btn btn-primary btn-xs' title='Lihat Nilai Keterampilan Siswa' href='index.php?view=ledgermapelguru&act=listsiswasikap&jdwl=$r[kodejdwl]&kd=$r[kode_pelajaran]&id=$r[kode_kelas]&tahun=$tahunakademik[id_tahun_akademik]'><span class='glyphicon glyphicon-search'></span> Lihat</a>
                                        </center></td>";
                            echo "</tr>";
                      $no++;
                      }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                </div>
            </div>
                                
<?php 
}
elseif($_GET[act]=='listsiswasikap'){
    if (isset($_POST[simpanpenilaian])){
        $juml = mysql_num_rows(mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]'"));
        for ($ia=1; $ia<=$juml; $ia++){
          $a   = $_POST['a'.$ia];
          $b   = $_POST['b'.$ia];
          $c   = $_POST['c'.$ia];
          $nisn   = $_POST['nisn'.$ia];
            $cek = mysql_num_rows(mysql_query("SELECT * FROM rb_ledger where kodejdwl='$_GET[jdwl]' AND nisn='$nisn' AND kode_kelas='$_GET[id]'"));
            if ($cek >= '1'){
              mysql_query("UPDATE rb_ledger SET pengetahuan='$a', keterampilan='$b', sikap='$c' where kodejdwl='$_GET[jdwl]' AND nisn='$nisn' AND kode_kelas='$_GET[id]'");
            }else{
              mysql_query("INSERT INTO rb_ledger VALUES('','$_GET[jdwl]','$nisn','$_GET[id]','$a','$b','$c','','','','".date('Y-m-d H:i:s')."')");
            }
        }
    }

    if (isset($_POST[simpandeskripsi])){
        $juml = mysql_num_rows(mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]'"));
        for ($ia=1; $ia<=$juml; $ia++){
          $a   = $_POST['a'.$ia];
          $b   = $_POST['b'.$ia];
          $c   = $_POST['c'.$ia];
          $nisn   = $_POST['nisn'.$ia];
            $cek = mysql_num_rows(mysql_query("SELECT * FROM rb_ledger where kodejdwl='$_GET[jdwl]' AND nisn='$nisn' AND kode_kelas='$_GET[id]'"));
            if ($cek >= '1'){
              mysql_query("UPDATE rb_ledger SET desk_pengetahuan='$a', desk_keterampilan='$b', desk_sikap='$c' where kodejdwl='$_GET[jdwl]' AND nisn='$nisn' AND kode_kelas='$_GET[id]'");
            }else{
              mysql_query("INSERT INTO rb_ledger VALUES('','$_GET[jdwl]','$nisn','$_GET[id]','','','','$a','$b','$c','".date('Y-m-d H:i:s')."')");
            }
        }
        echo "<script>document.location='index.php?view=ledgermapelguru&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]';</script>";
    }

    $d = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
    $m = mysql_fetch_array(mysql_query("SELECT * FROM rb_jadwal_pelajaran a JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran JOIN rb_guru c ON a.nip=c.nip where a.kodejdwl='$_GET[jdwl]'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Detail Ledger Nilai Mata Pelajaran Siswa</b></h3>
                  <a target='_BLANK' class='btn btn-sm btn-primary pull-right' href='export-ledger-mapel.php?jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]&id=$_GET[id]&kd=$_GET[kd]'>Export Excel Ledger Mapel</a>
                </div>
            
        <div class='box-body'>
              <div class='col-md-12'>
              <table class='table table-condensed table-hover'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[kodekelas]'>
                    <tr><th width='120px' scope='row'>Kode Kelas</th> <td>$d[nama_kelas]</td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
                    <tr><th scope='row'>Nama Guru</th>           <td>$m[nama_guru]</td></tr>
                  </tbody>
              </table>
              </div>

          <div class='panel-body'>
                    <ul id='myTabs' class='nav nav-tabs' role='tablist'>
                      <li role='presentation' class='active'><a href='#spiritual' id='spiritual-tab' role='tab' data-toggle='tab' aria-controls='spiritual' aria-expanded='true'>Penilaian </a></li>
                      <li role='presentation' class=''><a href='#sosial' role='tab' id='sosial-tab' data-toggle='tab' aria-controls='sosial' aria-expanded='false'>Deskripsi</a></li>
                      <li role='presentation' class=''><a href='#hadir' role='tab' id='hadir-tab' data-toggle='tab' aria-controls='hadir' aria-expanded='false'>Kehadiran</a></li>
                    </ul><br>
            <div id='myTabContent' class='tab-content'>";

                  // Ini Halaman unutk Penilaian
                      echo "<div role='tabpanel' class='tab-pane fade active in' id='spiritual' aria-labelledby='spiritual-tab'>";
                      echo "<div class='col-md-12'>
                            <form action='index.php?view=ledgermapelguru&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                            <input type='hidden' value='spiritual' name='status'>
                            <table class='table table-bordered table-striped'>
                                <tr>
                                  <th style='border:1px solid #e3e3e3' width='30px' rowspan='2'>No</th>
                                  <th style='border:1px solid #e3e3e3' width='80px' rowspan='2'>NISN</th>
                                  <th style='border:1px solid #e3e3e3' width='190px' rowspan='2'>Nama Lengkap</th>
                                  <th style='border:1px solid #e3e3e3' colspan='2'><center>KKM</center></th>
                                  <th style='border:1px solid #e3e3e3' colspan='2'><center>$m[kkm]</center></th>
                                </tr>
                                <tr>
                                  <th style='border:1px solid #e3e3e3;'><center>Kognitif / Pengetahuan</center></th>
                                  <th style='border:1px solid #e3e3e3;'><center>Psikomotorik / Keterampilan </center></th>
                                  <th style='border:1px solid #e3e3e3;'><center>Afektif / Sikap</center></th>
                                </tr>
                              <tbody>";
                              $no = 1;
                              $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                              while($r=mysql_fetch_array($tampil)){
                              $rapnk = mysql_fetch_array(mysql_query("SELECT sum(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/count(nisn) as raport FROM rb_nilai_keterampilan where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]'"));
                              $des = mysql_fetch_array(mysql_query("SELECT * FROM rb_ledger where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kelas='$_GET[id]'"));   
                              $rapn = mysql_fetch_array(mysql_query("SELECT kodejdwl, nisn, (sum(uh)+sum(tugas))/4 as total FROM ((SELECT kodejdwl, nisn, sum(nilai1)/count(nisn) as uh, sum(nilai2)/count(nisn) as tugas FROM `rb_nilai_pengetahuan` where nisn='$r[nisn]' GROUP BY kodejdwl) UNION (SELECT kodejdwl, nisn, nilai1, (nilai2*2) FROM rb_nilai_pengetahuan_semester where nisn='$r[nisn]')) as a where a.kodejdwl='$_GET[jdwl]' GROUP by a.kodejdwl"));
    
                                  echo "<tr>
                                        <td>$no</td>
                                        <td>$r[nisn]</td>
                                        <td>$r[nama]</td>
                                        <input type='hidden' name='nisn".$no."' value='$r[nisn]'>
                                        <td align=center><input style='text-align:center' class='form-control' type='number' name='a".$no."' value='".number_format($rapn[total])."' readonly='on'></td>
                                        <td align=center><input style='text-align:center' class='form-control' type='number' name='b".$no."' value='".number_format($rapnk[raport])."' readonly='on'></td>
                                        <td align=center><select class='form-control' name='c".$no."'>
                                            <option value='0' selected>- Pilih -</option>";
                                            $indikator = mysql_query("SELECT * FROM rb_nilai_sikap_indikator");
                                            while ($i = mysql_fetch_array($indikator)){
                                              if ($des[sikap]==$i[kode_indikator]){
                                                echo "<option value='$i[kode_indikator]' selected>$i[kode_indikator] ($i[keterangan])</option>";
                                              }else{
                                                echo "<option value='$i[kode_indikator]'>$i[kode_indikator] ($i[keterangan])</option>";
                                              }
                                            }
                                        echo "</select></td>
                                      </tr>";
                                  $no++;
                                }

                                echo "</tbody>
                            </table>
                            </div>
                            <div style='clear:both'></div>
                                <div class='box-footer'>
                                  <button type='submit' name='simpanpenilaian' class='btn btn-info'>Simpan Penilaian</button>
                                  <button type='reset' class='btn btn-default pull-right'>Cancel</button>
                                </div>
                            </form>
                            </div>";


                      // Ini Halaman unutk Deskripsi
                echo "<div role='tabpanel' class='tab-pane fade' id='sosial' aria-labelledby='sosial-tab'>
                      <div class='col-md-12'>
                            <form action='index.php?view=ledgermapelguru&act=listsiswasikap&jdwl=$_GET[jdwl]&kd=$_GET[kd]&id=$_GET[id]&tahun=$_GET[tahun]' method='POST'>
                            <input type='hidden' value='sosial' name='status'>
                            <table class='table table-bordered table-striped'>
                                <tr>
                                  <th style='border:1px solid #e3e3e3' width='30px' rowspan='2'>No</th>
                                  <th style='border:1px solid #e3e3e3' width='80px' rowspan='2'>NISN</th>
                                  <th style='border:1px solid #e3e3e3' width='190px' rowspan='2'>Nama Lengkap</th>
                                  <th style='border:1px solid #e3e3e3' colspan='3'><center>Deskripsi Ketercapaian</center></th>
                                </tr>
                                <tr>
                                  <th style='border:1px solid #e3e3e3;'><center>Kognitif / Pengetahuan </center></th>
                                  <th style='border:1px solid #e3e3e3;'><center>Psikomotorik / Keterampilan</center></th>
                                  <th style='border:1px solid #e3e3e3;'><center>Afektif / Sikap</center></th>
                                </tr>
                              <tbody>";
                              $no = 1;
                              $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                              while($r=mysql_fetch_array($tampil)){
                                $des = mysql_fetch_array(mysql_query("SELECT * FROM rb_ledger where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kelas='$_GET[id]'"));
                                  echo "<tr>
                                        <td>$no</td>
                                        <td>$r[nisn]</td>
                                        <td>$r[nama]</td>
                                        <input type='hidden' name='nisn".$no."' value='$r[nisn]'>
                                        <td align=center><textarea name='a".$no."' class='form-control' style='width:100%; color:blue' placeholder=' Tuliskan Deskripsi...'>$des[desk_pengetahuan]</textarea></td>
                                        <td align=center><textarea name='b".$no."' class='form-control' style='width:100%; color:blue' placeholder=' Tuliskan Deskripsi...'>$des[desk_keterampilan]</textarea></td>
                                        <td align=center><textarea name='c".$no."' class='form-control' style='width:100%; color:blue' placeholder=' Tuliskan Deskripsi...'>$des[desk_sikap]</textarea></td>
                                      </tr>";
                                  $no++;
                                }

                                echo "</tbody>
                            </table>
                            </div>
                            <div style='clear:both'></div>
                              <div class='box-footer'>
                                  <button type='submit' name='simpandeskripsi' class='btn btn-info'>Simpan Deskripsi</button>
                                  <button type='reset' class='btn btn-default pull-right'>Cancel</button>
                                </div>
                            </form>
                            </div>";

                // Ini Halaman unutk Kehadiran
                echo "<div role='tabpanel' class='tab-pane fade' id='hadir' aria-labelledby='hadir-tab'>
                      <div class='col-md-12'>
                            <form action='#' method='POST'>
                            <input type='hidden' value='hadir' name='status'>
                            <table class='table table-bordered table-striped'>
                                <tr>
                                  <th style='border:1px solid #e3e3e3' width='30px' rowspan='2'>No</th>
                                  <th style='border:1px solid #e3e3e3' width='80px' rowspan='2'>NISN</th>
                                  <th style='border:1px solid #e3e3e3' width='190px' rowspan='2'>Nama Lengkap</th>
                                  <th style='border:1px solid #e3e3e3' colspan='3'><center>Kehadiran</center></th>
                                </tr>
                                <tr>
                                  <th style='border:1px solid #e3e3e3;'><center>Alpa</center></th>
                                  <th style='border:1px solid #e3e3e3;'><center>Ijin</center></th>
                                  <th style='border:1px solid #e3e3e3;'><center>Sakit</center></th>
                                </tr>
                              <tbody>";
                              $no = 1;
                              $tampil = mysql_query("SELECT * FROM rb_siswa where kode_kelas='$_GET[id]' ORDER BY id_siswa");
                              while($r=mysql_fetch_array($tampil)){
                                $i = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kehadiran='I'"));
                                $s = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kehadiran='S'"));
                                $a = mysql_num_rows(mysql_query("SELECT * FROM `rb_absensi_siswa` where kodejdwl='$_GET[jdwl]' AND nisn='$r[nisn]' AND kode_kehadiran='A'"));
                                  echo "<tr>
                                        <td>$no</td>
                                        <td>$r[nisn]</td>
                                        <td>$r[nama]</td>
                                        <td align=center><input style='text-align:center' class='form-control' type='number' value='$a' name='a' disabled></td>
                                        <td align=center><input style='text-align:center' class='form-control' type='number' value='$i' name='b' disabled></td>
                                        <td align=center><input style='text-align:center' class='form-control' type='number' value='$s' name='c' disabled></td>
                                      </tr>";
                                  $no++;
                                }

                                echo "</tbody>
                            </table>
                            </div>
                            <div style='clear:both'></div>";
                                /* echo "<div class='box-footer'>
                                  <button type='submit' name='simpan' class='btn btn-info'>Simpan</button>
                                  <button type='reset' class='btn btn-default pull-right'>Cancel</button>
                                </div>"; */

                            echo "</form>
                            </div>
                  </div>
              </div>
          </div>
        </div>
      </div>";
}
?>