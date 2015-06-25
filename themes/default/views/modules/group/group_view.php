<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title." <b>(".$data->group_name.")</b>"; ?></h3></div>
		</div>
	
		<form class="form-horizontal" enctype="multipart/form-data" id="" action="" method="post">
				
				
				<div class="row">
					<!-- SECTION LEFT -->								
					<div class="col-md-5">	
						<!-- Informasi Majelis -->
						<section class="panel panel-default">
							<header class="panel-heading font-bold">Informasi Majelis</header>
							
							<div class="panel-body">
										<div class="form-group">
											<label for="group_area" class="col-sm-4 control-label">Area</label>
											<div class="col-sm-8">
												<input type="text" name="group_area" class="form-control" id="group_area" placeholder="" value="<?php echo set_value('area_name', isset($data->area_name) ? $data->area_name : ''); ?>" readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_branch" class="col-sm-4 control-label">Kantor Cabang</label>
											<div class="col-sm-8">
												<input type="text" name="group_branch" class="form-control" id="group_branch" placeholder="" value="<?php echo set_value('branch_name', isset($data->branch_name) ? $data->branch_name : ''); ?>" readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_tpl" class="col-sm-4 control-label">Petugas Pendamping</label>
											<div class="col-sm-8">
												<input type="text" name="group_tpl" class="form-control" id="group_tpl" placeholder="" value="<?php echo $data->officer_name; ?>" readonly />
											</div>
										</div>
										
										<div class="form-group">
											<label for="group_tpl" class="col-sm-4 control-label">Nomor Majelis</label>
											<div class="col-sm-8">
												<input type="text" name="group_number" class="form-control" id="group_number" placeholder="" value="<?php echo $data->group_number; ?>" readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_name" class="col-sm-4 control-label">Nama Majelis</label>
											<div class="col-sm-8">
												<input type="text" name="group_name" class="form-control" id="group_name" placeholder="" value="<?php echo set_value('group_name', isset($data->group_name) ? $data->group_name : ''); ?>" readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_leader" class="col-sm-4 control-label">Ketua Majelis</label>
											<div class="col-sm-8">
												<input type="text" name="group_leader" class="form-control" id="group_leader" placeholder="" value="<?php echo set_value('group_leader', isset($data->group_leader) ? $data->group_leader : ''); ?>" readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_leaderphone" class="col-sm-4 control-label">Telp Ketua Majelis</label>
											<div class="col-sm-8">
												<input type="text" name="group_leaderphone" class="form-control" id="group_leaderphone" placeholder="" value="<?php echo set_value('group_leaderphone', isset($data->group_leaderphone) ? $data->group_leaderphone : ''); ?>" readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_date" class="col-sm-4 control-label">Tanggal Terbentuk</label>
											<div class="col-sm-8">
												<input type="text" name="group_date" class="form-control datepicker-input" data-date-format="dd-mm-yyyy" id="group_date" placeholder="" value="<?php echo set_value('group_date', isset($data->group_date) ? $data->group_date : ''); ?>" readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_rt" class="col-sm-4 control-label">Nama RT</label>
											<div class="col-sm-8">
												<input type="text" name="group_rt" class="form-control" id="group_rt" placeholder="" value="<?php echo set_value('group_rt', isset($data->group_rt) ? $data->group_rt : ''); ?>" readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_leader" class="col-sm-4 control-label">Kampung</label>
											<div class="col-sm-8">
												<input type="text" name="group_kampung" class="form-control" id="group_location" placeholder="" value="<?php echo set_value('group_kampung', isset($data->group_kampung) ? $data->group_kampung : ''); ?>"  readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_leader" class="col-sm-4 control-label">Desa</label>
											<div class="col-sm-8">
												<input type="text" name="group_desa" class="form-control" id="group_desa" placeholder="" value="<?php echo set_value('group_desa', isset($data->group_desa) ? $data->group_desa : ''); ?>"  readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_leader" class="col-sm-4 control-label">Kecamatan</label>
											<div class="col-sm-8">
												<input type="text" name="group_kecamatan" class="form-control" id="group_kecamatan" placeholder="" value="<?php echo set_value('group_kecamatan', isset($data->group_kecamatan) ? $data->group_kecamatan : ''); ?>"  readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_address" class="col-sm-3 control-label">Alamat</label>
											<div class="col-sm-8">
												<textarea type="text" name="group_address" class="form-control" id="group_address" readonly ><?php echo set_value('group_address', isset($data->group_address) ? $data->group_address : ''); ?></textarea>
											</div>
										</div>
										<div class="form-group">
											<label for="group_address_rt" class="col-sm-3 control-label">RT</label>
											<div class="col-sm-8">
												<input type="text" name="group_address_rt" class="form-control" id="group_address_rt" placeholder="" readonly value="<?php echo set_value('group_address_rt', isset($data->group_address_rt) ? $data->group_address_rt : ''); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="group_address_rw" class="col-sm-3 control-label">RW</label>
											<div class="col-sm-8">
												<input type="text" name="group_address_rw" class="form-control" id="group_address_rw" placeholder="" readonly value="<?php echo set_value('group_address_rw', isset($data->group_address_rw) ? $data->group_address_rw : ''); ?>">
											</div>
										</div>
										<div class="form-group">
											<label for="group_frequency" class="col-sm-4 control-label">Frekuensi Pertemuan</label>
											<div class="col-sm-8">
												<input type="text" name="group_frequency" class="form-control" id="group_frequency" placeholder="" value="<?php echo set_value('group_frequency', isset($data->group_frequency) ? $data->group_frequency : ''); ?>" readonly />
											</div>
										</div>
										<div class="form-group">
											<label for="group_schedule_day" class="col-sm-4 control-label">Jadwal Pertemuan</label>
											<div class="col-sm-4">
												<input type="text" name="group_schedule_day" class="form-control" id="group_schedule_day" placeholder="" value="<?php echo set_value('group_schedule_day', isset($data->group_schedule_day) ? $data->group_schedule_day : ''); ?>" readonly />
											</div>
											<div class="col-sm-4">									
												<input type="text" name="group_schedule_time" class="form-control" id="group_schedule_time" placeholder="" value="<?php echo set_value('group_schedule_time', isset($data->group_schedule_time) ? $data->group_schedule_time : ''); ?>" readonly />
											</div>
										</div>
										
								
								</div>
						</section>
					</div>
					<!-- END SECTION LEFT -->	
					
					<!-- SECTION RIGHT -->								
					<div class="col-md-7">
						<!-- Anggota Aktif -->
						<section class="panel panel-default">
							<header class="panel-heading font-bold">Anggota Aktif Pembiayaan</header>
							<div class="panel-body">
								<div>
									<table class="table table-striped m-b-none text-sm">  
										<tr>
											<td align="center"><b>NO</b></td>
											<td><b>NAMA</b></td>
											<td  align="right"><b>PLAFOND</b></td>
											<td  align="center"><b>PEMB.<br/>KE</b></td>
											<td  align="center"><b>ANGS.<br/>KE</b></td>
											<td  align="right"><b>SISA<br/>ANGS.</b></td>
											<td  align="right"><b>AKAD</b></td>
											<td width="50px" align="right"><b>PAR</b></td>
											<td width="50px" align="center" title="%absensi"><b>ABS</b><br/>(%)</td>
										</tr>
										<?php $no =1;?>
										<?php foreach($clients as $c):  ?>
										<?php 
											$count_clients_absensi_h = 0; 
											$count_clients_absensi_a = 0;
											$count_clients_absensi_s = 0;
											$count_clients_absensi_i = 0;
											$count_clients_absensi_c = 0;
											//$client_on_group = $this->client_model->count_clients_on_group($c->client_id); 
											$count_clients_absensi_h = $this->clients_model->count_clients_absensi_h($c->client_id);
											$count_clients_absensi_a = $this->clients_model->count_clients_absensi_a($c->client_id);
											$count_clients_absensi_s = $this->clients_model->count_clients_absensi_s($c->client_id);
											$count_clients_absensi_i = $this->clients_model->count_clients_absensi_i($c->client_id);
											$count_clients_absensi_c = $this->clients_model->count_clients_absensi_c($c->client_id);
											$total_clients_absen = $count_clients_absensi_a + $count_clients_absensi_s + $count_clients_absensi_i + $count_clients_absensi_c;
											$persentase_kehadiran = ceil($count_clients_absensi_h / ($total_clients_absen+$count_clients_absensi_h) * 100);
										?>
										<tr>
											
											<td align="center"><?php echo $no; ?></td>
											<td><a href="<?php echo site_url()."/clients/summary/".$c->client_id; ?>" title="View"><?php echo $c->client_fullname; ?></a></td>
											<td align="right"><?php echo number_format(($c->data_plafond)); ?></td>
											<td align="center"><?php echo number_format($c->client_pembiayaan); ?></td>
											<td align="center"><?php echo number_format($c->data_angsuranke); ?></td>
											<td align="right"><?php echo number_format(((50-$c->data_angsuranke)*$c->data_angsuranpokok)); ?></td>
											<td align="right"><?php echo $c->data_akad; ?></td>
											<td align="right"><?php echo $c->data_par; ?></td>
											<td class="text-center"><span class="badge <?php if($persentase_kehadiran >= 90){ echo 'bg-primary'; }elseif($persentase_kehadiran >= 85 AND $persentase_kehadiran < 90 ){ echo 'bg-warning'; }elseif($persentase_kehadiran < 85 ){ echo 'bg-danger'; };?>"><?php echo $persentase_kehadiran; ?></span></td>
										</tr>
										<?php $no++; endforeach; ?>
									</table>
									<footer class="panel-footer">
										<div class="row">
											<div class="col-sm-12 text-left">
											<b>Keterangan :</b><br/>
											<span class="badge bg-primary">&nbsp;&nbsp;</span> Kehadiran &gt; 90 %&nbsp;&nbsp;
											<span class="badge bg-warning">&nbsp;&nbsp;</span> Kehadiran 85-90 % &nbsp;&nbsp;
											<span class="badge bg-danger">&nbsp;&nbsp;</span> Kehadiran &lt; 85 %
											</div>
										</div>
									</footer>
								</div>
							</div>
						</section>
						
						<!-- Anggota Keluar -->
						<section class="panel panel-default">
							<header class="panel-heading font-bold">Anggota Keluar</header>
							
							<div class="panel-body">
								<div>
									<table class="table table-striped m-b-none text-sm">  
										<tr>
											<td><b>NAMA</b></td>
											<td  align="left"><b>TGL KELUAR</b></td>
											<td  align="left"><b>ALASAN</b></td>
										</tr>
										<?php foreach($clients_out as $c):  ?>
										<tr>
											<td><?php echo $c->client_fullname; ?></td>
											<td align="left"><?php echo $c->client_unreg_date; ?></td>
											<td align="left"><?php echo $c->alasan_name; ?></td>
										</tr>
										<?php endforeach; ?>
									</table>
								</div>								
							</div>
						</section>
						
					</div>
					<!-- END SECTION RIGHT -->	
				
				</div>
				
				<!-- Panel Footer
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-2 ">
							<input type="hidden" name="group_id" class="form-control" id="group_id" placeholder="" value="<?php echo set_value('group_id', isset($data->group_id) ? $data->group_id : ''); ?>">
							<a href="<?php echo site_url()."/group/edit/".$data->group_id; ?>" class="btn btn-primary">Modify Data</a>
						</div>
					</div>
				</div>
				 -->
			
			
			

			
			
			
		</form>
	</div>
</div>	