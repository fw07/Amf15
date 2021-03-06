<section class="main">

	<div class="container">
		<div class="row">
			<div class="col-sm-6">
				<div id="module_title">
					<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
				</div>
			</div>
			<div class="col-sm-6 text-right"><br/>
				<!--<a href="<?php echo site_url()."/clients/pembiayaan_reg/".$id; ?>" class="btn btn-info"><i class="fa fa-plus"></i> Registrasi Pembiayaan</a>
				<a href="<?php echo site_url()."/clients/saving_reg/".$id; ?>" class="btn btn-primary" onclick="return confirmDialog();"><i class="fa fa-book"></i> Registrasi Tabungan</a>-->
			</div>
		</div>
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
		
		<div class="row">
			<div class="col-sm-6">
				<section class="panel panel-default">
					<header class="panel-heading font-bold">DATA ANGGOTA</header>
						<div class="panel-body">
							<form role="form" class="form-horizontal">
								<div class="form-group">
									<label for="" class="col-sm-5 control-label">Nomor Rekening</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo $client->client_account; ?>" readonly /></div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-5 control-label">Nama Lengkap</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo $client->client_fullname; ?>" readonly /></div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-5 control-label">Area</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo $client->area_name; ?>" readonly /></div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-5 control-label">Kantor Cabang</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo $client->branch_name; ?>" readonly /></div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-5 control-label">Majelis</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo $client->group_name; ?>" readonly /></div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-5 control-label">Pendamping Lapangan</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo $client->officer_name; ?>" readonly /></div>
								</div>
							</form>
							
						</div>
					</section>
				</div>
				
				<div class="col-sm-6">
					<section class="panel panel-default">
						<header class="panel-heading font-bold">TABUNGAN</header>
						<div class="panel-body">
							<form role="form" class="form-horizontal">
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">SALDO TAB. WAJIB</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo number_format($tabwajib->tabwajib_saldo);?>" readonly /></div>
									<label for="" class="col-sm-1 text-center control-label"><a href="<?php echo site_url();?>/saving/tabwajib_view/<?php echo $client->client_account; ?>" title="Lihat Transaksi"><i class="fa fa-search"></i></a></label>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">SALDO TAB. SUKARELA</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo number_format($tabsukarela->tabsukarela_saldo);?>" readonly /></div>
									<label for="" class="col-sm-1 text-center control-label"><a href="<?php echo site_url();?>/saving/tabsukarela_view/<?php echo $client->client_account; ?>" title="Lihat Transaksi"><i class="fa fa-search"></i></a></label>
								</div>
								
							</form>
						</div>
					</section>
					<section class="panel panel-default">
						<header class="panel-heading font-bold">PEMBIAYAAN AKTIF</header>
						<div class="panel-body">
							<form role="form" class="form-horizontal">
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">PLAFOND</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo number_format($pembiayaan_aktif->data_plafond ); ?>" readonly /></div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">PROFIT</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo number_format($pembiayaan_aktif->data_margin); ?>" readonly /></div>
								</div>	
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">ANGSURAN KE</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo $pembiayaan_aktif->data_angsuranke; ?>" readonly /></div>
								</div>	
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">SISA POKOK</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo number_format((50 - $pembiayaan_aktif->data_angsuranke) * $pembiayaan_aktif->data_angsuranpokok ); ?>" readonly /></div>
								</div>	
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">SISA PROFIT</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo number_format((50 - $pembiayaan_aktif->data_angsuranke) * ($pembiayaan_aktif->data_margin / 50) ); ?>" readonly /></div>
								</div>
								<!--
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">SISA ANGSURAN</label>
									<div class="col-sm-7"><input type="text" class="form-control" value="<?php echo  number_format($pembiayaan_aktif->data_sisaangsuran); ?>" readonly /></div>
								</div>
								-->
							</form>
						</div>
					</section>
				</div>
			</div><!-- end row -->
			
		
			

		
		<section class="panel panel-default">
				
			<!-- TABLE BODY -->
			<div class="table-responsive">
					<table class="table table-striped m-b-none text-sm">      
						<thead>                  
						  <tr>
							<th width="50px" class="text-center">Pembiayaan Ke</th>
							<th width="120px">Tanggal Pencairan</th>
							<th width="120px">Tanggal Jatuh Tempo</th>
							<th class="text-right">Plafond<br/>(Rp)</th>
							<th class="text-right">Angsuran<br/>(Rp)</th>
							<th width="70px" class="text-center">Absensi<br/>(%)</th>
							<th width="70px" class="text-center">Rata-rata<br/>Menabung (Rp)</th>
							<th class="text-center">PAR</th>
							<th class="text-center">PPI</th>
							<th class="text-center">CHI</th>
							<th>Status</th>
							<th width="100px" class="text-center">Manage</th>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php foreach($pembiayaan as $p):  ?>
						<?php 
							$count_clients_absensi_h = 0; 
							$count_clients_absensi_a = 0;
							$count_clients_absensi_s = 0;
							$count_clients_absensi_i = 0;
							$count_clients_absensi_c = 0;
							$count_clients_absensi_h = $this->clients_pembiayaan_model->count_clients_absensi_h($p->data_id);
							$count_clients_absensi_a = $this->clients_pembiayaan_model->count_clients_absensi_a($p->data_id);
							$count_clients_absensi_s = $this->clients_pembiayaan_model->count_clients_absensi_s($p->data_id);
							$count_clients_absensi_i = $this->clients_pembiayaan_model->count_clients_absensi_i($p->data_id);
							$count_clients_absensi_c = $this->clients_pembiayaan_model->count_clients_absensi_c($p->data_id);
							$total_clients_absen = $count_clients_absensi_a + $count_clients_absensi_s + $count_clients_absensi_i + $count_clients_absensi_c;
							$persentase_kehadiran = ceil($count_clients_absensi_h / ($total_clients_absen+$count_clients_absensi_h) * 100);
							
							$avg_tabsukarela = ceil($this->tabsukarela_model->avg_tabsukarela($p->data_id));
							?>
							<tr>     				              
								<td class="text-center"><?php echo $p->data_ke; ?></td>
								<td><?php echo $p->data_date_accept; ?></td>
								<td><?php echo $p->data_jatuhtempo; ?></td>							
								<td class="text-right"><?php echo number_format($p->data_plafond+$p->data_margin); ?></td>
								<td class="text-right"><?php echo number_format($p->data_totalangsuran); ?></td>
								<td class="text-center"><span class="badge <?php if($persentase_kehadiran >= 90){ echo 'bg-primary'; }elseif($persentase_kehadiran >= 85 AND $persentase_kehadiran < 90 ){ echo 'bg-warning'; }elseif($persentase_kehadiran < 85 ){ echo 'bg-danger'; };?>"><?php echo $persentase_kehadiran; ?></span></td>
								<td class="text-center"><?php echo number_format($avg_tabsukarela); ?></td>
								<td class="text-center"><?php echo $p->data_par; ?></td>
								<td class="text-center"><?php echo $p->data_popi_total." (".$p->data_popi_kategori.")"; ?></td>	
								<td class="text-center"><?php echo $p->data_rmc_total." (".$p->data_rmc_kategori.")"; ?></td>	
								<td><?php if($p->data_status == 1){echo "Berjalan";}elseif($p->data_status == 2){echo "Pengajuan";}elseif($p->data_status == 3){echo "Selesai";}elseif($p->data_status == 4){echo "Gagal Dropping";} ?></td>
								<td class="text-center">
									<a href="<?php echo site_url()."/clients/pembiayaan_view/".$p->data_id; ?>" title="View"><i class="fa fa-search"></i></a> 
									<a href="<?php echo site_url()."/pembiayaan/angsuran/".$p->data_id; ?>" title="History"><i class="fa fa-book"></i></a> 
									<?php if($p->deleted == "0"){ ?><a href="<?php echo site_url()."/clients/pembiayaan_edit/".$p->data_id; ?>" title="Edit"><i class="fa fa-pencil"></i></a> 
									<a href="<?php echo site_url()."/clients/pembiayaan_delete/".$p->data_id; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a><?php } ?>
								</td>
							</tr>
							
						<?php endforeach; ?>
						</tbody>	
					</table>  
					
				</div>
				
			</section>
		</div>
</section>