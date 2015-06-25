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
					<a href="<?php echo site_url().'/branch/report/new'; ?>" class="btn btn-sm btn-info" ><i class="fa fa-plus"></i> &nbsp;Create New Report</a>
				</div>
				<!--
				<form action="" method="post"> 
				<div class="col-sm-3 pull-right">
					<div class="input-group">						
						<input type="text" name="q" class="input-sm form-control" placeholder="Search Group"> <span class="input-group-btn">
						<button class="btn btn-sm btn-default" type="submit">Go!</button> </span> 						
					</div>
				</div>
				</form>
				-->
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>
					  <tr>
						<th width="30px" rowspan="2">No</th>
						<th rowspan="2">Kantor Cabang</th>
						<th rowspan="2" width="50px">Minggu</th>
						<th colspan="2" class="text-center">Tangal</th>
						<th colspan="2" class="text-center">Anggota</th>
						<th colspan="2"  class="text-center">Pembiayaan</th>
						<th rowspan="2" class="text-center">Saldo (Rp)</th>
						<th rowspan="2" class="text-center" width="30px">Status</th>
						<th rowspan="2" width="80px" class="text-center">Manage</th>
					  </tr> 
					  <tr>
						<th class="text-center" width="110px">Awal Minggu</th>
						<th class="text-center" width="115px">Akhir Minggu</th>
						<th class="text-center" width="50px">Baru</th>
						<th class="text-center" width="50px">Keluar</th>
						<th class="text-center">Pengajuan (Rp)</th>
						<th class="text-center">Pencairan (Rp)</th>
					  </tr>                 
					</thead> 
					<tbody>	
					<?php
						$total_rows=$config['total_rows'];
						if(empty($no)){ 
							$no=1; 
							$nostart=1;
							$noend=$config['per_page'];
							if($noend>$total_rows){ $noend=$total_rows; }
						}else{ 
							$no=$no+1;
							$nostart=$no;
							$noend=$nostart+$config['per_page']-1;
							if($noend>$total_rows){ $noend=$total_rows; }
						} 
					?>
					<?php foreach($report as $r):  ?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>
							<td><?php echo $r->branch_name; ?></td>
							<td class="text-center"><?php echo $r->report_week; ?></td>
							<td class="text-center">2013-01-01</td>
							<td class="text-center">2013-01-05</td>
							<td class="text-center"><?php echo $r->report_anggotabaru; ?></td>
							<td class="text-center"><?php echo $r->report_anggotakeluar; ?></td>
							<td class="text-right"><?php echo number_format($r->report_pengajuan); ?></td>
							<td class="text-right"><?php echo number_format($r->report_pembiayaan); ?></td>
							<td class="text-right"><?php echo number_format($r->report_saldo); ?></td>
							<td class="text-center"><?php if($r->report_accept == 1){ ?><i class="fa fa-check text-success" title="Accepted"></i><?php }else{ ?><i class="fa fa-bookmark text-warning" title="New"></i><?php } ?></td>
							<td class="text-center">
								<a href="<?php echo site_url()."/branch/report/view/".$r->report_id; ?>" title="View"><i class="fa fa-search"></i></a>
								<a href="<?php echo site_url()."/branch/report/delete/".$r->report_id; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a></td>
						</tr>
					<?php $no++; endforeach; ?>
					</tbody>	
				</table>  
			</div>
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
			
	</section>
	</div>
</section>	