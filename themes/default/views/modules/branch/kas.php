<?php 
$user_level = $this->session->userdata('user_level');
?>

<section class="main">
	<div class="container">
			
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
		<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>', '</div>'); ?>
		
					
		<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-4 m-b-xs">
					<?php if($user_level==1 OR $user_level==2){ ?>
					<a href="<?php echo site_url().'/branch/kas_create/'; ?>" class="btn btn-sm btn-info" >Tambah Rekapitulasi Kas</a>
					<?php } ?>
				</div>
				
							
			</div>
			
			<!-- TABLE BODY -->
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
							<?php if($user_level==1 OR $user_level==2){ ?>
							<th class="text-center" width="80px">Manage</th>
							<?php } ?>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php
							if(empty($no)){ 
									$no=1; 
									$nostart=1;
									$noend=$config['per_page'];
								}else{ 
									$no=$no+1;
									$nostart=$no;
									$noend=$nostart+$config['per_page']-1;
								} 
						?>
						<?php foreach($kas as $c):  ?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>	
								<td><?php echo $c->kas_date; ?></td>
								<td><?php echo $c->branch_name; ?></td>
								<td class="text-right"><?php echo number_format($c->kas_brangkas); ?></td>
								<td class="text-right"><?php echo number_format($c->kas_rf); ?></td>
								<td class="text-right"><?php echo number_format($c->kas_amanah); ?></td>
								<td class="text-right"><?php echo number_format($c->kas_total); ?></td>
								<?php if($user_level==1 OR $user_level==2){ ?>
								<td class="text-center">
									<a href="<?php echo site_url()."/branch/kas_delete/".$c->kas_id; ?>" title="Delete Kas"><i class="fa fa-trash-o" onclick="return confirmDialog();" ></i></a>  
								</td>
								<?php } ?>
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