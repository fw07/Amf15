<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
	
		<form class="form-horizontal" enctype="multipart/form-data" id="createClientForm" action="" method="post">
			
			
			
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#personalinfo" data-toggle="tab">Data Pribadi</a></li>
						<li><a href="#business" data-toggle="tab">Pembiayaan</a></li>
						<li><a href="#family" data-toggle="tab">Keluarga</a></li>
						<li><a href="#popi" data-toggle="tab">POPI</a></li>
						<li><a href="#rmc" data-toggle="tab">IRMC</a></li>
						<li><a href="#asetrt" data-toggle="tab">Asset RT</a></li>
						<li><a href="#pendapatan" data-toggle="tab">Pendapatan</a></li>
						<li><a href="#pengeluaran" data-toggle="tab">Pengeluaran</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>', '</div>'); ?>
							
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Area</label>
								<div class="col-sm-4">								  
								  <select name="client_officer" class="form-control">
										<option value="1">Bogor</option>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Cabang</label>
								<div class="col-sm-4">								  
								  <select name="client_officer" class="form-control">
										<option value="1">Ciseeng</option>
										<option value="2">Jasinga</option>
										<option value="2">Bojong Gede</option>
										<option value="2">Kemang</option>
								  </select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Majelis</label>
								<div class="col-sm-4">								  
								  <select name="client_officer" class="form-control">
										<option value="1">Nama Majelis</option>
								  </select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">No Rekening</label>
								<div class="col-sm-4">
								  <input type="text" name="client_firstname" class="form-control" id="" placeholder="" value="<?php echo set_value('data_rekening', isset($data->data_rekening) ? $data->data_rekening : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Nama Lengkap</label>
								<div class="col-sm-4">
								  <input type="text" name="client_firstname" class="form-control" id="" placeholder="" value="<?php echo set_value('data_namalengkap', isset($data->data_namalengkap) ? $data->data_namalengkap : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Nama Panggilan</label>
								<div class="col-sm-4">
								  <input type="text" name="client_lastname" class="form-control" id="" placeholder="" value="<?php echo set_value('data_namapanggilan', isset($data->data_namapanggilan) ? $data->data_namapanggilan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="martialstatus" class="col-sm-2 control-label">Status</label>
								<div class="col-sm-4">
									<input type="radio" name="client_martialstatus" id="martialstatus" value="Menikah" checked > Menikah  &nbsp;&nbsp;
									<input type="radio" name="client_martialstatus" id="martialstatus" value="Janda" > Janda 
								 </div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Tempat Lahir</label>
								<div class="col-sm-4">
								  <input type="text" name="" class="form-control" id="" placeholder="" value="<?php echo set_value('data_tempatlahir', isset($data->data_tempatlahir) ? $data->data_tempatlahir : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Tanggal Lahir</label>
								<div class="col-sm-4">
								  <input type="text" name="" class="form-control" id="" placeholder="" value="<?php echo set_value('data_tgllahir', isset($data->data_tgllahir) ? $data->data_tgllahir : ''); ?>">
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label for="client_city" class="col-sm-2 control-label">RT / RW</label>
								<div class="col-sm-1">
								  <input type="text" name="data_rt" class="form-control" id="client_city" placeholder="RT" value="<?php echo set_value('data_rt', isset($data->data_rt) ? $data->data_rt : ''); ?>"> 
								</div>
								<div class="col-sm-1">
								  <input type="text" name="data_rt" class="form-control" id="client_city" placeholder="RW" value="<?php echo set_value('data_rw', isset($data->data_rw) ? $data->data_rw : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Kampung</label>
								<div class="col-sm-4">
								  <input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_kampung', isset($data->data_kampung) ? $data->data_kampung : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Desa</label>
								<div class="col-sm-4">
								  <input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_desa', isset($data->data_desa) ? $data->data_desa : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Kecamatan</label>
								<div class="col-sm-4">
								  <input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_kecamatan', isset($data->data_kecamatan) ? $data->data_kecamatan : ''); ?>">
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">No KTP</label>
								<div class="col-sm-4">
								  <input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_ktp', isset($data->data_ktp) ? $data->data_ktp : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Pendidikan Terakhir</label>
								<div class="col-sm-4">
									<select name="client_pendidikan" class="form-control">
										<option value="Tidak Sekolah">Tidak Sekolah</option>
										<option value="Tidak Tamat SD">Tidak Tamat SD</option>
										<option value="SD">SD</option>
										<option value="SMP">SMP</option>
										<option value="SMA/SMK">SMA/SMK</option>
										<option value="Diploma">Diploma</option>
										<option value="S1 Sarjana">S1 Sarjana</option>
										<option value="Kursus">Kursus</option>
										<option value="Pesantren">Pesantren</option>
										<option value="Lainnya">Lainnya</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Agama</label>
								<div class="col-sm-4">
									<select name="client_agama" class="form-control">
										<option value="Islam">Islam</option>
										<option value="Katolik">Katolik</option>
										<option value="Protestan">Protestan</option>
										<option value="Hindu">Hindu</option>
										<option value="Budha">Budha</option>
										<option value="Kong Hu Cu">Kong Hu Cu</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Pekerjaan</label>
								<div class="col-sm-4">								  
									<select name="client_pekerjaan" class="form-control">
										<option value="Petani">Petani</option>
										<option value="Pedagang">Pedagang</option>
										<option value="Peternak">Peternak</option>
										<option value="Pengrajin">Pengrajin</option>
										<option value="Buruh">Buruh</option>
										<option value="Industri Kecil">Industri Kecil</option>
										<option value="Pegawai Swasta">Pegawai Swasta</option>
										<option value="Pegawai Negeri">Pegawai Negeri</option>
										<option value="Ibu Rumah Tangga">Ibu Rumah Tangga</option>
										<option value="Lainnya">Lainnya</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Komoditas</label>
								<div class="col-sm-4">
								  <input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_komoditas', isset($data->data_komoditas) ? $data->data_komoditas : ''); ?>">
								</div>
							</div>
						</div>		
						
						<!-- FAMILY -->
						<div class="tab-pane" id="family">
							
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Suami</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_suami', isset($data->data_suami) ? $data->data_suami : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Tanggal Lahir</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_suami_tgllahir', isset($data->data_suami_tgllahir) ? $data->data_suami_tgllahir : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Pekerjaan</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_suami_pekerjaan', isset($data->data_suami_pekerjaan) ? $data->data_suami_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Komoditas</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_suami_pekerjaan', isset($data->data_suami_pekerjaan) ? $data->data_suami_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Pendidikan</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_suami_pendidikan', isset($data->data_suami_pendidikan) ? $data->data_suami_pendidikan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Jumlah Anak</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_keluarga_anak', isset($data->data_keluarga_anak) ? $data->data_keluarga_anak : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Jumlah Anak Belum Sekolah</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_keluarga_', isset($data->data_pekerjaan) ? $data->data_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">TK</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_keluarga_', isset($data->data_pekerjaan) ? $data->data_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Tidak Sekolah</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_keluarga_', isset($data->data_pekerjaan) ? $data->data_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Tidak Tamat SD</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_keluarga_', isset($data->data_pekerjaan) ? $data->data_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">SD</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_keluarga_', isset($data->data_pekerjaan) ? $data->data_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">SMP</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_keluarga_', isset($data->data_pekerjaan) ? $data->data_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">SMA</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_keluarga_', isset($data->data_pekerjaan) ? $data->data_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Kuliah</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_keluarga_', isset($data->data_pekerjaan) ? $data->data_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Jumlah Tanggungan</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="" value="<?php echo set_value('data_keluarga_', isset($data->data_pekerjaan) ? $data->data_pekerjaan : ''); ?>">
								</div>
							</div>
						</div>
						
						<!-- BUSINESS -->
						<div class="tab-pane" id="business">
						
							<table class="table table-bordered">
								<thead>
									<tr>
										<td width="30px">No</td>
										<td>Nama</td>
										<td>Lama</td>
										<td>Plafond Terakhir</td>
										<td>Total Angsuran</td>
										<td>Status</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>1</td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
									</tr>
									<tr>
										<td>2</td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
									</tr>
									<tr>
										<td>3</td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
									</tr>
									<tr>
										<td>4</td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
										<td><input type="text" name="data_" class="form-control" id="" placeholder=""></td>
									</tr>
								</tbody>
							</table>
						
						
							
						</div>
						
						<!-- INDEX POPI -->
						<div class="tab-pane" id="popi">
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jml. Anggota RT</label>
								<div class="col-sm-4">
									<input type="radio" name="client_popi_anggota_rt" value="A"> A. 6 atau lebih<br/>
									<input type="radio" name="client_popi_anggota_rt" value="B"> B. 5<br/>
									<input type="radio" name="client_popi_anggota_rt" value="C"> C. 4<br/>
									<input type="radio" name="client_popi_anggota_rt" value="D"> D. 3<br/>
									<input type="radio" name="client_popi_anggota_rt" value="E"> E. 2<br/>
									<input type="radio" name="client_popi_anggota_rt" value="F"> F. 1<br/>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Anggota RT 6-18 th yg masih sekolah</label>
								<div class="col-sm-4">
										<input type="radio" name="client_popi_anggota_rt_sekolah"  value="A"> A. Tidak ada anak berusia 6-18 tahun<br/>
										<input type="radio" name="client_popi_anggota_rt_sekolah"  value="B"> B. Tidak<br/>
										<input type="radio" name="client_popi_anggota_rt_sekolah"  value="C"> C. Ya									
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan terakhir kepala RT perempuan/istri</label>
								<div class="col-sm-4">									
										<input type="radio" name="client_popi_pendidikan" value="A"> A. Belum pernah bersekolah<br/>
										<input type="radio" name="client_popi_pendidikan" value="B"> B. SD, Madrasah Ibtidaiyah, Paket A<br/>
										<input type="radio" name="client_popi_pendidikan" value="C"> C. SMP, Madrasah Tsanawiyah, Paket B<br/>
										<input type="radio" name="client_popi_pendidikan" value="D"> D. Tidak ada kepala rumah tangga perempuan<br/>
										<input type="radio" name="client_popi_pendidikan" value="E"> E. SMK<br/>
										<input type="radio" name="client_popi_pendidikan" value="F"> F. SMA, Mad. Aliyah<br/>
										<input type="radio" name="client_popi_pendidikan" value="G"> G. D1, D2, D3, S1 <br/>
									
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pekerjaan Kepala RT laki-laki/sumai di minggu terakhir</label>
								<div class="col-sm-4">
										<input type="radio" name="client_popi_pekerjaan_suami" value="A"> A. Tidak ada kepala rumah tangga laki-laki<br/>
										<input type="radio" name="client_popi_pekerjaan_suami" value="B"> B. Tidak bekerja / pekerja tidak dibayar<br/>
										<input type="radio" name="client_popi_pekerjaan_suami" value="C"> C. Pekerja bebas<br/>
										<input type="radio" name="client_popi_pekerjaan_suami" value="D"> D. Berusaha sendiri  dibantu buruh tidak tetap<br/>
										<input type="radio" name="client_popi_pekerjaan_suami" value="E"> E. Buruh/karyawan/pegawai<br/>
										<input type="radio" name="client_popi_pekerjaan_suami" value="F"> F. Berusaha dibantu buruh tetap/buruh dibayar<br/>									
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis lantai terluas</label>
								<div class="col-sm-4">
										<input type="radio" name="client_popi_lantai" value="A"> A. Tanah atau bambu<br/>
										<input type="radio" name="client_popi_lantai" value="B"> B. Bukan tanah atau bambu
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Kloset / WC RT</label>
								<div class="col-sm-4">
									<input type="radio" name="client_popi_closet" value="A"> A. Tidak ada atau jamban cemplung/cubluk<br/>
									<input type="radio" name="client_popi_closet" value="B"> B. Ada kloset, tapi tidak tersambung ke septic tank<br/>
									<input type="radio" name="client_popi_closet" value="C"> C. Leher Angsa</option>
									
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Bahan Bakar utama RT</label>
								<div class="col-sm-4">
										<input type="radio" name="client_popi_bahanbakar" value="A"> A. Bambu Kayu bakar, arang, briket<br/>
										<input type="radio" name="client_popi_bahanbakar" value="B"> B. Gas/elpiji, minyak tanah, listrik atau lainya
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT memiliki tabung gas 12 kg/lebih</label>
								<div class="col-sm-4">									
										<input type="radio" name="client_popi_gas" value="A"> A. Tidak<br/>
										<input type="radio" name="client_popi_gas" value="B"> B. Ya
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT memiliki Kulkas</label>
								<div class="col-sm-4">							
										<input type="radio" name="client_popi_kulkas" value="A"> A. Tidak<br/>
										<input type="radio" name="client_popi_kulkas" value="B"> B. Ya
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT memiliki sepeda motor/perahu motor</label>
								<div class="col-sm-4">
										<input type="radio" name="client_popi_motor" value="A"> A. Tidak<br/>
										<input type="radio" name="client_popi_motor" value="B"> B. Ya
								</div>
							</div>
						</div>
					
						<!-- INDEX RMC -->
						<div class="tab-pane" id="rmc">							
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Ukuran Rumah</label>
								<div class="col-sm-8">
										<input type="radio" name="client_rmc_ukuran" value="A"> A. Besar &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_ukuran" value="B"> B. Sedang&nbsp;&nbsp; 
										<input type="radio" name="client_rmc_ukuran" value="C"> C. Kecil
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kondisi Rumah</label>
								<div class="col-sm-8">										
										<input type="radio" name="client_rmc_kondisi" value="A"> A. Bagus &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_kondisi" value="B"> B. Sedang &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_kondisi" value="C"> C. Rusak
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Atap</label>
								<div class="col-sm-8">									
										<input type="radio" name="client_rmc_atap" value="A"> A. Genteng Mewah &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_atap" value="B"> B. Genteng biasa/asbes/seng &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_atap" value="C"> C. Rumbia
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Dinding</label>
								<div class="col-sm-8">
										<input type="radio" name="client_rmc_dinding" value="A"> A. Tembok &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_dinding" value="B"> B. Setengah tembok/belum plester &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_dinding" value="C"> C. Kayu/bambu/bilik
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Lantai</label>
								<div class="col-sm-8">
										<input type="radio" name="client_rmc_lantai" value="A"> A. Keramik &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_lantai" value="B"> B. Keramik  25%/tegel/semen &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_lantai" value="C"> C. Tanah/Panggung
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Listrik</label>
								<div class="col-sm-8">
										<input type="radio" name="client_rmc_listrik" value="A"> A. PLN &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_listrik" value="B"> B. Sambungan  &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_listrik" value="C"> C. Tidak Ada
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Sumber Air</label>
								<div class="col-sm-8">
										<input type="radio" name="client_rmc_listrik" value="A"> A. PAM &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_listrik" value="B"> B. Sanyo/pompa mesin  &nbsp;&nbsp; 
										<input type="radio" name="client_rmc_listrik" value="C"> C. Sumur Timba
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kepemilikan Rumah</label>
								<div class="col-sm-4">
									<select name="client_rmc_kepemilikan" class="form-control">
										<option value="Milik Sendiri">Milik Sendiri</option>
										<option value="Menumpang">Menumpang</option>
										<option value="Kontrak">Kontrak</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Harga/bulan</label>
								<div class="col-sm-4">
									<input type="text" name="data_rmc_hargabulan" class="form-control" id="" placeholder="">
								</div>
							</div>
					</div>
						
						<!-- INDEX Aset RT -->
						<div class="tab-pane" id="asetrt">
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Lahan</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jumlah Lahan</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Ternak</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jumlah Ternak</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tabungan</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Deposito</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Lainnya</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Total</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
						</div>
				
						<!-- Pendapatan -->
						<div class="tab-pane" id="pendapatan">
							
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Suami</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Jenis Usaha</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Lama</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Istri</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Jenis Usaha</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Lama</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Lainnya</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Jenis Usaha</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Lama</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Total</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
						</div>
				
						<!-- Pengeluaran -->
						<div class="tab-pane" id="pengeluaran">
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Konsumsi Beras</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_beras" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Belanja Dapur</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_dapur" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Rekening (listrik, air, tlp)</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_rekening" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pulsa Handphone</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_handphone" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kreditan</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_kredit" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Arisan</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_arisan" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan Anak</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_pendidikan" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Konsumsi Umum</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_umum" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"> Angs. Pinjaman Lain</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_angsuran" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Total</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Saving Power</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tujuan</label>
								<div class="col-sm-4">
									<input type="text" name="data_" class="form-control" id="" placeholder="">
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-2 ">
						  <button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
		</form>
	</div>
</div>	