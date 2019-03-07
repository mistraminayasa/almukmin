<?php if ($_GET[act]==''){ ?>
<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Bank Soal Quiz dan Ujian</h3>
            <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                <input type="hidden" name='view' value='banksoal'>
                <select name='tingkat' style='padding:4px'>
                    <option value='0' selected>- Pilih Tingkat -</option>
                    <?php 
                          $tingkat = array('VII','VIII','IX');
                          for ($i = 0; $i <= 2; $i++){
                            if ($_GET[tingkat]==$tingkat[$i]){
                              echo "<option value='".$tingkat[$i]."' selected>Kelas ".$tingkat[$i]."</option>";
                            }else{
                              echo "<option value='".$tingkat[$i]."'>Kelas ".$tingkat[$i]."</option>";
                            }
                          }
                        ?>
                </select>
                <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
            </form>

        </div><!-- /.box-header -->
        <div class="box-body">
            <table id="example" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style='width:20px'>No</th>
                        <th>Kode Mapel</th>
                        <th>Nama Mapel</th>
                        <th>Nama Jurusan</th>
                        <?php
                        if($_SESSION[level]!='kepala'){ ?>
                        <th>Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_GET[tingkat])){
                      $tampil = mysql_query("SELECT * FROM rb_mata_pelajaran a JOIN rb_jurusan b ON a.kode_jurusan=b.kode_jurusan where a.tingkat='$_GET[tingkat]'");
                    }
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[kode_pelajaran]</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[nama_jurusan]</td>";
                              if($_SESSION[level]!='kepala'){
                                echo "<td style='width:200px !important'><center>
                                        <a class='btn btn-info btn-xs' title='Lihat Soal' href='index.php?view=banksoal&act=lihatessai&mapel=$r[kode_pelajaran]&tingkat=$_GET[tingkat]'><span class='glyphicon glyphicon-search'></span> Soal Essai</a>
                                        <a class='btn btn-success btn-xs' title='Lihat Soal' href='index.php?view=banksoal&act=lihatobjektif&mapel=$r[kode_pelajaran]&tingkat=$_GET[tingkat]'><span class='glyphicon glyphicon-search'></span> Soal Objektif</a>
                                      </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }
                  ?>
                <tbody>
            </table>
        </div><!-- /.box-body -->
        <?php 
                    if ($_GET[tingkat] == ''){
                        echo "<center style='padding:60px; color:red'>Silahkan Memilih Tingkat Terlebih dahulu...</center>";
                    }
                ?>
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
                      echo "<a class='pull-right btn btn-primary btn-sm' href='index.php?view=banksoal&act=tambah&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]'>Tambahkan Soal</a>";
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
                                        <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=banksoal&act=edit&id=$r[id_bank_pertanyaan_essai]&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]'><span class='glyphicon glyphicon-edit'></span></a>
                                        <a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?view=banksoal&act=lihatessai&hapus=$r[id_bank_pertanyaan_essai]&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                                      </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET[hapus])){
                        mysql_query("DELETE FROM rb_bank_pertanyaan_essai where id_bank_pertanyaan_essai='$_GET[hapus]'");
                        echo "<script>document.location='index.php?view=banksoal&act=lihatessai&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
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
          echo "<script>document.location='index.php?view=banksoal&act=lihatessai&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=banksoal&act=lihatessai&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&gagal';</script>";
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
          echo "<script>document.location='index.php?view=banksoal&act=lihatessai&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=banksoal&act=lihatessai&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&gagal';</script>";
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
                      echo "<a class='pull-right btn btn-primary btn-sm' href='index.php?view=banksoal&act=tambahobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]'>Tambahkan Soal</a>";
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
                                <a class='btn btn-success btn-xs' href='index.php?view=banksoal&act=editobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&id=$ko[id_bank_pertanyaan_objektif]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' href='index.php?view=banksoal&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&hapus=$ko[id_bank_pertanyaan_objektif]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                            </td>
                            </tr>";
                      $noo++;
                    } 

                      if (isset($_GET[hapus])){
                        mysql_query("DELETE FROM rb_bank_pertanyaan_objektif where id_bank_pertanyaan_objektif='$_GET[hapus]'");
                        echo "<script>document.location='index.php?view=banksoal&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
                      }

              echo "</table>
                </div>
                </div>
            </div>";

}elseif($_GET[act]=='tambahobjektif'){
  if (isset($_POST[objektif])){
      $query = mysql_query("INSERT INTO rb_bank_pertanyaan_objektif VALUES('','$_GET[mapel]','$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]','$_POST[e]','$_POST[f]','$_POST[g]')");
        if ($query){
          echo "<script>document.location='index.php?view=banksoal&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=banksoal&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&gagal';</script>";
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
          echo "<script>document.location='index.php?view=banksoal&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=banksoal&act=lihatobjektif&mapel=$_GET[mapel]&tingkat=$_GET[tingkat]&gagal';</script>";
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