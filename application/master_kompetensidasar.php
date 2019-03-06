<?php if ($_GET[act]==''){ ?>
<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Kompetensi Dasar</h3>
            <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                <input type="hidden" name='view' value='kompetensidasar'>
                <select name='tingkat' style='padding:4px'>
                    <option value='0' selected>- Pilih Tingkat -</option>
                    <?php 
                          $tingkat = array('VII','VIII','IX', 'X', 'XI', 'XII');
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
        <div class="box-body table-responsive no-padding">
            <table id="example2" class="table table-hover">
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
                      $tampil = mysql_query("SELECT * FROM rb_mata_pelajaran a LEFT JOIN rb_jurusan b ON a.kode_jurusan=b.kode_jurusan where a.tingkat='$_GET[tingkat]' AND a.kode_kurikulum='$kurikulum[kode_kurikulum]'");
                    }
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[kode_pelajaran]</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[nama_jurusan]</td>";
                              if($_SESSION[level]!='kepala'){
                                echo "<td style='width:200px !important'><center>
                                        <a class='btn btn-success btn-xs' title='Lihat Kompetensi Dasar' href='index.php?view=kompetensidasar&act=lihat&id=$r[kode_pelajaran]&tingkat=$_GET[tingkat]'><span class='glyphicon glyphicon-search'></span> Lihat Kompetensi</a>
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
}elseif($_GET[act]=='lihat'){
    $d = mysql_fetch_array(mysql_query("SELECT * FROM `rb_mata_pelajaran` where kode_pelajaran='$_GET[id]'"));
            echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Kompetensi Dasar</h3>";
                  if($_SESSION[level]!='kepala'){
                      echo "<a class='pull-right btn btn-primary btn-sm' href='index.php?view=kompetensidasar&act=tambah&jdwl=$_GET[id]&tingkat=$_GET[tingkat]'>Tambahkan Kompetensi Dasar</a>";
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
                        <tr><th scope='row'>Nama Mapel</th>      <td>$d[namamatapelajaran]</td></tr>
                      </tbody>
                  </table>
                  </div>

                  <table id='example' class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Ranah</th>
                        <th>Kode</th>
                        <th>Kompetensi Dasar</th>";
                        if($_SESSION[level]!='kepala'){
                            echo "<th>Action</th>";
                        }
                      echo "</tr>
                    </thead>
                    <tbody>";
                      $tampil = mysql_query("SELECT * FROM rb_kompetensi_dasar where kode_pelajaran='$_GET[id]' ORDER BY kd ASC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[ranah]</td>
                              <td>$r[kd]</td>
                              <td>$r[kompetensi_dasar]</td>";
                              if($_SESSION[level]!='kepala'){
                                echo "<td style='width:80px !important'><center>
                                        <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=kompetensidasar&act=edit&id=$r[id_kompetensi_dasar]&jdwl=$_GET[id]&tingkat=$_GET[tingkat]'><span class='glyphicon glyphicon-edit'></span></a>
                                        <a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?view=kompetensidasar&act=lihat&hapus=$r[id_kompetensi_dasar]&jdwl=$_GET[id]&tingkat=$_GET[tingkat]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                                      </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET[hapus])){
                        mysql_query("DELETE FROM rb_kompetensi_dasar where id_kompetensi_dasar='$_GET[hapus]'");
                        echo "<script>document.location='index.php?view=kompetensidasar&act=lihat&id=$_GET[jdwl]&tingkat=$_GET[tingkat]';</script>";
                      }

                    echo "<tbody>
                  </table>
                </div>
                </div>
            </div>";

}elseif($_GET[act]=='tambah'){
    if (isset($_POST[tambah])){
        $query = mysql_query("INSERT INTO rb_kompetensi_dasar VALUES('','$_POST[jdwl]','$_POST[kd]','$_POST[e]','$_POST[f]','".date('Y-m-d H:i:s')."')");
        if ($query){
          echo "<script>document.location='index.php?view=kompetensidasar&act=lihat&id=$_POST[jdwl]&tingkat=$_GET[tingkat]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=kompetensidasar&act=lihat&id=$_POST[jdwl]&tingkat=$_GET[tingkat]&gagal';</script>";
        }
    }

    $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[jdwl]'"));

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Kompetensi Dasar</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
                    <tr><th width='140px' scope='row'>Mata Pelajaran</th>   <td><select class='form-control' name='c'>"; 
                                                $mapel = mysql_query("SELECT * FROM rb_mata_pelajaran");
                                                while($a = mysql_fetch_array($mapel)){
                                                  if ($e[kode_pelajaran]==$a[kode_pelajaran]){
                                                    echo "<option value='$a[kode_pelajaran]' selected>$a[namamatapelajaran]</option>";
                                                  }
                                                }
                                                echo "</select>
                    </td></tr>
                   
                    <tr><th scope='row'>Ranah</th>   <td><select class='form-control' name='e'> 
                                                              <option value='0' selected>- Pilih -</option>
                                                              <option value='pengetahuan'>Pengetahuan</option>
                                                              <option value='keterampilan'>Keterampilan</option>
                                                              <option value='sikap'>Sikap</option>
                                                            </select>
                    </td></tr>
                    <tr><th scope='row'>Kode</th>  <td><input type='text' class='form-control' name='kd'></td></tr>
                    <tr><th scope='row'>Kompetensi Dasar</th>  <td><input type='text' class='form-control' name='f'></td></tr>
                    
                    </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='#'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";

}elseif($_GET[act]=='edit'){
    if (isset($_POST[update])){
        $query = mysql_query("UPDATE rb_kompetensi_dasar SET kd = '$_POST[kd]',
                                                    ranah = '$_POST[e]',
                                                    kompetensi_dasar = '$_POST[f]' where id_kompetensi_dasar='$_POST[id]'");
        if ($query){
          echo "<script>document.location='index.php?view=kompetensidasar&act=lihat&id=$_POST[jdwl]&tingkat=$_GET[tingkat]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=kompetensidasar&act=lihat&id=$_POST[jdwl]&tingkat=$_GET[tingkat]&gagal';</script>";
        }
    }
    $e = mysql_fetch_array(mysql_query("SELECT * FROM rb_kompetensi_dasar where id_kompetensi_dasar='$_GET[id]'"));
    if ($e[semester]=='1'){ $status = 'Ganjil'; }else{ $status = 'Genap'; }
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Kompetensi Dasar</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <input type='hidden' name='jdwl' value='$_GET[jdwl]'>
                  <input type='hidden' name='id' value='$_GET[id]'>
                    <tr><th width='140px' scope='row'>Mata Pelajaran</th>   <td><select class='form-control' name='c'>"; 
                                                $mapel = mysql_query("SELECT * FROM rb_mata_pelajaran");
                                                while($a = mysql_fetch_array($mapel)){
                                                  if ($e[kode_pelajaran]==$a[kode_pelajaran]){
                                                    echo "<option value='$a[kode_pelajaran]' selected>$a[namamatapelajaran]</option>";
                                                  }
                                                }
                                                echo "</select>
                    </td></tr>
                   
                    <tr><th scope='row'>Ranah</th>   <td><select style='text-transform:capitalize' class='form-control' name='e'> 
                                                              <option value='$e[ranah]' selected>$e[ranah]</option>
                                                              <option value='pengetahuan'>Pengetahuan</option>
                                                              <option value='keterampilan'>Keterampilan</option>
                                                              <option value='sikap'>Sikap</option>
                                                            </select>
                    </td></tr>
                    <tr><th scope='row'>Kode</th>  <td><input type='text' class='form-control' name='kd' value='$e[kd]'></td></tr>
                    <tr><th scope='row'>Kompetensi Dasar</th>  <td><input type='text' class='form-control' value='$e[kompetensi_dasar]' name='f'></td></tr>
                    
                    </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='#'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>