<?php if ($_GET[act]==''){ ?>
<div class="col-xs-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Data Kelompok Mata Pelajaran </h3>
            <?php if($_SESSION[level]!='kepala'){ ?>
            <a class='pull-right btn btn-primary btn-sm' href='index.php?view=kelompokmapel&act=tambah'>Tambahkan
                Data</a>
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
                        <th>Jenis</th>
                        <th>Nama Kelompok</th>
                        <?php if($_SESSION[level]!='kepala'){ ?>
                        <th style='width:70px'>Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $tampil = mysql_query("SELECT * FROM rb_kelompok_mata_pelajaran ORDER BY id_kelompok_mata_pelajaran DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    echo "<tr><td>$no</td>
                              <td>$r[jenis_kelompok_mata_pelajaran]</td>
                              <td>$r[nama_kelompok_mata_pelajaran]</td>";
                              if($_SESSION[level]!='kepala'){
                        echo "<td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='index.php?view=kelompokmapel&act=edit&id=$r[id_kelompok_mata_pelajaran]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?view=kelompokmapel&hapus=$r[id_kelompok_mata_pelajaran]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET[hapus])){
                          mysql_query("DELETE FROM rb_kelompok_mata_pelajaran where id_kelompok_mata_pelajaran='$_GET[hapus]'");
                          echo "<script>document.location='index.php?view=kelompokmapel';</script>";
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
        $query = mysql_query("UPDATE rb_kelompok_mata_pelajaran SET jenis_kelompok_mata_pelajaran = '$_POST[a]',
                                         nama_kelompok_mata_pelajaran = '$_POST[b]' where id_kelompok_mata_pelajaran='$_POST[id]'");
        if ($query){
          echo "<script>document.location='index.php?view=kelompokmapel&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=kelompokmapel&gagal';</script>";
        }
    }
    $edit = mysql_query("SELECT * FROM rb_kelompok_mata_pelajaran where id_kelompok_mata_pelajaran='$_GET[id]'");
    $s = mysql_fetch_array($edit);
    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Kelompok Mata Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[id_kelompok_mata_pelajaran]'>
                    <tr><th width='120px' scope='row'>Jenis</th> <td><input type='text' class='form-control' name='a' value='$s[jenis_kelompok_mata_pelajaran]'> </td></tr>
                    <tr><th width='120px' scope='row'>Nama Kelompok</th> <td><input type='text' class='form-control' name='b' value='$s[nama_kelompok_mata_pelajaran]'> </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='index.php?view=kelompokmapel'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}elseif($_GET[act]=='tambah'){
    if (isset($_POST[tambah])){
        $query = mysql_query("INSERT INTO rb_kelompok_mata_pelajaran VALUES('','$_POST[a]','$_POST[b]')");
        if ($query){
          echo "<script>document.location='index.php?view=kelompokmapel&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=kelompokmapel&gagal';</script>";
        }
    }

    echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Kelompok Mata Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='120px' scope='row'>Jenis</th> <td><input type='text' class='form-control' name='a'> </td></tr>
                    <tr><th width='120px' scope='row'>Nama Kelompok</th> <td><input type='text' class='form-control' name='b'> </td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='index.php?view=kelompokmapel'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>