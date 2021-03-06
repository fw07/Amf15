<?php 
$user_level = $this->session->userdata('user_level');

	//FILTER DATE
	$date_start = $this->input->post('date_start');
	$date_end = $this->input->post('date_end');
	if($date_start AND $date_end){ 
		$date_start = $this->input->post('date_start');
		$date_end = $this->input->post('date_end');
	}else{
		$date_start = date('Y-m-d', strtotime('today - 30 days'));
		$date_end = date("Y-m-d");
	}

?>

<section class="main">
	<div class="container">
	
	<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
	</div>
	
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
		
	<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-4 m-b-xs">
					Cabang : <b><?php echo $this->session->userdata('user_branch_name'); ?></b><br/>
					Tanggal : <b><?php echo $date_start; ?></b> s/d <b><?php echo $date_end; ?></b>
				</div>
				<div class="col-sm-6 pull-right text-right">
					<form method="post" action="">	
						<div class="col-sm-12 m-b-xs pull-right text-right">
							<input type="text" name="q" class="input-sm form-control input-s-sm inline" placeholder="Search Group">
						
							<input type="text" name="date_start" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd"  placeholder="Date Start" value="<?php echo $date_start; ?>" />
							<input type="text" name="date_end" class="input-sm form-control input-s-sm inline datepicker-input" data-date-format="yyyy-mm-dd" placeholder="Date End" value="<?php echo $date_end; ?>"  />
							<button class="btn btn-sm inline btn-info" type="submit">Go!</button>
						</div>					
					</form>
				</div>
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>                  
					  <tr>
						<th width="30px">No</th>
						<th>No Majelis</th>
						<th width="200px">Nama Majelis</th>
						<th class="text-center">Jumlah<br/>Anggota</th>
						<th class="text-center">Absensi<br/>(%)</th>
						<!--<th class="text-center">Tanggal<br/>Pengesahan</th>-->
						<th>Cabang</th>
						<th>Pendamping</th>
						<th>Hari</th>
						<th>Jam</th>
						<th width="80px" class="text-center">Manage</th>
					  </tr>                  
					</thead> 
					<tbody>	
					<?php
						$total_client_on_group = 0;
						$no=1; 						
					?>
					<?php foreach($group as $c):  ?>
					<?php 
						$client_on_group = 0;
						$count_clients_absensi_h = 0; 
						$count_clients_absensi_not_h = 0;
						$client_on_group = $this->group_model->count_clients_on_group($c->group_id); 
						
						
						
						$count_clients_absensi_h = $this->group_model->count_clients_absensi_h_by_date($c->group_id, $date_start, $date_end);
						$count_clients_absensi_not_h = $this->group_model->count_clients_absensi_not_h_by_date($c->group_id, $date_start, $date_end);
						$persentase_kehadiran = ceil($count_clients_absensi_h / ($count_clients_absensi_not_h+$count_clients_absensi_h) * 100);
						
					?>
						<tr>     
							<td class="text-center"><?php echo $no; ?></td>
							<td><?php echo $c->group_number; ?></td>
							<td><?php echo $c->group_name; ?></td>
							<td class="text-center"><?php echo $client_on_group; ?></td>
							<td class="text-center"><span class="badge <?php if($persentase_kehadiran >= 90){ echo 'bg-primary'; }elseif($persentase_kehadiran >= 85 AND $persentase_kehadiran < 90 ){ echo 'bg-warning'; }elseif($persentase_kehadiran < 85 ){ echo 'bg-danger'; };?>"><?php echo $persentase_kehadiran; ?></span></td>
							<!--<td class="text-center"><?php echo $c->group_date; ?></td>-->
							<td><?php echo $c->branch_name; ?></td>
							<td><?php echo $c->officer_name; ?></td>
							<td><?php echo $c->group_schedule_day; ?></td>
							<td><?php echo $c->group_schedule_time; ?></td>
							<td class="text-center">
								<a href="<?php echo site_url()."/group/view/".$c->group_id; ?>" title="View"><i class="fa fa-search"></i></a>
								<?php if($user_level==1 OR $user_level==2){ ?>
								<a href="<?php echo site_url()."/branch/group_edit/".$c->group_id; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
								<a href="<?php echo site_url()."/branch/group_delete/".$c->group_id; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a></td>
								<?php } ?>
						</tr>
					<?php $no++; endforeach; ?>
					</tbody>	
				</table>  
			</div>
			<!--
			<footer class="panel-footer">
				<div class="row">
					<div class="col-sm-4 text-left"> <small class="text-muted inline m-t-sm m-b-sm">showing <?php echo $nostart; ?>-<?php echo $noend; ?> of <?php echo $config['total_rows']; ?> items</small></div>
					<div class="col-sm-5 text-right text-center-xs pull-right">
						<ul class="pagination pagination-sm m-t-none m-b-none">
							<?php echo $this->pagination->create_links(); ?>
						</ul>
					</div>
				</div>
			</footer>
			-->
			<footer class="panel-footer">
				<div class="row">
					<div class="col-sm-4 text-left">
					<b>Keterangan :</b><br/>
					<span class="badge bg-primary">&nbsp;&nbsp;</span> Presentase Kehadiran &gt; 90 %<br/>
					<span class="badge bg-warning">&nbsp;&nbsp;</span> Presentase Kehadiran 85-90 % <br/>
					<span class="badge bg-danger">&nbsp;&nbsp;</span> Presentase Kehadiran &lt; 85 %
					</div>
				</div>
			</footer>
	</section>
	</div>
</section>	