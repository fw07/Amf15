<section class="main">
	<div class="container">
			
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-5 m-b-xs">
					<a class="btn btn-sm btn-default">Registrasi Anggota</a>
				</div>
			
				
				<div class="col-sm-3 pull-right">
					<div class="input-group">
						<input type="text" class="input-sm form-control" placeholder="Search"> <span class="input-group-btn"> <button class="btn btn-sm btn-default" type="button">Go!</button> </span> 
					</div>
				</div>
			</div>
			
			<!-- TABLE BODY -->
			<div class="table-responsive">
					<?php if($this->session->flashdata('message')){ ?>
						<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
					<?php } ?>
					
					<table class="table table-striped m-b-none text-sm">      
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th width="150px">Nomor Rekening</th>
							<th>Nama Lengkap</th>
							<th>Majelis</th>
							<th>Cabang</th>
							<th width="80px">Manage</th>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php 	if(empty($no)){ 
									$no=1; 
									$nostart=1;
									$noend=$config['per_page'];
								}else{ 
									$no=$no+1;
									$nostart=$no;
									$noend=$nostart+$config['per_page']-1;
								} 
						?>
						<?php foreach($clients as $c):  ?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>					              
								<td><?php echo $c->data_rekening; ?></td>
								<td><?php echo $c->data_namalengkap; ?></td>
								<td><a href="<?php echo site_url()."/group/view/".$c->data_majelis; ?>" title="View This Group"><?php echo $c->data_majelis; ?></a></td>
								<td><a href="<?php echo site_url()."/branch/view/".$c->data_cabang; ?>" title="View This Branch"><?php echo $c->data_cabang; ?></a></td>
								<td class="text-center">
									<a href="<?php echo site_url()."/clients/view/".$c->data_id; ?>" title="View"><i class="fa fa-search"></i></a> 
									<a href="<?php echo site_url()."/clients/edit/".$c->data_id; ?>" title="Edit"><i class="fa fa-pencil"></i></a> 
									<a href="<?php echo site_url()."/clients/delete/".$c->data_id; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a></td>
							</tr>
							
						<?php $no++; endforeach; ?>
						<?php echo $list;?>
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