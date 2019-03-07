<?php 
if ($_GET[act]==''){ 
?> 
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Pembayaran Keuangan Siswa </h3>
                  <a target='_BLANK' class='btn btn-sm btn-success pull-right' href='print-keuangan.php?biaya=<?php echo $_GET[biaya]; ?>&kelas=<?php echo $_GET[kelas]; ?>'>Print Laporan</a>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='laporankeuangan'>
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
                        echo "
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
                          echo "</tr>";
                      $no++;
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
}
?>