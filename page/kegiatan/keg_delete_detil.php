<?php
if ($_SESSION['sesi_level'] > 1) {
$keg_d_id=$lvl3;
$db_keg = new db();
$conn_keg = $db_keg -> connect();
$sql_delete_keg = $conn_keg -> query("select * from keg_detil where keg_d_id='$keg_d_id'");
$cek=$sql_delete_keg->num_rows;
$update_nilai='';
if ($cek>0) {
	echo '<div class="margin10px"><div class="alert alert-danger" role="alert">'; //untuk alert
	$r=$sql_delete_keg->fetch_object();
	$keg_id=$r->keg_id;
	$keg_d_unitkerja=$r->keg_d_unitkerja;
  if ($_SESSION['sesi_level'] > 2) {
	  //query delete langsung
	  if ($_SESSION['sesi_level'] > 3) {
	  	$sql_hapus=$conn_keg -> query("delete from keg_detil where keg_d_id='$keg_d_id'");
		if ($sql_hapus) { $update_nilai=1;echo '(BERHASIL) Konfirmasi '.$JenisDetilKegiatan[$r->keg_d_jenis].' berhasil di hapus'; }
		else { $update_nilai=0;echo '(ERROR) Konfirmasi '.$JenisDetilKegiatan[$r->keg_d_jenis].' tidak bisa dihapus'; }
	  }
	  else {
	  	$keg_unit=get_unitkerja_kegiatan($keg_id);
	  	if ($_SESSION['sesi_unitkerja']==$keg_unit) {
	  		$sql_hapus=$conn_keg -> query("delete from keg_detil where keg_d_id='$keg_d_id'");
			if ($sql_hapus) { $update_nilai=1;echo '(BERHASIL) Konfirmasi '.$JenisDetilKegiatan[$r->keg_d_jenis].' berhasil di hapus'; }
			else { $update_nilai=0;echo '(ERROR) Konfirmasi '.$JenisDetilKegiatan[$r->keg_d_jenis].' tidak bisa dihapus'; }
	  	}
	  	else {
	  		$parent_unit=get_parent_kode($keg_unit);
			if ($_SESSION['sesi_unitkerja']==$parent_unit) {
				$sql_hapus=$conn_keg -> query("delete from keg_detil where keg_d_id='$keg_d_id'");
				if ($sql_hapus) { $update_nilai=1;echo '(BERHASIL) Konfirmasi '.$JenisDetilKegiatan[$r->keg_d_jenis].' berhasil di hapus'; }
				else { $update_nilai=0;echo '(ERROR) Konfirmasi '.$JenisDetilKegiatan[$r->keg_d_jenis].' tidak bisa dihapus'; }
			}
			else {
				echo '<i class="fa fa-exclamation-circle fa-2x" aria-hidden="true"></i> Level user <strong>'.$_SESSION['sesi_user_id'].'</strong> tidak bisa menghapus '.$JenisDetilKegiatan[$r->keg_d_jenis].' ini';
			}
	  	}
	  }
	  
  }
  else {
	if ($r->keg_d_jenis==1) {
		//query delete
		if ($r->keg_d_unitkerja == $_SESSION['sesi_unitkerja']) {
		  $sql_hapus=$conn_keg -> query("delete from keg_detil where keg_d_id='$keg_d_id'");
		  if ($sql_hapus) { $update_nilai=1;echo '(BERHASIL) Konfirmasi '.$JenisDetilKegiatan[$r->keg_d_jenis].' berhasil di hapus'; }
		  else { $update_nilai=2;echo '(ERROR) Konfirmasi '.$JenisDetilKegiatan[$r->keg_d_jenis].' tidak bisa dihapus';}
		}
		else {
			echo '<i class="fa fa-exclamation-circle fa-2x" aria-hidden="true"></i> unit kerja user <strong>'.$_SESSION['sesi_user_id'].'</strong> tidak bisa mengakses menu ini';
		}
	}
	else {
		echo '<i class="fa fa-exclamation-circle fa-2x" aria-hidden="true"></i>Level user <strong>'.$_SESSION['sesi_user_id'].'</strong> tidak mengakses menu ini';
	}
  }
  echo '</div></div>
  <a class="btn btn-success" href="'.$url.'/'.$page.'/view/'.$r->keg_id.'"><i class="fa fa-chevron-left" aria-hidden="true"></i> Kembali</a>';
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
	$keg_id=$r->keg_id;
	$keg_d_unitkerja=$r->keg_d_unitkerja;
	$sql_update_nilai=$conn_keg -> query("update keg_target set keg_t_point_waktu='$nilai_waktu', keg_t_point_jumlah='$nilai_volume', keg_t_point='$nilai_total' where keg_id='$keg_id' and keg_t_unitkerja='$keg_d_unitkerja'") or die(mysqli_error($conn));
	}
else {
	echo '<div class="margin10px"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle fa-2x" aria-hidden="true"></i> Data kegiatan ini tidak tersedia</div></div>';
	}
}
else {
	echo '<div class="margin10px"><div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-circle fa-2x" aria-hidden="true"></i> Level user <strong>'.$_SESSION['sesi_user_id'].'</strong> tidak bisa mengakses menu ini </div></div>';
}

?>
