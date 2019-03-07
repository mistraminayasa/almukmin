<?php if ($_GET[act]==''){ ?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title"><?php echo "Bank Soal Quiz dan Ujian Pada $tahunakademik[id_tahun_akademik]" ; ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Kode Pelajaran</th>
                        <th>Jadwal Pelajaran</th>
                        <th>Kelas</th>
                        <th>Guru</th>
                        <th>Hari</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Ruang</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php
                    if (isset($_GET[tahun])){
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$_GET[tahun]' ORDER BY a.hari DESC");
                    
                    }else{
                      $tampil = mysql_query("SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, b.tingkat, c.nama_guru, d.nama_ruangan FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_ruangan d ON a.kode_ruangan=d.kode_ruangan
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$tahunakademik[id_tahun_akademik]' ORDER BY a.hari DESC");
                    }
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[kode_pelajaran]</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[nama_kelas]</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[hari]</td>
                              <td>$r[jam_mulai]</td>
                              <td>$r[jam_selesai]</td>
                              <td>$r[nama_ruangan]</td>
                              <td style='width:200px !important'><center>
                                        <a class='btn btn-info btn-xs' title='Lihat Soal' href='index.php?view=banksoalguru&act=lihatessai&mapel=$r[kode_pelajaran]&tingkat=$r[tingkat]'><span class='glyphicon glyphicon-search'></span> Soal Essai</a>
                                        <a class='btn btn-success btn-xs' title='Lihat Soal' href='index.php?view=banksoalguru&act=lihatobjektif&mapel=$r[kode_pelajaran]&tingkat=$r[tingkat]'><span class='glyphicon glyphicon-search'></span> Soal Objektif</a>
                                      </center></td>
                          </tr>";
                      $no++;
                      }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                </div>
            </div>

<?php 
}elseif($_GET[act]=='lihatessai'){
    $d = mysql_fetch_array(mysql_query("SELECT * FROM `rb_mata_pelajaran` where kode_pelajaran='$_GET[mapel]'"));
            echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Bank Soal Essai</h3>";
                  if($_SESSION[level]!='kepala'){
                      echo "<a class='pull-right btn btn-primary btn-sm' href='index.php?view=banksoalguru&act=tambah&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]'>Tambahkan Soal</a>";
                  }
                echo "</div>
                <div class='box-body'>";

                  if (isset($_GET['sukses'])){
                      echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
                          </div>";
                  }elseif(isset($_GET['gagal'])){
                      echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..
                          </div>";
                  }

                  echo "<div class='col-md-12'>
                  <table class='table table-condensed table-hover'>
                      <tbody>
                        <tr><th width='120px' scope='row'>Kelas</th> <td>$d[tingkat]</td></tr>
                        <tr><th scope='row'>Kode Mapel</th>           <td>$d[kode_pelajaran]</td></tr>
                        <tr><th scope='row'>Nama Mapel</th>           <td>$d[namamatapelajaran]</td></tr>
                      </tbody>
                  </table>
                  </div>

                  <table id='example' class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Pertanyaan</th>";
                        if($_SESSION[level]!='kepala'){
                            echo "<th>Action</th>";
                        }
                      echo "</tr>
                    </thead>
                    <tbody>";
                      $tampil = mysql_query("SELECT * FROM rb_bank_pertanyaan_essai where kode_pelajaran='$_GET[mapel]'");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[pertanyaan_essai]</td>";
                              if($_SESSION[level]!='kepala'){
                                echo "<td style='width:80px !important'><center>
                                        <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=banksoalguru&act=edit&id=$r[id_bank_pertanyaan_essai]&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]'><span class='glyphicon glyphicon-edit'></span></a>
                                        <a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?view=banksoalguru&act=lihatessai&hapus=$r[id_bank_pertanyaan_essai]&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                                      </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET[hapus])){
                        mysql_query("DELETE FROM rb_bank_pertanyaan_essai where id_bank_pertanyaan_essai='$_GET[hapus]'");
                        echo "<script>document.location='index.php?view=banksoalguru&act=lihatessai&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
                      }

                    echo "<tbody>
                  </table>
                </div>
                </div>
            </div>";

}elseif($_GET[act]=='tambah'){
  if (isset($_POST[essai])){
      $query = mysql_query("INSERT INTO rb_bank_pertanyaan_essai VALUES('','$_GET[mapel]','$_POST[a]')");
        if ($query){
          echo "<script>document.location='index.php?view=banksoalguru&act=lihatessai&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=banksoalguru&act=lihatessai&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&gagal';</script>";
        }

  }
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Soal Essay</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='120px' scope='row'>Pertanyaan</th> <td><textarea style='height:250px;' class='textarea form-control' name='a'></textarea> </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='essai' class='btn btn-info'>Simpan</button>
                    <a href='#'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";

}elseif($_GET[act]=='edit'){
  if (isset($_POST[essai])){
      $query = mysql_query("UPDATE rb_bank_pertanyaan_essai SET pertanyaan_essai = '$_POST[a]' where id_bank_pertanyaan_essai='$_POST[id]'");
        if ($query){
          echo "<script>document.location='index.php?view=banksoalguru&act=lihatessai&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=banksoalguru&act=lihatessai&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&gagal';</script>";
        }
  }

  $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_bank_pertanyaan_essai where id_bank_pertanyaan_essai='$_GET[id]'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Soal Essay</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' value='$e[id_bank_pertanyaan_essai]' name='id'>
                    <tr><th width='120px' scope='row'>Pertanyaan</th> <td><textarea style='height:250px;' class='textarea form-control' name='a'>$e[pertanyaan_essai]</textarea> </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='essai' class='btn btn-info'>Simpan</button>
                    <a href='#'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";

}elseif($_GET[act]=='lihatobjektif'){
    $d = mysql_fetch_array(mysql_query("SELECT * FROM `rb_mata_pelajaran` where kode_pelajaran='$_GET[mapel]'"));
            echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Bank Soal Objektif</h3>";
                  if($_SESSION[level]!='kepala'){
                      echo "<a class='pull-right btn btn-primary btn-sm' href='index.php?view=banksoalguru&act=tambahobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]'>Tambahkan Soal</a>";
                  }
                echo "</div>
                <div class='box-body'>";

                  if (isset($_GET['sukses'])){
                      echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
                          </div>";
                  }elseif(isset($_GET['gagal'])){
                      echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..
                          </div>";
                  }

                  echo "<div class='col-md-12'>
                  <table class='table table-condensed table-hover'>
                      <tbody>
                        <tr><th width='120px' scope='row'>Kelas</th> <td>$_GET[tingkat]</td></tr>
                        <tr><th scope='row'>Kode Mapel</th>           <td>$d[kode_pelajaran]</td></tr>
                        <tr><th scope='row'>Nama Mapel</th>           <td>$d[namamatapelajaran]</td></tr>
                      </tbody>
                  </table>
                  </div>
                <table id='example' class='table table-bordered table-striped'>
                    <tr>
                      <th style='width:40px'>No</th>
                      <th>Pertanyaan Objektif</th>
                      <th colspan=2></th>
                    </tr>";
                    $objektif = mysql_query("SELECT * FROM `rb_bank_pertanyaan_objektif` where kode_pelajaran='$_GET[mapel]'");
                    $noo = 1;
                    while ($ko = mysql_fetch_array($objektif)){
                        echo "<tr>
                            <td>$noo</td>
                            <td>$ko[pertanyaan_objektif] <br>";
                              if (trim($ko[jawab_a])!=''){ echo "<input type='radio' name='$noo'> a. $ko[jawab_a] <br>"; }
                              if (trim($ko[jawab_b])!=''){ echo "<input type='radio' name='$noo'> b. $ko[jawab_b] <br>"; }
                              if (trim($ko[jawab_c])!=''){ echo "<input type='radio' name='$noo'> c. $ko[jawab_c] <br>"; }
                              if (trim($ko[jawab_d])!=''){ echo "<input type='radio' name='$noo'> d. $ko[jawab_d] <br>"; }
                              if (trim($ko[jawab_e])!=''){ echo "<input type='radio' name='$noo'> e. $ko[jawab_e]"; }
                            echo "<div class='btn btn-default btn-xs btn-block'>Kunci Jawaban : $ko[kunci_jawaban]</div>
                            </td>
                            <td style='width:80px'>
                                <a class='btn btn-success btn-xs' href='index.php?view=banksoalguru&act=editobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&id=$ko[id_bank_pertanyaan_objektif]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' href='index.php?view=banksoalguru&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&hapus=$ko[id_bank_pertanyaan_objektif]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                            </td>
                            </tr>";
                      $noo++;
                    } 

                      if (isset($_GET[hapus])){
                        mysql_query("DELETE FROM rb_bank_pertanyaan_objektif where id_bank_pertanyaan_objektif='$_GET[hapus]'");
                        echo "<script>document.location='index.php?view=banksoalguru&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
                      }

              echo "</table>
                </div>
                </div>
            </div>";

}elseif($_GET[act]=='tambahobjektif'){
  if (isset($_POST[objektif])){
      $query = mysql_query("INSERT INTO rb_bank_pertanyaan_objektif VALUES('','$_GET[mapel]','$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]','$_POST[e]','$_POST[f]','$_POST[g]')");
        if ($query){
          echo "<script>document.location='index.php?view=banksoalguru&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=banksoalguru&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&gagal';</script>";
        }
  }
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Soal Objektif</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='120px' scope='row'>Pertanyaan</th> <td><textarea style='height:250px;' class='textarea form-control' name='a'></textarea> </td></tr>
                    <tr><th width='120px' scope='row'>Jawab A</th> <td><textarea style='height:150px;' class='textarea form-control' name='b'></textarea></td></tr>
                    <tr><th width='120px' scope='row'>Jawab B</th> <td><textarea style='height:150px;' class='textarea form-control' name='c'></textarea></td></tr>
                    <tr><th width='120px' scope='row'>Jawab C</th> <td><textarea style='height:150px;' class='textarea form-control' name='d'></textarea></td></tr>
                    <tr><th width='120px' scope='row'>Jawab D</th> <td><textarea style='height:150px;' class='textarea form-control' name='e'></textarea></td></tr>
                    <tr><th width='120px' scope='row'>Jawab E</th> <td><textarea style='height:150px;' class='textarea form-control' name='f'></textarea></td></tr>
                    <tr><th width='120px' scope='row'>Kunci</th> <td><select class='form-control' name='g'>
                                                                        <option value='' selected>- Pilih -</option>
                                                                        <option value='a'>Jawab A</option>
                                                                        <option value='b'>Jawab B</option>
                                                                        <option value='c'>Jawab C</option>
                                                                        <option value='d'>Jawab D</option>
                                                                        <option value='e'>Jawab E</option>
                                                                     </select></td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='objektif' class='btn btn-info'>Simpan</button>
                    <a href='#'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif($_GET[act]=='editobjektif'){
  if (isset($_POST[objektif])){
      $query = mysql_query("UPDATE rb_bank_pertanyaan_objektif SET pertanyaan_objektif = '$_POST[a]',
                                                              jawab_a = '$_POST[b]',
                                                              jawab_b = '$_POST[c]',
                                                              jawab_c = '$_POST[d]',
                                                              jawab_d = '$_POST[e]',
                                                              jawab_e = '$_POST[f]',
                                                              kunci_jawaban = '$_POST[g]' where id_bank_pertanyaan_objektif='$_POST[id]'");
        if ($query){
          echo "<script>document.location='index.php?view=banksoalguru&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=banksoalguru&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&gagal';</script>";
        }
  }

  $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_bank_pertanyaan_objektif where id_bank_pertanyaan_objektif='$_GET[id]'"));
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Soal Objektif</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$e[id_bank_pertanyaan_objektif]'>
                    <tr><th width='120px' scope='row'>Pertanyaan</th> <td><textarea style='height:250px;' class='textarea form-control' name='a'>$e[pertanyaan_objektif]</textarea> </td></tr>
                    <tr><th width='120px' scope='row'>Jawab A</th> <td><textarea style='height:150px;' class='textarea form-control' name='b'>$e[jawab_a]</textarea></td></tr>
                    <tr><th width='120px' scope='row'>Jawab B</th> <td><textarea style='height:150px;' class='textarea form-control' name='c'>$e[jawab_b]</textarea></td></tr>
                    <tr><th width='120px' scope='row'>Jawab C</th> <td><textarea style='height:150px;' class='textarea form-control' name='d'>$e[jawab_c]</textarea></td></tr>
                    <tr><th width='120px' scope='row'>Jawab D</th> <td><textarea style='height:150px;' class='textarea form-control' name='e'>$e[jawab_d]</textarea></td></tr>
                    <tr><th width='120px' scope='row'>Jawab E</th> <td><textarea style='height:150px;' class='textarea form-control' name='f'>$e[jawab_e]</textarea></td></tr>
                    <tr><th width='120px' scope='row'>Kunci</th> <td><select class='form-control' name='g'>
                                                                        <option value='$e[kunci_jawaban]' selected>Jawab $e[kunci_jawaban]</option>
                                                                        <option value='a'>Jawab a</option>
                                                                        <option value='b'>Jawab b</option>
                                                                        <option value='c'>Jawab c</option>
                                                                        <option value='d'>Jawab d</option>
                                                                        <option value='e'>Jawab e</option>
                                                                     </select></td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='objektif' class='btn btn-info'>Update</button>
                    <a href='#'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}

?>