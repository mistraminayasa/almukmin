<?php if ($_GET[act]==''){ ?>
<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Data Predikat / Grade Nilai </h3>
            <?php if($_SESSION[level]!='kepala'){ ?>
            <a class='pull-right btn btn-primary btn-sm' href='index.php?view=predikat&act=tambah'>Tambahkan Data</a>
            <?php } ?>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive no-padding">
            <?php 
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
                ?>
            <table id="example1" class="table table-hover">
                <thead>
                    <tr>
                        <th style='width:40px'>No</th>
                        <th>Status</th>
                        <th>Dari</th>
                        <th>Sampai</th>
                        <th>Grade</th>
                        <th>Keterangan</th>
                        <?php if($_SESSION[level]!='kepala'){ ?>
                        <th style='width:70px'>Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $tampil = mysql_query("SELECT a.id_predikat, a.nilai_a, a.nilai_b, a.grade, a.keterangan, a.kode_kelas as akelas,  b.nama_kelas FROM rb_predikat a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas ORDER BY a.id_predikat DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                      if ($r[akelas]=='0'){
                          $kelas = 'Lainnya';
                      }else{
                          $kelas = $r[akelas];
                      }
                    echo "<tr><td>$no</td>
                              <td>$kelas</td>
                              <td>$r[nilai_a]</td>
                              <td>$r[nilai_b]</td>
                              <td>$r[grade]</td>
                              <td>$r[keterangan]</td>";
                              if($_SESSION[level]!='kepala'){
                        echo "<td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=predikat&act=edit&id=$r[id_predikat]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?view=predikat&hapus=$r[id_predikat]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }
                      if (isset($_GET[hapus])){
                          mysql_query("DELETE FROM rb_predikat where id_predikat='$_GET[hapus]'");
                          echo "<script>document.location='index.php?view=predikat';</script>";
                      }

                  ?>
                </tbody>
            </table>
        </div><!-- /.box-body -->
    </div><!-- /.box -->
</div>
<?php 
}elseif($_GET[act]=='edit'){
    if (isset($_POST[update])){
        $query = mysql_query("UPDATE rb_predikat SET kode_kelas = '$_POST[aa]', 
                                         nilai_a = '$_POST[a]',
                                         nilai_b = '$_POST[b]',
                                         grade = '$_POST[c]',
                                         keterangan = '$_POST[d]' where id_predikat='$_POST[id]'");
        if ($query){
          echo "<script>document.location='index.php?view=predikat&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=predikat&gagal';</script>";
        }
    }
    $edit = mysql_query("SELECT * FROM rb_predikat where id_predikat='$_GET[id]'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Predikat / Grade</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[id_predikat]'>
                    <tr><th scope='row'>Kelas</th> <td><select class='form-control' name='aa'>"; 
                                                          echo "<option value='0' selected>Lainnya</option>";
                                                          $kelas = mysql_query("SELECT * FROM rb_kelas");
                                                          while ($k = mysql_fetch_array($kelas)){
                                                            if ($s[kode_kelas]==$k[kode_kelas]){
                                                              echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                                                            }else{
                                                              echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
                                                            }
                                                          }
                                                      echo "</select></td></tr>
                    <tr><th width='120px' scope='row'>Dari</th> <td><input type='text' class='form-control' name='a' value='$s[nilai_a]'> </td></tr>
                    <tr><th scope='row'>Sampai</th> <td><input type='text' class='form-control' name='b' value='$s[nilai_b]'> </td></tr>
                    <tr><th scope='row'>Grade</th> <td><input type='text' class='form-control' name='c' value='$s[grade]'> </td></tr>
                    <tr><th scope='row'>Keterangan</th> <td><input type='text' class='form-control' name='d' value='$s[keterangan]'> </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=predikat'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif($_GET[act]=='tambah'){
    if (isset($_POST[tambah])){
        $query = mysql_query("INSERT INTO rb_predikat VALUES('','$_POST[aa]','$_POST[a]','$_POST[b]','$_POST[c]','$_POST[d]')");
        if ($query){
          echo "<script>document.location='index.php?view=predikat&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=predikat&gagal';</script>";
        }
    }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Predikat / Grade</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th scope='row'>Kelas</th> <td><select class='form-control' name='aa'>"; 
                                                          echo "<option value='0' selected>Lainnya</option>";
                                                          $kelas = mysql_query("SELECT * FROM rb_kelas");
                                                          while ($k = mysql_fetch_array($kelas)){
                                                              echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
                                                          }
                                                      echo "</select></td></tr>
                    <tr><th width='120px' scope='row'>Dari</th> <td><input type='text' class='form-control' name='a'> </td></tr>
                    <tr><th scope='row'>Sampai</th> <td><input type='text' class='form-control' name='b'> </td></tr>
                    <tr><th scope='row'>Grade</th> <td><input type='text' class='form-control' name='c'> </td></tr>
                    <tr><th scope='row'>Keterangan</th> <td><input type='text' class='form-control' name='d'> </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=predikat'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>