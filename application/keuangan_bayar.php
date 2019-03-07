<?php 
if ($_GET[act]==''){ 
?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Pembayaran Keuangan Siswa </h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='pembayaransiswa'>
                    <select name='biaya' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Jenis Biaya -</option>";
                            $biaya = mysql_query("SELECT * FROM rb_keuangan_jenis");
                            while ($k = mysql_fetch_array($biaya)){
                              if ($_GET[biaya]==$k[id_keuangan_jenis]){
                                echo "<option value='$k[id_keuangan_jenis]' selected>$k[nama_jenis]</option>";
                              }else{
                                echo "<option value='$k[id_keuangan_jenis]'>$k[nama_jenis]</option>";
                              }
                            }
                        ?>
                    </select>
                    <select name='kelas' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Filter Kelas -</option>";
                            $kelas = mysql_query("SELECT * FROM rb_kelas");
                            while ($k = mysql_fetch_array($kelas)){
                              if ($_GET[kelas]==$k[kode_kelas]){
                                echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                              }else{
                                echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
                              }
                            }
                        ?>
                    </select>

                    <input type="submit" style='margin-top:-4px' class='btn btn-info btn-sm' value='Lihat'>
                  </form>
                </div><!-- /.box-header -->
                <div class="box-body">

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

                <form action='' method='POST'>
                <input type="hidden" name='kelas' value='<?php echo $_GET[kelas]; ?>'>
                <?php 
                $j = mysql_fetch_array(mysql_query("SELECT * FROM `rb_keuangan_jenis` where id_keuangan_jenis='$_GET[biaya]'"));
                $th = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik where aktif='Ya'"));
                echo "<table id='example1' class='table table-bordered table-striped'>
                    <thead>
                    <tr><th width='30px'>No</th>
                        <th>NIPD</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>";
                        if ($_GET[kelas] != '' AND $_GET[biaya] != ''){
                          if ($j[total_beban] != '0'){
                            echo "<th>Status</th>";
                          }
                        }

                        echo "<th width='140px'>Action</th>
                        </tr>
                    </thead>
                    <tbody>";

                  if ($_GET[kelas] != '' AND $_GET[biaya] != ''){
                    $tampil = mysql_query("SELECT * FROM rb_siswa a LEFT JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas 
                                              LEFT JOIN rb_jenis_kelamin c ON a.id_jenis_kelamin=c.id_jenis_kelamin 
                                                LEFT JOIN rb_jurusan d ON b.kode_jurusan=d.kode_jurusan 
                                                  where a.kode_kelas='$_GET[kelas]' ORDER BY a.id_siswa");
                  }
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    $t = mysql_fetch_array(mysql_query("SELECT sum(total_bayar) as total FROM `rb_keuangan_bayar` where id_keuangan_jenis='$_GET[biaya]' AND kode_kelas='$r[kode_kelas]' AND nisn='$r[nisn]' AND tahun_akademik='$th[id_tahun_akademik]'"));
                    if ($j[total_beban] <= $t[total]) { $status = 'Lunas'; $class = 'green'; }else{ $status = 'Belum Lunas'; $class = 'red'; }
                    echo "<tr><td>$no</td>
                              <td>$r[nipd]</td>
                              <td>$r[nisn]</td>
                              <td>$r[nama]</td>
                              <td>$r[nama_kelas]</td>";
                              if ($_GET[kelas] != '' AND $_GET[biaya] != ''){
                                if ($j[total_beban] != '0'){
                                  echo "<td><i style='color:$class'>$status</i></td>";
                                }
                              }

                              echo "<td><center>
                                  <a class='btn btn-info btn-xs' title='Lihat Detail' href='index.php?view=pembayaransiswa&act=detail&biaya=$_GET[biaya]&nisn=$r[nisn]'><span class='glyphicon glyphicon-search'></span> Lihat Detail</a> ";
                                if ($j[total_beban] != '0'){
                                  if ($status == 'Lunas'){
                                    echo "<a class='btn btn-default btn-xs' title='Lihat Detail' href='index.php?view=pembayaransiswa&act=detail&biaya=$_GET[biaya]&nisn=$r[nisn]'><span class='glyphicon glyphicon-ok'></span> Lunas</a> ";
                                  }else{
                                      echo "<a class='btn btn-success btn-xs' title='Bayar Lunas' href='index.php?view=pembayaransiswa&biaya=$_GET[biaya]&kelas=$_GET[kelas]&nisn=$r[nisn]&lunas' onclick=\"return confirm('Apa anda yakin untuk mengubah Status Menjadi Lunas?')\"><span class='glyphicon glyphicon-ok'></span> Lunas</a> ";
                                  }
                                }
                              echo "</center></td>
                          </tr>";
                      $no++;
                      }

                      if (isset($_GET[lunas])){
                          $th = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik where aktif='Ya'"));
                          $j = mysql_fetch_array(mysql_query("SELECT * FROM `rb_keuangan_jenis` where id_keuangan_jenis='$_GET[biaya]'"));
                          $t = mysql_fetch_array(mysql_query("SELECT sum(total_bayar) as total FROM `rb_keuangan_bayar` where id_keuangan_jenis='$_GET[biaya]' AND kode_kelas='$_GET[kelas]' AND nisn='$_GET[nisn]' AND tahun_akademik='$th[id_tahun_akademik]'"));
                          $total = $j[total_beban]-$t[total];
                          $query = mysql_query("INSERT INTO rb_keuangan_bayar VALUES('','$j[id_keuangan_jenis]','$_GET[kelas]','$_GET[nisn]','$th[id_tahun_akademik]','$total','".date('Y-m-d H:i:s')."')");
                          if ($query){
                            echo "<script>document.location='index.php?view=pembayaransiswa&biaya=$_GET[biaya]&kelas=$_GET[kelas]&sukses';</script>";
                          }else{
                            echo "<script>document.location='index.php?view=pembayaransiswa&biaya=$_GET[biaya]&kelas=$_GET[kelas]&gagal';</script>";
                          }
                      }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <?php 
                    if ($_GET[kelas] == '' AND $_GET[biaya] == ''){
                        echo "<center style='padding:60px; color:red'>Silahkan Memilih Jenis Biaya dan Kelas Terlebih dahulu...</center>";
                    }
                ?>
              </div><!-- /.box -->
              </form>
            </div>
<?php 
}elseif($_GET[act]=='detail'){
    if (isset($_POST[proses])){
        $th = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik where aktif='Ya'"));
        $query = mysql_query("INSERT INTO rb_keuangan_bayar VALUES('','$_POST[a]','$_POST[b]','$_POST[c]','$th[id_tahun_akademik]','$_POST[bayar]','".date('Y-m-d H:i:s')."')");
        if ($query){
          echo "<script>document.location='index.php?view=pembayaransiswa&act=detail&biaya=$_POST[a]&nisn=$_POST[c]&sukses';</script>";
        }else{
          echo "<script>document.location='index.php?view=pembayaransiswa&act=detail&biaya=$_POST[a]&nisn=$_POST[c]&gagal';</script>";
        }
    }

    $d = mysql_fetch_array(mysql_query("SELECT * FROM `rb_siswa` a JOIN rb_kelas b ON a.kode_kelas=b.kode_kelas where a.nisn='$_GET[nisn]'"));
    $j = mysql_fetch_array(mysql_query("SELECT * FROM `rb_keuangan_jenis` where id_keuangan_jenis='$_GET[biaya]'"));
    $t = mysql_fetch_array(mysql_query("SELECT sum(total_bayar) as total FROM `rb_keuangan_bayar` where id_keuangan_jenis='$_GET[biaya]' AND kode_kelas='$d[kode_kelas]' AND nisn='$_GET[nisn]'"));
    $sisa = $t[total]-$j[total_beban];
    if ($j[total_beban] <= $t[total]) { $status = 'Lunas'; $class = 'success'; }else{ $status = 'Belum Lunas'; $class = 'danger'; }
            echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Data Pembayaran Keuangan Siswa</h3>
                  <a class='pull-right btn btn-sm btn-warning' href='index.php?view=pembayaransiswa&biaya=$_GET[biaya]&kelas=$d[kode_kelas]'>Kembali</a>
                </div>
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
                  <form action='' method='POST'>
                  <table class='table table-condensed table-hover'>
                      <tbody>
                        <tr><th width='120px' scope='row'>Nama Kelas</th> <td>$d[nama_kelas]</td></tr>
                        <tr><th scope='row'>Nama Siswa</th>           <td>$d[nama]</td></tr>
                        <tr><th scope='row'>Jenis Biaya</th>           <td>$j[nama_jenis]</td></tr>";

                        if ($sisa < 0){
                          echo "<input type='hidden' value='$j[id_keuangan_jenis]' name='a'>
                                <input type='hidden' value='$d[kode_kelas]' name='b'>
                                <input type='hidden' value='$_GET[nisn]' name='c'>
                                <tr><th scope='row'>Bayar Sisa</th>           <td><input type='text' name='bayar' value=''> <input type='submit' name='proses' value='Proses'></td></tr>";
                        }

                        if ($j[total_beban] > '0'){
                        echo "<tr><th scope='row'>Total Beban</th>           <td>Rp ".number_format($j[total_beban])."</td></tr>
                                <tr><th scope='row'>Total Bayar</th>           <td>Rp ".number_format($t[total])."</td></tr>
                                <tr class='alert alert-$class'><th scope='row'>Sisa</th>           <td>Rp ".number_format($t[total]-$j[total_beban])." <small class='pull-right'>$status</small></td></tr>";
                        }

                        if ($j[total_beban] == '0'){
                          echo "<tr><th scope='row'>Total </th>           <td>Rp ".number_format($t[total])."</td></tr>
                              <input type='hidden' value='$j[id_keuangan_jenis]' name='a'>
                              <input type='hidden' value='$d[kode_kelas]' name='b'>
                              <input type='hidden' value='$_GET[nisn]' name='c'>
                              <tr><th scope='row'>Bayar</th>           <td><input type='text' name='bayar' value=''> <input type='submit' name='proses' value='Proses'></td></tr>";
                        }

                      echo "</tbody>
                  </table>
                  </form>
                  </div>


                  <table id='example' class='table table-bordered table-striped'>
                    <thead>
                      <tr>
                        <th style='width:20px'>No</th>
                        <th>Total Bayar</th>
                        <th>Tanggal Bayar</th>";
                        if($_SESSION[level]!='kepala'){
                            echo "<th>Action</th>";
                        }
                      echo "</tr>
                    </thead>
                    <tbody>";
                      $tampil = mysql_query("SELECT * FROM rb_keuangan_bayar where id_keuangan_jenis='$_GET[biaya]' AND kode_kelas='$d[kode_kelas]' AND nisn='$_GET[nisn]' ORDER BY id_keuangan_bayar DESC");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    $ex = explode(' ',$r[waktu_bayar]);
                    echo "<tr><td>$no</td>
                              <td>Rp ".number_format($r[total_bayar])."</td>
                              <td>".tgl_indo($ex[0])." ".$ex[1]." WIB</td>";
                              if($_SESSION[level]!='kepala'){
                                echo "<td style='width:80px !important'><center>
                                        <a class='btn btn-danger btn-xs' title='Delete Data' href='index.php?view=pembayaransiswa&act=detail&biaya=$_GET[biaya]&nisn=$_GET[nisn]&hapus=$r[id_keuangan_bayar]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                                      </center></td>";
                              }
                            echo "</tr>";
                      $no++;
                      }

                      if (isset($_GET[hapus])){
                        mysql_query("DELETE FROM rb_keuangan_bayar where id_keuangan_bayar='$_GET[hapus]'");
                        echo "<script>document.location='index.php?view=pembayaransiswa&act=detail&biaya=$_GET[biaya]&nisn=$_GET[nisn]';</script>";
                      }

                    echo "<tbody>
                  </table>
                </div>
                </div>
            </div>";
}
?>