<?php
if ($_POST['submit_keg']) {
$keg_id=$_POST['keg_id'];
$keg_d_unitkerja=$_POST['keg_d_unitkerja'];
$keg_d_jenis=2;
$keg_d_tgl=$_POST['keg_d_tgl'];
$keg_d_jumlah=$_POST['keg_d_jumlah'];
$waktu_lokal=date("Y-m-d H:i:s");
$created=$_SESSION['sesi_user_id'];

//print_r($_POST['keg_kabkota']);
//var_dump($_POST['keg_kabkota']);
//echo '<br />';
$cek=cek_id_kegiatan($keg_id);
if ($cek==0) {
  echo 'Kegiatan : '. $nama_kegiatan .' tidak tersedia ada';
}
else {
$db = new db();
$conn = $db -> connect();
$sql_keg = $conn->query("insert into keg_detil(keg_id, keg_d_unitkerja, keg_d_tgl, keg_d_jumlah, keg_d_jenis, keg_d_dibuat_oleh, keg_d_dibuat_waktu, keg_d_diupdate_oleh, keg_d_ket) value('$keg_id', '$keg_d_unitkerja', '$keg_d_tgl', '$keg_d_jumlah', '$keg_d_jenis', '$created', '$waktu_lokal', '$created','$created')");
$nilai_point=get_nilai_kegiatan($keg_id,$keg_d_unitkerja);
if ($nilai_point!='') {
   $nilai_waktu=$nilai_point[0];
   $nilai_volume=$nilai_point[1];
   $nilai_total=$nilai_point[2];
}
else {
	$nilai_waktu='';
   $nilai_volume='';
   $nilai_total='';
}

$sql_update_nilai=$conn -> query("update keg_target set keg_t_point_waktu='$nilai_waktu', keg_t_point_jumlah='$nilai_volume', keg_t_point='$nilai_total' where keg_id='$keg_id' and keg_t_unitkerja='$keg_d_unitkerja'") or die(mysqli_error($conn));
if ($sql_keg) echo '(BERHASIL) data berhasil di simpan';
else echo '(ERROR) data tidak berhasil disimpan' ;
echo '<br />';
if ($sql_update_nilai) echo '(BERHASIL) data nilai berhasil disimpan';
else echo '(ERROR) data nilai tidak berhasil disimpan' ;

echo '<br /><a href="'.$url.'/'.$page.'/view/'.$keg_id.'">Kembali</a>';
}
}
?>
