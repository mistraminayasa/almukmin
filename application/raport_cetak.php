<?php if ($_GET[act]==''){ 
  $k = mysql_fetch_array(mysql_query("SELECT * FROM rb_kelas where kode_kelas='$_GET[id]'"));
  $t = mysql_fetch_array(mysql_query("SELECT * FROM rb_tahun_akademik where id_tahun_akademik='$_GET[tahun]'"));
?>
            <div class="col-xs-12">  
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Cetak Raport Semester Siswa <?php echo $_GET[tahun]; ?></h3>
                  <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
                    <input type="hidden" name='view' value='raportcetak'>
                    <select name='tahun' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Pilih Tahun Akademik -</option>";
                            $tahun = mysql_query("SELECT * FROM rb_tahun_akademik");
                            while ($k = mysql_fetch_array($tahun)){
                              if ($_GET[tahun]==$k[id_tahun_akademik]){
                                echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
                              }else{
                                echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
                              }
                            }
                        ?>
                    </select>
                    <select name='id' style='padding:4px'>
                        <?php 
                            echo "<option value=''>- Filter Kelas -</option>";
                            $kelas = mysql_query("SELECT * FROM rb_kelas");
                            while ($k = mysql_fetch_array($kelas)){
                              if ($_GET[id]==$k[kode_kelas]){
                                echo "<option value='$k[kode_kelas]' selected>$k[kode_kelas] - $k[nama_kelas]</option>";
                              }else{
                                echo "<option value='$k[kode_kelas]'>$k[kode_kelas] - $k[nama_kelas]</option>";
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
                        <th>No</th>
                        <th>NIPD</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Jenis Kelamin</th>
                        <th>Ranking</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                  <?php 
                    $tampil = mysql_query("SELECT * FROM rb_siswa a
                                              JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                                where a.kode_kelas='$_GET[id]' ORDER BY a.id_siswa");
                    $no = 1;
                    while($r=mysql_fetch_array($tampil)){
                    $ta = mysql_fetch_array(mysql_query("SELECT sum(d.total) as total FROM (SELECT kodejdwl, nisn, (sum(uh)+sum(tugas))/4 as total FROM ((SELECT kodejdwl, nisn, sum(nilai1)/count(nisn) as uh, sum(nilai2)/count(nisn) as tugas FROM `rb_nilai_pengetahuan` where nisn='$r[nisn]' GROUP BY kodejdwl) UNION (SELECT kodejdwl, nisn, nilai1, (nilai2*2) FROM rb_nilai_pengetahuan_semester where nisn='$r[nisn]')) as a GROUP by a.kodejdwl) as d"));
                    $rapnk = mysql_fetch_array(mysql_query("SELECT sum(raport) as raporttotal FROM (SELECT sum(GREATEST(nilai1,nilai2,nilai3,nilai4,nilai5,nilai6))/count(nisn) as raport FROM rb_nilai_keterampilan where nisn='$r[nisn]' GROUP BY kodejdwl) as a"));
                    $nilai_total = number_format($ta[total])+number_format($rapnk[raporttotal]);
                    $rank = mysql_fetch_array(mysql_query("SELECT * FROM rb_peringkat where nisn='$r[nisn]' AND kode_kelas='$r[kode_kelas]' AND id_tahun_akademik='$_GET[tahun]'"));
                    
                    $cek = mysql_num_rows(mysql_query("SELECT * FROM rb_peringkat where nisn='$r[nisn]' AND kode_kelas='$r[kode_kelas]' AND id_tahun_akademik='$_GET[tahun]'"));
                    if ($cek >= 1){
                      mysql_query("UPDATE rb_peringkat SET nilai_total='$nilai_total' where nisn='$r[nisn]' AND kode_kelas='$r[kode_kelas]' AND id_tahun_akademik='$_GET[tahun]'");
                    }else{
                      mysql_query("INSERT INTO rb_peringkat VALUES('','$r[nisn]','$r[kode_kelas]','$_GET[tahun]','$nilai_total','')");
                    }

                    echo "<tr><td width=40px>$no</td>
                              <td>$r[nipd]</td>
                              <td>$r[nisn]</td>
                              <td>$r[nama]</td>
                              <td>$r[jenis_kelamin]</td>
                              <td align=center>$rank[rank]</td>
                              <td width='420px'><center>
                                <a target='_BLANK' class='btn btn-success btn-xs' href='print_raport/print-hal.php?id=$r[nisn]&kelas=$r[kode_kelas]&tahun=$_GET[tahun]'><span class='glyphicon glyphicon-print'></span> Cover</a>
                                <a target='_BLANK' class='btn btn-success btn-xs' href='print_raport/print-hal1.php?id=$r[nisn]&kelas=$r[kode_kelas]&tahun=$_GET[tahun]'><span class='glyphicon glyphicon-print'></span> Hal 1</a>
                                <a target='_BLANK' class='btn btn-success btn-xs' href='print_raport/print-hal2.php?id=$r[nisn]&kelas=$r[kode_kelas]&tahun=$_GET[tahun]'><span class='glyphicon glyphicon-print'></span> Hal 2</a>
                                <a target='_BLANK' class='btn btn-success btn-xs' href='print_raport/print-hal3.php?id=$r[nisn]&kelas=$r[kode_kelas]&tahun=$_GET[tahun]'><span class='glyphicon glyphicon-print'></span> Hal 3</a>
                                <a target='_BLANK' class='btn btn-success btn-xs' href='print_raport/print-hal4.php?id=$r[nisn]&kelas=$r[kode_kelas]&tahun=$_GET[tahun]'><span class='glyphicon glyphicon-print'></span> Hal 4</a>
                                <a target='_BLANK' class='btn btn-success btn-xs' href='print_raport/print-hal5.php?id=$r[nisn]&kelas=$r[kode_kelas]&tahun=$_GET[tahun]'><span class='glyphicon glyphicon-print'></span> Hal 5</a>
                                <a target='_BLANK' class='btn btn-success btn-xs' href='print_raport/print-hal6.php?id=$r[nisn]&kelas=$r[kode_kelas]&tahun=$_GET[tahun]'><span class='glyphicon glyphicon-print'></span> Hal 6</a>
                              </center></td>";
                            echo "</tr>";
                      $no++;
                      }

                      $rank = mysql_query("SELECT * FROM rb_peringkat ORDER BY nilai_total DESC");
                      $ii = 1;
                      while ($ra = mysql_fetch_array($rank)){
                        mysql_query("UPDATE rb_peringkat SET rank='$ii' where nisn='$ra[nisn]' AND kode_kelas='$ra[kode_kelas]' AND id_tahun_akademik='$_GET[tahun]'");
                        $ii++;
                      }
                  ?>
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
                <?php 
                    if ($_GET[kelas] == '' AND $_GET[tahun] == ''){
                        echo "<center style='padding:60px; color:red'>Silahkan Memilih Tahun akademik dan Kelas Terlebih dahulu...</center>";
                    }
                ?>
              </div>
            </div>

<?php } ?>