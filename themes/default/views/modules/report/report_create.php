<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>			
		</div>
		
		
		<div class="row">
			&nbsp;&nbsp;&nbsp;&nbsp;
			From <b><?php echo date("d M Y",strtotime($start_date)); ?></b> to <b><?php echo date("d M Y",strtotime($end_date)); ?></b>
			<br/><br/>
		</div>
							
		<form class="form-horizontal" enctype="multipart/form-data" id="" action="" method="post">
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#saldo" data-toggle="tab">Posisi Kas</a></li>
						<li class=""><a href="#group" data-toggle="tab">Majelis</a></li>
						<li class=""><a href="#anggota" data-toggle="tab">Anggota Baru</a></li>
						<li class=""><a href="#pengajuan" data-toggle="tab">Pengajuan</a></li>
						<li class=""><a href="#pencairan" data-toggle="tab">Pencairan</a></li>
						<li class=""><a href="#anggotakeluar" data-toggle="tab">Anggota Keluar</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
					
						<!-- POSISI KAS -->
						<div class="tab-pane active" id="saldo">							
							<div class="table-responsive">
								<table class="table table-striped m-b-none text-sm">      
									<thead>                  
									  <tr>
										<th width="30px">No</th>
										<th width="150px">Tanggal</th>
										<th>Cabang</th>
										<th class="text-right">Brangkas (Rp)</th>
										<th class="text-right">RF (Rp)</th>
										<th class="text-right">Amanah (Rp)</th>
										<th class="text-right">Total (Rp)</th>
									  </tr>                  
									</thead> 
									<tbody>	
									<?php $no=1;$total_saldo=0; ?>
									<?php foreach($kas as $c):  ?>
									<?php 
										$kas_total=$c->kas_total;
										$total_saldo = $total_saldo+$kas_total;
									?>
										<tr>     
											<td align="center"><?php echo $no; ?></td>	
											<td><?php echo $c->kas_date; ?></td>
											<td><?php echo $c->branch_name; ?></td>
											<td class="text-right"><?php echo number_format($c->kas_brangkas); ?></td>
											<td class="text-right"><?php echo number_format($c->kas_rf); ?></td>
											<td class="text-right"><?php echo number_format($c->kas_amanah); ?></td>
											<td class="text-right"><?php echo number_format($c->kas_total); ?></td>											
										</tr>										
									<?php $no++; endforeach; ?>
									</tbody>	
								</table>								
							</div>
						</div>		
						<input type="hidden" name="report_saldo" value="<?php echo $total_saldo; ?>" />
						
						<!-- GROUP -->
						<div class="tab-pane" id="group">
							<div class="table-responsive">  
								<h4>Majelis Baru</h4>
								<table class="table table-striped m-b-none text-sm">              
									<thead>                  
									  <tr>
										<th width="30px">No</th>
										<th>No Majelis</th>
										<th>Majelis</th>
										<th class="text-center">Jumlah Anggota</th>
										<th class="text-center">Tanggal Pengesahan</th>
										<th>Cabang</th>
										<th>Pendamping</th>
										<th>Hari</th>
										<th>Jam</th>
									  </tr>                  
									</thead> 
									<tbody>	
									<?php $no=1;?>
									<?php foreach($group as $c):  ?>
									<?php $client_on_group = $this->group_model->count_clients_on_group($c->group_id);  ?>
										<tr>     
											<td class="text-center"><?php echo $no; ?></td>
											<td><?php echo $c->group_number; ?></td>
											<td><?php echo $c->group_name; ?></td>
											<td class="text-center"><?php echo $client_on_group; ?></td>
											<td class="text-center"><?php echo $c->group_date; ?></td>
											<td><a href="<?php echo site_url()."/branch/view/".$c->group_branch; ?>" title="View This Branch"><?php echo $c->branch_name; ?></a></td>
											<td><?php echo $c->officer_name; ?></td>
											<td><?php echo $c->group_schedule_day; ?></td>
											<td><?php echo $c->group_schedule_time; ?></td>
											
										</tr>
									<?php $no++; endforeach; ?>
									</tbody>	
								</table>  
							</div>			
						</div>
						<input type="hidden" name="report_groupnew" value="<?php echo ($no-1); ?>" />
						
						<!-- ANGGOTA -->
						<div class="tab-pane" id="anggota">
							<div class="table-responsive">
								<table class="table table-striped m-b-none text-sm">      
									<thead>                  
									  <tr>
										<th width="30px">No</th>
										<th width="150px">Nomor Rekening</th>
										<th>Nama Lengkap</th>
										<th class="text-center">Majelis</th>
										<th class="text-center">Cabang</th>
										<th class="text-center">Tanggal Registrasi</th>
										<th class="text-center" width="50px">Pembiayaan</th>
										<th class="text-center" width="50px">Status</th>
									  </tr>                  
									</thead> 
									<tbody>	
									<?php $no=1;?>
									<?php foreach($client as $c):  ?>
										<tr>     
											<td align="center"><?php echo $no; ?></td>					              
											<td><?php echo $c->client_account; ?></td>
											<td><?php echo $c->client_fullname; ?></td>
											<td class="text-center"><a href="<?php echo site_url()."/branch/group_view/".$c->client_group; ?>" title="View This Group"><?php echo $c->group_name; ?></a></td>
											<td class="text-center"><?php echo $c->branch_name; ?></td>
											<td class="text-center"><?php echo $c->client_reg_date; ?></td>
											<td class="text-center"><span class="label label-success"><?php echo $c->client_pembiayaan; ?></span></td>
											<td class="text-center"><?php if($c->client_status == "1"){ echo "Aktif";}else{ echo "Keluar"; }; ?></td>
										</tr>										
									<?php $no++; endforeach; ?>
									</tbody>	
								</table> 								
							</div>
						</div>
						<input type="hidden" name="report_anggotabaru" value="<?php echo ($no-1); ?>" />
						
						<!-- PENGAJUAN -->
						<div class="tab-pane" id="pengajuan">
							<div class="table-responsive">
								<table class="table table-striped m-b-none text-sm">      
									<thead>                  
									  <tr>
										<th width="30px">No</th>
										<th width="150px">No. Rekening</th>
										<th>Nama Lengkap</th>
										<th class="text-center">Majelis</th>
										<th class="text-center">Plafond</th>
										<th class="text-center">Ke</th>
										<th class="text-center">Tgl Pengajuan</th>
										<th class="text-center">Tgl Pencairan</th>
										<th class="text-left" width="130px">Status</th>
									  </tr>                  
									</thead> 
									<tbody>	
									<?php $no=1; $pengajuan_total=0;?>
									<?php foreach($pengajuan as $c):  ?>
									<?php 
										$plafond=$c->data_plafond;
										$pengajuan_total = $pengajuan_total+$plafond;
									?>
										<tr>     
											<td align="center"><?php echo $no; ?></td>					              
											<td><?php echo $c->client_account; ?></td>
											<td><?php echo $c->client_fullname; ?></td>
											<td class="text-center"><a href="<?php echo site_url()."/branch/group_view/".$c->client_group; ?>" title="View This Group"><?php echo $c->group_name; ?></a></td>
											<td class="text-center"><?php echo $c->data_pengajuan; ?></td>
											<td class="text-center"><?php echo $c->data_ke; ?></td>
											<td class="text-center"><?php echo $c->data_tgl; ?></td>
											<td class="text-center"><?php echo $c->data_date_accept; ?></td>
											<td class="text-left"><?php if($c->data_status_pengajuan == "v"){ echo "Disetujui"; }elseif($c->data_status_pengajuan == "x"){ echo "Ditunda"; }elseif($c->data_status_pengajuan == "k"){ echo "Komite"; } ?></td>											
										</tr>
										
									<?php $no++; endforeach; ?>
									</tbody>	
								</table>  
							</div>
						</div>
						<input type="hidden" name="report_pengajuan" value="<?php echo $pengajuan_total; ?>" />
						
						<!-- PENCAIRAN -->
						<div class="tab-pane" id="pencairan">	
							<div class="table-responsive">				
								<table class="table table-striped m-b-none text-sm">      
									<thead>                  
									  <tr>
										<th width="30px">No</th>
										<th width="150px">No. Rekening</th>
										<th>Nama Lengkap</th>
										<th class="text-center">Majelis</th>
										<th class="text-center">Plafond</th>
										<th class="text-center">Profit</th>
										<th class="text-center">Angsuran</th>
										<th class="text-center">Ke</th>
										<th class="text-center">Tgl Pengajuan</th>
										<th class="text-center">Tgl Pencairan</th>
										<th class="text-left">Status</th>
									  </tr>                  
									</thead> 
									<tbody>	
									<?php $no=1; $plafond_cair=0; $pencairan_total=0; ?>
									<?php foreach($pencairan as $c):  ?>
									<?php 
										$plafond_cair=$c->data_plafond;
										$pencairan_total = $pencairan_total+$plafond_cair;
									?>
										<tr>     
											<td align="center"><?php echo $no; ?></td>					              
											<td><?php echo $c->client_account; ?></td>
											<td><?php echo $c->client_fullname; ?></td>
											<td class="text-center"><a href="<?php echo site_url()."/branch/group_view/".$c->client_group; ?>" title="View This Group"><?php echo $c->group_name; ?></a></td>
											<td class="text-center"><?php echo $c->data_plafond; ?></td>
											<td class="text-center"><?php echo $c->data_margin; ?></td>
											<td class="text-center"><?php echo ((($c->data_plafond + $c->data_margin)/50) + $c->data_tabunganwajib ); ?></td>
											<td class="text-center"><?php echo $c->data_ke; ?></td>
											<td class="text-center"><?php echo $c->data_tgl; ?></td>
											<td class="text-center"><?php echo $c->data_date_accept; ?></td>
											<td class="text-left"><?php if($c->data_status_pengajuan == "v"){ echo "Disetujui"; }elseif($c->data_status_pengajuan == "x"){ echo "Ditunda"; }elseif($c->data_status_pengajuan == "k"){ echo "Komite"; } ?></td>											
										</tr>
										
									<?php $no++; endforeach; ?>
									</tbody>	
								</table> 								
							</div>
						</div>	
						<input type="hidden" name="report_pencairan" value="<?php echo $pencairan_total; ?>" />
						
						
						
						<!-- ANGGOTA KELUAR -->
						<div class="tab-pane" id="anggotakeluar">
							<div class="table-responsive">				
								<table class="table table-striped m-b-none text-sm">      
									<thead>                  
										<tr>
											<th>Majelis</th>
											<th>Nama</th>
											<th width="100px">Tgl Keluar</th>
											<th>Alasan</th>
											<th class="text-center">Ke</th>
											<th>Tab Wajib</th>
											<th>Tab Cadangan</th>
											<th>Tab Sukarela</th>
											<th>Pendamping</th>
											<th>Pewawancara</th>
										</tr>                 
									</thead> 
									<tbody>	
									<?php $no=1;?>
									<?php foreach($client_unreg as $c):  ?>
										<tr>
											<td><?php echo $c->group_name; ?></td>
											<td><?php echo $c->client_fullname; ?></td>
											<td><?php echo $c->client_unreg_date; ?></td>
											<td><?php echo $c->alasan_name; ?></td>
											<td class="text-center"><?php echo $c->client_pembiayaan; ?></td>
											<td>0</td>
											<td>0</td>
											<td>0</td>
											<td><?php echo $c->officer_name; ?></td>
											<td><?php echo $c->officer_name; ?></td>
										</tr>	
										
									<?php $no++; endforeach; ?>
									</tbody>	
								</table> 
							</div>
							<input type="hidden" name="report_anggotakeluar" value="<?php echo ($no-1); ?>" />
							
						</div>
					</div>
				</div><!-- End Panel Body -->
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-3 ">
							<!--<form method="post" action="report/generate">-->
							<input type="hidden" name="startdate" value="<?php echo $start_date; ?>" />
							<input type="hidden" name="enddate" value="<?php echo $end_date; ?>" />
							<button type="submit" class="btn btn-primary">Submit Report</button>
							<!--</form>-->
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	
