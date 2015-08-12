<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
		
		<form class="form-horizontal" id="createClientForm" action="" method="post">
		
		<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#personalinfo" data-toggle="tab">Data Pribadi</a></li>
						<li><a href="#pengajuan" data-toggle="tab">Pengajuan</a></li>
						<li><a href="#business" data-toggle="tab">Pembiayaan</a></li>
						<li><a href="#family" data-toggle="tab">Keluarga</a></li>
						<li><a href="#popi" data-toggle="tab">PPI</a></li>
						<li><a href="#rmc" data-toggle="tab">CHI</a></li>
						<li><a href="#asetrt" data-toggle="tab">Asset RT</a></li>
						<li><a href="#pendapatan" data-toggle="tab">Pendapatan</a></li>
						<li><a href="#pengeluaran" data-toggle="tab">Pengeluaran</a></li>
						<li><a href="#pembiayaan" data-toggle="tab">Sumber</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
					
						<!-- PERSONAL INFO -->
						<div class="tab-pane active" id="personalinfo">							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Area</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->area_name; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Cabang</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->branch_name; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Majelis</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->group_name; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nomor Rekening</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_account; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendamping Lapangan</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->officer_name; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Lengkap</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_fullname; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Panggilan</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_simplename; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal Lahir</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_birthdate; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tempat Lahir</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_birthplace; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="martialstatus" class="col-sm-3 control-label">Status</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_martialstatus; ?>" readonly /></div>
							</div>
							<hr/>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT / RW</label>
								<div class="col-sm-1"><input type="text" class="form-control" value="<?php echo $client->client_rt; ?>" readonly /></div>
								<div class="col-sm-1"><input type="text" class="form-control" value="<?php echo $client->client_rw; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kampung</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_kampung; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Desa</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_desa; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kecamatan</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_kecamatan; ?>" readonly /></div>
							</div>
							<hr/>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">No KTP</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_ktp; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Agama</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_religion; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan Terakhir</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_education; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pekerjaan</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_job; ?>" readonly /></div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Komoditas</label>
								<div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $client->client_comodity; ?>" readonly /></div>
							</div>
						</div>
						
						<!-- PENGAJUAN -->
						<div class="tab-pane" id="pengajuan">
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pembiayaan Ke </label>
								<div class="col-sm-4">
									<input type="text" name="data_ke" value="<?php echo set_value('data_ke', isset($data->data_ke) ? $data->data_ke : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pengajuan </label>
								<div class="col-sm-4">
									<input type="text" name="data_pengajuan" value="<?php echo set_value('data_pengajuan', isset($data->data_pengajuan) ? $data->data_pengajuan : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal Pengajuan</label>
								<div class="col-sm-4">
									<?php if(!$data->group_date){ ?>
										<input type="text" name="data_tgl" value="<?php echo $data->data_tgl; ?>" 	class="form-control datepicker-input" data-date-format="yyyy-mm-dd" />										
									<?php }else{ ?>
										<input type="text" name="data_tgl" value="<?php echo set_value('data_tgl', isset($data->data_tgl) ? $data->data_tgl : ''); ?>" 	class="form-control datepicker-input" data-date-format="yyyy-mm-dd" />
									<?php }?>
								</div>
							</div>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal Persetujuan</label>
								<div class="col-sm-4">
										<input type="text" name="data_date_accept" value="<?php echo set_value('data_date_accept', isset($data->data_date_accept) ? $data->data_date_accept : ''); ?>" 	class="form-control datepicker-input" data-date-format="yyyy-mm-dd" />									
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal Penagihan Pertama</label>
								<div class="col-sm-4">
										<input type="text" name="data_date_first" value="<?php echo set_value('data_date_first', isset($data->data_date_first) ? $data->data_date_first : ''); ?>" 	class="form-control datepicker-input" data-date-format="yyyy-mm-dd" />									
								</div>
							</div>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Sektor Pembiayaan</label>
								<div class="col-sm-4">
									<select name="data_sector" class="form-control" >
												<option value="0" >Sektor</option>
												<?php foreach($sector as $s):  ?>
													<option value="<?php echo $s->sector_id; ?>" <?php if($s->sector_id == $data->data_sector){ echo "selected";} ?>><?php echo $s->sector_name; ?></option>
												<?php endforeach;  ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tujuan Pembiayaan</label>
								<div class="col-sm-4">
									<input type="text" name="data_tujuan" value="<?php echo set_value('data_tujuan', isset($data->data_tujuan) ? $data->data_tujuan : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">PLAFOND (Rp)</label>
								<div class="col-sm-4">
									<input type="text" name="data_plafond" value="<?php echo set_value('data_plafond', isset($data->data_plafond) ? $data->data_plafond : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">JANGKA WAKTU (minggu)</label>
								<div class="col-sm-4">
									<input type="text" name="data_jangkawaktu" value="50" class="form-control" readonly />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">AKAD</label>
								<div class="col-sm-4">
									<select name="data_akad" class="form-control">
										<option value="MBA" 	<?php if($data->data_akad == "MBA"){echo "selected"; } ?>	>Murabahah</option>
										<option value="IJR" 	<?php if($data->data_akad == "IJR"){echo "selected"; } ?>	>Ijarah</option>
										<option value="AHA" 	<?php if($data->data_akad == "AHA"){echo "selected"; } ?>	>Al Hiwalah</option>
										<option value="MYR" 	<?php if($data->data_akad == "MYR"){echo "selected"; } ?>	>Musyarakah</option>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">TOTAL ANGSURAN (Rp)</label>
								<div class="col-sm-4">
									<input type="text" name="data_totalangsuran" value="<?php echo set_value('data_totalangsuran', isset($data->data_totalangsuran) ? $data->data_totalangsuran : ''); ?>" class="form-control"  />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pokok (Rp)</label>
								<div class="col-sm-4">
									<input type="text" name="data_angsuranpokok" value="<?php echo set_value('data_angsuranpokok', isset($data->data_angsuranpokok) ? $data->data_angsuranpokok : ''); ?>" class="form-control"  />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tabungan Wajib (Rp)</label>
								<div class="col-sm-4">
									<input type="text" name="data_tabunganwajib" value="<?php echo set_value('data_tabunganwajib', isset($data->data_tabunganwajib) ? $data->data_tabunganwajib : ''); ?>" class="form-control"  />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Margin/fee (Rp)</label>
								<div class="col-sm-4">
									<input type="text" name="data_margin" value="<?php echo set_value('data_margin', isset($data->data_margin) ? $data->data_margin : ''); ?>" class="form-control"  />
								</div>
							</div>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Angsuran Ke</label>
								<div class="col-sm-4"><?php if($data->data_angsuranke == ""){$data_angsuranke=0; }else{ $data_angsuranke = $data->data_angsuranke; }?>
									<input type="text" name="data_angsuranke" readonly value="<?php echo set_value('data_angsuranke', isset($data_angsuranke) ? $data_angsuranke : ''); ?>" class="form-control" />
								</div>
							</div>
							
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">STATUS</label>
								<div class="col-sm-4">
									<select name="data_status" class="form-control">
										<option value="2" 	<?php if($data->data_status == "2"){echo "selected"; } ?>	>Belum Aktif / Pengajuan</option>
										<option value="1" 	<?php if($data->data_status == "1"){echo "selected"; } ?>	>Berjalan</option>
										<?php if($data->data_angsuranke >= 50 OR $data->data_status == "3"){ ?><option value="3" 	<?php if($data->data_status == "3"){echo "selected"; } ?>	>Selesai</option><?php } ?>
										
									</select>
								</div>
							</div>
						</div>

						<!-- BUSINESS -->
						<div class="tab-pane" id="business">
						
							<table class="table table-bordered">
								<thead>
									<tr>
										<td width="30px" class="text-center">No</td>
										<td>Nama</td>
										<td>Lama</td>
										<td>Plafond Terakhir</td>
										<td>Total Angsuran</td>
										<td width="150px">Status</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-center">1</td>
										<td><input type="text" name="data_pembiayaan1_nama" 	value="<?php echo $data->data_pembiayaan1_nama; ?>" 	class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan1_lama" 	value="<?php echo $data->data_pembiayaan1_lama; ?>" 	class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan1_plafond" 	value="<?php echo $data->data_pembiayaan1_plafond; ?>"	class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan1_total" 	value="<?php echo $data->data_pembiayaan1_total; ?>" 	class="form-control" ></td>
										<td>
											<select name="data_pembiayaan1_status" class="form-control">
												<option value="Berjalan" <?php if($data->data_pembiayaan1_status == "Berjalan") { echo "selected";}?> >Berjalan</option>
												<option value="Selesai" <?php if($data->data_pembiayaan1_status == "Selesai") 	{ echo "selected";}?> >Selesai</option>												
										  </select>
										</td>
									</tr>
									<tr>
										<td class="text-center">2</td>
										<td><input type="text" name="data_pembiayaan2_nama" 	value="<?php echo $data->data_pembiayaan2_nama; ?>" 	class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan2_lama" 	value="<?php echo $data->data_pembiayaan2_lama; ?>" 	class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan2_plafond" 	value="<?php echo $data->data_pembiayaan2_plafond; ?>" 	class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan2_total" 	value="<?php echo $data->data_pembiayaan2_total; ?>" 	class="form-control" ></td>
										<td>
											<select name="data_pembiayaan2_status" class="form-control">
												<option value="Berjalan" <?php if($data->data_pembiayaan2_status == "Berjalan") { echo "selected";}?> >Berjalan</option>
												<option value="Selesai" <?php if($data->data_pembiayaan2_status == "Selesai") 	{ echo "selected";}?> >Selesai</option>												
											</select>
										</td>
									</tr>
									<tr>
										<td class="text-center">3</td>
										<td><input type="text" name="data_pembiayaan3_nama" 	value="<?php echo $data->data_pembiayaan3_nama; ?>" class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan3_lama" 	value="<?php echo $data->data_pembiayaan3_lama; ?>" class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan3_plafond" 	value="<?php echo $data->data_pembiayaan3_plafond; ?>" class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan3_total" 	value="<?php echo $data->data_pembiayaan3_total; ?>" 	class="form-control" ></td>
										<td>
											<select name="data_pembiayaan3_status" class="form-control">
												<option value="Berjalan" <?php if($data->data_pembiayaan3_status == "Berjalan") { echo "selected";}?> >Berjalan</option>
												<option value="Selesai" <?php if($data->data_pembiayaan3_status == "Selesai") 	{ echo "selected";}?> >Selesai</option>												
											</select>
										</td>
									</tr>
									<tr>
										<td class="text-center">4</td>
										<td><input type="text" name="data_pembiayaan4_nama" 	value="<?php echo $data->data_pembiayaan4_nama; ?>" 	class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan4_lama"	 	value="<?php echo $data->data_pembiayaan4_lama; ?>" 	class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan4_plafond" 	value="<?php echo $data->data_pembiayaan4_plafond; ?>" 	class="form-control" ></td>
										<td><input type="text" name="data_pembiayaan4_total" 	value="<?php echo $data->data_pembiayaan4_total; ?>" 	class="form-control" ></td>
										<td>
											<select name="data_pembiayaan4_status" class="form-control">
												<option value="Berjalan" <?php if($data->data_pembiayaan4_status == "Berjalan") { echo "selected";}?> >Berjalan</option>
												<option value="Selesai" <?php if($data->data_pembiayaan4_status == "Selesai") 	{ echo "selected";}?> >Selesai</option>												
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						
						<!-- FAMILY -->
						<div class="tab-pane" id="family">
							
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Nama Suami</label>
								<div class="col-sm-4">
									<input type="text" name="data_suami" class="form-control" value="<?php echo set_value('data_suami', isset($data->data_suami) ? $data->data_suami : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Tanggal Lahir Suami</label>
								<div class="col-sm-4">
									<input type="text" name="data_suami_tgllahir" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" value="<?php echo set_value('data_suami_tgllahir', isset($data->data_suami_tgllahir) ? $data->data_suami_tgllahir : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Pekerjaan Suami</label>
								<div class="col-sm-4">
									<input type="text" name="data_suami_pekerjaan" class="form-control" value="<?php echo set_value('data_suami_pekerjaan', isset($data->data_suami_pekerjaan) ? $data->data_suami_pekerjaan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Komoditas Suami</label>
								<div class="col-sm-4">
									<input type="text" name="data_suami_komoditas" class="form-control" value="<?php echo set_value('data_suami_komoditas', isset($data->data_suami_komoditas) ? $data->data_suami_komoditas : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Pendidikan Suami</label>
								<div class="col-sm-4">
									<input type="text" name="data_suami_pendidikan" class="form-control" value="<?php echo set_value('data_suami_pendidikan', isset($data->data_suami_pendidikan) ? $data->data_suami_pendidikan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Jumlah Tanggungan</label>
								<div class="col-sm-4">
									<input type="text" name="data_keluarga_tanggungan" class="form-control" value="<?php echo set_value('data_keluarga_tanggungan', isset($data->data_keluarga_tanggungan) ? $data->data_keluarga_tanggungan : ''); ?>">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-2 control-label">Jumlah Anak</label>
								<div class="col-sm-4">
									<input type="text" name="data_keluarga_anak" class="form-control" value="<?php echo set_value('data_keluarga_anak', isset($data->data_keluarga_anak) ? $data->data_keluarga_anak : ''); ?>">
								</div>
							</div>
							<br/>
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="text-center">Belum<br/>Sekolah</td>
										<td class="text-center">Tidak<br/>Sekolah</td>
										<td class="text-center">TK</td>
										<td class="text-center">SD</td>
										<td class="text-center">Tidak<br/>Tamat SD</td>
										<td class="text-center">SMP</td>
										<td class="text-center">SMA</td>
										<td class="text-center">Kuliah</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><input type="text" name="data_keluarga_belumsekolah" 	value="<?php echo set_value('data_keluarga_belumsekolah', isset($data->data_keluarga_belumsekolah) ? $data->data_keluarga_belumsekolah : ''); ?>" 	class="form-control" /></td>
										<td><input type="text" name="data_keluarga_tidaksekolah" 	value="<?php echo set_value('data_keluarga_tidaksekolah', isset($data->data_keluarga_tidaksekolah) ? $data->data_keluarga_tidaksekolah : ''); ?>" 	class="form-control" /></td>
										<td><input type="text" name="data_keluarga_tk" 				value="<?php echo set_value('data_keluarga_tk', isset($data->data_keluarga_tk) ? $data->data_keluarga_tk : ''); ?>" 								class="form-control" /></td>
										<td><input type="text" name="data_keluarga_sd" 				value="<?php echo set_value('data_keluarga_sd', isset($data->data_keluarga_sd) ? $data->data_keluarga_sd : ''); ?>" 								class="form-control" /></td>
										<td><input type="text" name="data_keluarga_tidaktamatsd" 	value="<?php echo set_value('data_keluarga_tidaktamatsd', isset($data->data_keluarga_tidaktamatsd) ? $data->data_keluarga_tidaktamatsd : ''); ?>" 	class="form-control" /></td>
										<td><input type="text" name="data_keluarga_smp" 			value="<?php echo set_value('data_keluarga_smp', isset($data->data_keluarga_smp) ? $data->data_keluarga_smp : ''); ?>" 								class="form-control" /></td>
										<td><input type="text" name="data_keluarga_sma" 			value="<?php echo set_value('data_keluarga_sma', isset($data->data_keluarga_sma) ? $data->data_keluarga_sma : ''); ?>" 								class="form-control" /></td>
										<td><input type="text" name="data_keluarga_kuliah" 			value="<?php echo set_value('data_keluarga_kuliah', isset($data->data_keluarga_kuliah) ? $data->data_keluarga_kuliah : ''); ?>" 					class="form-control" /></td>										
									</tr>
								</tbody>
							</table>
							
						</div>
						
						<!-- INDEX POPI -->
						<div class="tab-pane" id="popi">
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jml. Anggota RT</label>
								<div class="col-sm-5">
									<input type="radio" name="data_popi_anggotart" value="A" <?php if($data->data_popi_anggotart == "A"){echo "checked"; } ?> /> A. 6 atau lebih (0)<br/>
									<input type="radio" name="data_popi_anggotart" value="B" <?php if($data->data_popi_anggotart == "B"){echo "checked"; } ?> /> B. 5 (5)<br/>
									<input type="radio" name="data_popi_anggotart" value="C" <?php if($data->data_popi_anggotart == "C"){echo "checked"; } ?> /> C. 4 (11)<br/>
									<input type="radio" name="data_popi_anggotart" value="D" <?php if($data->data_popi_anggotart == "D"){echo "checked"; } ?> /> D. 3 (18)<br/>
									<input type="radio" name="data_popi_anggotart" value="E" <?php if($data->data_popi_anggotart == "E"){echo "checked"; } ?> /> E. 2 (24)<br/>
									<input type="radio" name="data_popi_anggotart" value="F" <?php if($data->data_popi_anggotart == "F"){echo "checked"; } ?> /> F. 1 (37)<br/>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Anggota RT 6-18 th yg masih sekolah</label>
								<div class="col-sm-5">
										<input type="radio" name="data_popi_masihsekolah"  value="A" <?php if($data->data_popi_masihsekolah == "A"){echo "checked"; } ?> /> A. Tidak ada anak berusia 6-18 tahun (0)<br/>
										<input type="radio" name="data_popi_masihsekolah"  value="B" <?php if($data->data_popi_masihsekolah == "B"){echo "checked"; } ?> /> B. Tidak (0)<br/>
										<input type="radio" name="data_popi_masihsekolah"  value="C" <?php if($data->data_popi_masihsekolah == "C"){echo "checked"; } ?> /> C. Ya (2)								
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan terakhir kepala RT perempuan/istri</label>
								<div class="col-sm-5">									
										<input type="radio" name="data_popi_pendidikanistri" value="A" <?php if($data->data_popi_pendidikanistri == "A"){echo "checked"; } ?> /> A. Belum pernah bersekolah (0)<br/>
										<input type="radio" name="data_popi_pendidikanistri" value="B" <?php if($data->data_popi_pendidikanistri == "B"){echo "checked"; } ?> /> B. SD, Madrasah Ibtidaiyah, Paket A (3)<br/>
										<input type="radio" name="data_popi_pendidikanistri" value="C" <?php if($data->data_popi_pendidikanistri == "C"){echo "checked"; } ?> /> C. SMP, Madrasah Tsanawiyah, Paket B (4)<br/>
										<input type="radio" name="data_popi_pendidikanistri" value="D" <?php if($data->data_popi_pendidikanistri == "D"){echo "checked"; } ?> /> D. Tidak ada kepala rumah tangga perempuan (4)<br/>
										<input type="radio" name="data_popi_pendidikanistri" value="E" <?php if($data->data_popi_pendidikanistri == "E"){echo "checked"; } ?> /> E. SMK (4)<br/>
										<input type="radio" name="data_popi_pendidikanistri" value="F" <?php if($data->data_popi_pendidikanistri == "F"){echo "checked"; } ?> /> F. SMA, Mad. Aliyah (6)<br/>
										<input type="radio" name="data_popi_pendidikanistri" value="G" <?php if($data->data_popi_pendidikanistri == "G"){echo "checked"; } ?> /> G. D1, D2, D3, S1 (18)<br/>
									
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pekerjaan Kepala RT laki-laki/sumai di minggu terakhir</label>
								<div class="col-sm-5">
										<input type="radio" name="data_popi_pekerjaansuami" value="A" <?php if($data->data_popi_pekerjaansuami == "A"){echo "checked"; } ?> /> A. Tidak ada kepala rumah tangga laki-laki (0)<br/>
										<input type="radio" name="data_popi_pekerjaansuami" value="B" <?php if($data->data_popi_pekerjaansuami == "B"){echo "checked"; } ?> /> B. Tidak bekerja / pekerja tidak dibayar (0)<br/>
										<input type="radio" name="data_popi_pekerjaansuami" value="C" <?php if($data->data_popi_pekerjaansuami == "C"){echo "checked"; } ?> /> C. Pekerja bebas (1)<br/>
										<input type="radio" name="data_popi_pekerjaansuami" value="D" <?php if($data->data_popi_pekerjaansuami == "D"){echo "checked"; } ?> /> D. Berusaha sendiri dibantu buruh tidak tetap (3)<br/>
										<input type="radio" name="data_popi_pekerjaansuami" value="E" <?php if($data->data_popi_pekerjaansuami == "E"){echo "checked"; } ?> /> E. Buruh/karyawan/pegawai (3)<br/>
										<input type="radio" name="data_popi_pekerjaansuami" value="F" <?php if($data->data_popi_pekerjaansuami == "F"){echo "checked"; } ?> /> F. Berusaha dibantu buruh tetap/buruh dibayar (6)<br/>									
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis lantai terluas</label>
								<div class="col-sm-5">
										<input type="radio" name="data_popi_jenislantai" value="A" <?php if($data->data_popi_jenislantai == "A"){echo "checked"; } ?> /> A. Tanah atau bambu (0)<br/>
										<input type="radio" name="data_popi_jenislantai" value="B" <?php if($data->data_popi_jenislantai == "B"){echo "checked"; } ?> /> B. Bukan tanah atau bambu (5)
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Kloset / WC RT</label>
								<div class="col-sm-5">
									<input type="radio" name="data_popi_jeniswc" value="A" <?php if($data->data_popi_jeniswc == "A"){echo "checked"; } ?> /> A. Tidak ada atau jamban cemplung/cubluk (0)<br/>
									<input type="radio" name="data_popi_jeniswc" value="B" <?php if($data->data_popi_jeniswc == "B"){echo "checked"; } ?> /> B. Ada kloset, tapi tidak tersambung ke septic tank (1)<br/>
									<input type="radio" name="data_popi_jeniswc" value="C" <?php if($data->data_popi_jeniswc == "C"){echo "checked"; } ?> /> C. Leher Angsa (4)</option>
									
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Bahan Bakar utama RT</label>
								<div class="col-sm-5">
									<input type="radio" name="data_popi_bahanbakar" value="A" <?php if($data->data_popi_bahanbakar == "A"){echo "checked"; } ?> /> A. Bambu Kayu bakar, arang, briket (0)<br/>
									<input type="radio" name="data_popi_bahanbakar" value="B" <?php if($data->data_popi_bahanbakar == "B"){echo "checked"; } ?> /> B. Gas/elpiji, minyak tanah, listrik atau lainya (5)
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT memiliki tabung gas 12 kg/lebih</label>
								<div class="col-sm-5">									
									<input type="radio" name="data_popi_gas" value="A" <?php if($data->data_popi_gas == "A"){echo "checked"; } ?> /> A. Tidak (0)<br/>
									<input type="radio" name="data_popi_gas" value="B" <?php if($data->data_popi_gas == "B"){echo "checked"; } ?> /> B. Ya (6)
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT memiliki Kulkas</label>
								<div class="col-sm-5">							
									<input type="radio" name="data_popi_kulkas" value="A" <?php if($data->data_popi_kulkas == "A"){echo "checked"; } ?> /> A. Tidak (0)<br/>
									<input type="radio" name="data_popi_kulkas" value="B" <?php if($data->data_popi_kulkas == "B"){echo "checked"; } ?> /> B. Ya (8)
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT memiliki sepeda motor/perahu motor</label>
								<div class="col-sm-5">
									<input type="radio" name="data_popi_motor" value="A" <?php if($data->data_popi_motor == "A"){echo "checked"; } ?> /> A. Tidak (0)<br/>
									<input type="radio" name="data_popi_motor" value="B" <?php if($data->data_popi_motor == "B"){echo "checked"; } ?> /> B. Ya (9)
								</div>
							</div>
						</div>
						
						<!-- INDEX RMC -->
						<div class="tab-pane" id="rmc">							
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Ukuran Rumah</label>
								<div class="col-sm-8">
										<input type="radio" name="data_rmc_ukuranrumah" value="A" <?php if($data->data_rmc_ukuranrumah == "A"){echo "checked"; } ?> /> A. Besar (3) &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_ukuranrumah" value="B" <?php if($data->data_rmc_ukuranrumah == "B"){echo "checked"; } ?> /> B. Sedang (1) &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_ukuranrumah" value="C" <?php if($data->data_rmc_ukuranrumah == "C"){echo "checked"; } ?> /> C. Kecil (0) 
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kondisi Rumah</label>
								<div class="col-sm-8">										
										<input type="radio" name="data_rmc_kondisirumah" value="A" <?php if($data->data_rmc_kondisirumah == "A"){echo "checked"; } ?> /> A. Bagus  (3) &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_kondisirumah" value="B" <?php if($data->data_rmc_kondisirumah == "B"){echo "checked"; } ?> /> B. Sedang (1)  &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_kondisirumah" value="C" <?php if($data->data_rmc_kondisirumah == "C"){echo "checked"; } ?> /> C. Rusak (0) 
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Atap</label>
								<div class="col-sm-8">									
										<input type="radio" name="data_rmc_jenisatap" value="A" <?php if($data->data_rmc_jenisatap == "A"){echo "checked"; } ?> /> A. Genteng Mewah (2)  &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_jenisatap" value="B" <?php if($data->data_rmc_jenisatap == "B"){echo "checked"; } ?> /> B. Genteng biasa/asbes/seng (1)  &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_jenisatap" value="C" <?php if($data->data_rmc_jenisatap == "C"){echo "checked"; } ?> /> C. Rumbia (0) 
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Dinding</label>
								<div class="col-sm-8">
										<input type="radio" name="data_rmc_jenisdinding" value="A" <?php if($data->data_rmc_jenisdinding == "A"){echo "checked"; } ?> /> A. Tembok (2)  &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_jenisdinding" value="B" <?php if($data->data_rmc_jenisdinding == "B"){echo "checked"; } ?> /> B. Setengah tembok/belum plester  (1) &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_jenisdinding" value="C" <?php if($data->data_rmc_jenisdinding == "C"){echo "checked"; } ?> /> C. Kayu/bambu/bilik (0) 
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Lantai</label>
								<div class="col-sm-8">
										<input type="radio" name="data_rmc_jenislantai" value="A" <?php if($data->data_rmc_jenislantai == "A"){echo "checked"; } ?> /> A. Keramik (2)  &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_jenislantai" value="B" <?php if($data->data_rmc_jenislantai == "B"){echo "checked"; } ?> /> B. Keramik  25%/tegel/semen  (1) &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_jenislantai" value="C" <?php if($data->data_rmc_jenislantai == "C"){echo "checked"; } ?> /> C. Tanah/Panggung (0) 
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Listrik</label>
								<div class="col-sm-8">
										<input type="radio" name="data_rmc_listrik" value="A" <?php if($data->data_rmc_listrik == "A"){echo "checked"; } ?> /> A. PLN  (2) &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_listrik" value="B" <?php if($data->data_rmc_listrik == "B"){echo "checked"; } ?> /> B. Sambungan  (1)  &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_listrik" value="C" <?php if($data->data_rmc_listrik == "C"){echo "checked"; } ?> /> C. Tidak Ada (0) 
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Sumber Air</label>
								<div class="col-sm-8">
										<input type="radio" name="data_rmc_sumberair" value="A" <?php if($data->data_rmc_sumberair == "A"){echo "checked"; } ?> /> A. PAM  (2) &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_sumberair" value="B" <?php if($data->data_rmc_sumberair == "B"){echo "checked"; } ?> /> B. Sanyo/pompa mesin (1)   &nbsp;&nbsp; 
										<input type="radio" name="data_rmc_sumberair" value="C" <?php if($data->data_rmc_sumberair == "C"){echo "checked"; } ?> /> C. Sumur Timba (0) 
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kepemilikan Rumah</label>
								<div class="col-sm-4">
									<select name="data_rmc_kepemilikan" class="form-control">
										<option value="Milik Sendiri" <?php if($data->data_rmc_kepemilikan == "Milik Sendiri"){echo "selected"; } ?>>Milik Sendiri</option>
										<option value="Menumpang" <?php if($data->data_rmc_kepemilikan == "Menumpang"){echo "selected"; } ?>>Menumpang</option>
										<option value="Kontrak" <?php if($data->data_rmc_kepemilikan == "Kontrak"){echo "selected"; } ?>>Kontrak</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Harga/bulan</label>
								<div class="col-sm-4">
									<input type="text" name="data_rmc_hargaperbulan" value="<?php echo $data->data_rmc_hargaperbulan; ?>" class="form-control" >
								</div>
							</div>
					</div>
						
						<!-- INDEX Aset RT -->
						<div class="tab-pane" id="asetrt">
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Lahan</label>
								<div class="col-sm-4">
									<input type="text" name="data_aset_lahan" value="<?php echo set_value('data_aset_lahan', isset($data->data_aset_lahan) ? $data->data_aset_lahan : ''); ?>" class="form-control" >
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jumlah Lahan</label>
								<div class="col-sm-4">
									<input type="text" name="data_aset_jumlahlahan" value="<?php echo set_value('data_aset_jumlahlahan', isset($data->data_aset_jumlahlahan) ? $data->data_aset_jumlahlahan : ''); ?>"  class="form-control" >
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Ternak</label>
								<div class="col-sm-4">
									<input type="text" name="data_aset_ternak" value="<?php echo set_value('data_aset_ternak', isset($data->data_aset_ternak) ? $data->data_aset_ternak : ''); ?>"  class="form-control" >
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jumlah Ternak</label>
								<div class="col-sm-4">
									<input type="text" name="data_aset_jumlahternak" value="<?php echo set_value('data_aset_jumlahternak', isset($data->data_aset_jumlahternak) ? $data->data_aset_jumlahternak : ''); ?>"  class="form-control" >
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tabungan</label>
								<div class="col-sm-4">
									<input type="text" name="data_aset_tabungan" value="<?php echo set_value('data_aset_tabungan', isset($data->data_aset_tabungan) ? $data->data_aset_tabungan : ''); ?>"  class="form-control" >
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Deposito</label>
								<div class="col-sm-4">
									<input type="text" name="data_aset_deposito" value="<?php echo set_value('data_aset_deposito', isset($data->data_aset_deposito) ? $data->data_aset_deposito : ''); ?>"  class="form-control" >
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Lainnya</label>
								<div class="col-sm-4">
									<input type="text" name="data_aset_lain" value="<?php echo set_value('data_aset_lain', isset($data->data_aset_lain) ? $data->data_aset_lain : ''); ?>"  class="form-control" >
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Total</label>
								<div class="col-sm-4">
									<input type="text" name="data_aset_total"  value="<?php echo set_value('data_aset_total', isset($data->data_aset_total) ? $data->data_aset_total : ''); ?>" class="form-control" >
								</div>
							</div>
						</div>
				
						<!-- Pendapatan -->
						<div class="tab-pane" id="pendapatan">
							
							<table class="table table-bordered">
								<thead>
									<tr>
										<td class="text-center"> </td>
										<td class="text-center">Jenis Usaha</td>
										<td class="text-center">Lama Bekerja</td>
										<td class="text-center">Jumlah</td>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td>Suami</td>
										<td><input type="text" name="data_pendapatan_suamijenisusaha" 	value="<?php echo set_value('data_pendapatan_suamijenisusaha', isset($data->data_pendapatan_suamijenisusaha) ? $data->data_pendapatan_suamijenisusaha : ''); ?>" class="form-control" /></td>
										<td><input type="text" name="data_pendapatan_suamilama" 					value="<?php echo set_value('data_pendapatan_suamilama', isset($data->data_pendapatan_suamilama) ? $data->data_pendapatan_suamilama : ''); ?>" 	class="form-control" /></td>
										<td><input type="text" name="data_pendapatan_suami" 			value="<?php echo set_value('data_pendapatan_suami', isset($data->data_pendapatan_suami) ? $data->data_pendapatan_suami : ''); ?>" 	class="form-control" /></td>
									</tr>
									<tr>
										<td>Istri</td>
										<td><input type="text" name="data_pendapatan_istrijenisusaha" 	value="<?php echo set_value('data_pendapatan_istrijenisusaha', isset($data->data_pendapatan_istrijenisusaha) ? $data->data_pendapatan_istrijenisusaha : ''); ?>" class="form-control" /></td>
										<td><input type="text" name="data_pendapatan_istrilama" 					value="<?php echo set_value('data_pendapatan_istrilama', isset($data->data_pendapatan_istrilama) ? $data->data_pendapatan_istrilama : ''); ?>" 	class="form-control" /></td>
										<td><input type="text" name="data_pendapatan_istri" 			value="<?php echo set_value('data_pendapatan_istri', isset($data->data_pendapatan_istri) ? $data->data_pendapatan_istri : ''); ?>" 	class="form-control" /></td>
									</tr>
									<tr>
										<td>Lainny</td>
										<td><input type="text" name="data_pendapatan_lainjenisusaha" 	value="<?php echo set_value('data_pendapatan_lainjenisusaha', isset($data->data_pendapatan_lainjenisusaha) ? $data->data_pendapatan_lainjenisusaha : ''); ?>" class="form-control" /></td>
										<td><input type="text" name="data_pendapatan_lainlama" 			value="<?php echo set_value('data_pendapatan_lainlama', isset($data->data_pendapatan_lainlama) ? $data->data_pendapatan_lainlama : ''); ?>" 	class="form-control" /></td>
										<td><input type="text" name="data_pendapatan_lain" 				value="<?php echo set_value('data_pendapatan_lain', isset($data->data_pendapatan_lain) ? $data->data_pendapatan_lain : ''); ?>" 	class="form-control" /></td>
									</tr>
									
									<tr>
										<td colspan="3">TOTAL</td>
										<td><input type="text" name="data_pendapatan_total" 			value="<?php echo set_value('data_pendapatan_total', isset($data->data_pendapatan_total) ? $data->data_pendapatan_total : ''); ?>" 	class="form-control" /></td>
									</tr>
								</tbody>
							</table>
							
							
						</div>
				
						<!-- Pengeluaran -->
						<div class="tab-pane" id="pengeluaran">
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Konsumsi Beras</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_beras" value="<?php echo set_value('data_pengeluaran_beras', isset($data->data_pengeluaran_beras) ? $data->data_pengeluaran_beras : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Belanja Dapur</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_dapur" value="<?php echo set_value('data_pengeluaran_dapur', isset($data->data_pengeluaran_dapur) ? $data->data_pengeluaran_dapur : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Rekening (listrik, air, tlp)</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_rekening" value="<?php echo set_value('data_pengeluaran_rekening', isset($data->data_pengeluaran_rekening) ? $data->data_pengeluaran_rekening : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pulsa Handphone</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_pulsa" value="<?php echo set_value('data_pengeluaran_pulsa', isset($data->data_pengeluaran_pulsa) ? $data->data_pengeluaran_pulsa : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kreditan</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_kreditan" value="<?php echo set_value('data_pengeluaran_kreditan', isset($data->data_pengeluaran_kreditan) ? $data->data_pengeluaran_kreditan : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Arisan</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_arisan" value="<?php echo set_value('data_pengeluaran_arisan', isset($data->data_pengeluaran_arisan) ? $data->data_pengeluaran_arisan : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan Anak</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_pendidikan" value="<?php echo set_value('data_pengeluaran_pendidikan', isset($data->data_pengeluaran_pendidikan) ? $data->data_pengeluaran_pendidikan : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Konsumsi Umum</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_umum" value="<?php echo set_value('data_pengeluaran_umum', isset($data->data_pengeluaran_umum) ? $data->data_pengeluaran_umum : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"> Angs. Pinjaman Lain</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_angsuranlain" value="<?php echo set_value('data_pengeluaran_angsuranlain', isset($data->data_pengeluaran_angsuranlain) ? $data->data_pengeluaran_angsuranlain : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Total</label>
								<div class="col-sm-4">
									<input type="text" name="data_pengeluaran_total" value="<?php echo set_value('data_pengeluaran_total', isset($data->data_pengeluaran_total) ? $data->data_pengeluaran_total : ''); ?>" 	class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Saving Power</label>
								<div class="col-sm-4">
									<input type="text" name="data_savingpower" value="<?php echo set_value('data_savingpower', isset($data->data_savingpower) ? $data->data_savingpower : ''); ?>" 	class="form-control" />
								</div>
							</div>
						</div>

						<div class="tab-pane" id="pembiayaan">
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pilih Sumber Pembiayaan</label>
								<div class="col-sm-4">
									<select name="client_pembiayaan_sumber" class="form-control">
									<?php if($client->client_pembiayaan_sumber == null) { ?> 
										<?php echo '<option value="" selected="selected">Pilih Investor</option>'; ?>
										<?php for($i = 0; $i < count($investor); $i++) { ?>
										<?php 
											echo '<option value="';
											echo $investor[$i]->lender_id.'">';
											echo $investor[$i]->lender_name.'</option>';
											?>
										<?php } ?>
									<?php } else { ?>
										<?php for($i = 0; $i < count($investor); $i++) { ?>
										<?php 
											if($investor[$i]->lender_id == $client->client_pembiayaan_sumber){
												$selected = 'selected="selected"';
												echo '<option value="';
												echo $investor[$i]->lender_id.'" '.$selected.'>';
												echo $investor[$i]->lender_name.'</option>';
											}else{
												echo '<option value="';
												echo $investor[$i]->lender_id.'">';
												echo $investor[$i]->lender_name.'</option>';
											}
											?>	
										<?php } ?>
									<?php } ?>
									</select>
								</div>
							</div>
						</div>						
					</div>
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-2 ">
							<input type="hidden" name="data_client" value="<?php echo $data_client; ?>" />
							<input type="hidden" name="data_id" value="<?php echo $data->data_id; ?>" />
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
		</form>
	</div>
</div>	