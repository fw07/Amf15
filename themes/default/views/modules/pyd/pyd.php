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
					<a href="<?php echo site_url().'/group/register/'; ?>" class="btn btn-sm btn-info" >Registrasi Majelis</a>
				</div>
				<form action="" method="post"> 
				<div class="col-sm-3 pull-right">
					<div class="input-group">
						
						<input type="text" name="q" class="input-sm form-control" placeholder="Search Group"> <span class="input-group-btn">
						<button class="btn btn-sm btn-default" type="submit">Go!</button> </span> 
						
					</div>
				</div>
				</form>
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>                  
					  <tr>
						<th width="30px">No</th>
						<th>No Majelis</th>
						<th>Majelis</th>
						<th>Cabang</th>
						<th>Area</th>
						<th>TPL</th>
						<th width="80px" class="text-center">Manage</th>
					  </tr>                  
					</thead> 
					<tbody>	
					<?php
						$total_rows=$config['total_rows'];
						$no=1; 
						/*if(empty($no)){ 
							$no=1; 
							$nostart=1;
							$noend=$config['per_page'];
							if($noend>$total_rows){ $noend=$total_rows; }
						}else{ 
							$no=$no+1;
							$nostart=$no;
							$noend=$nostart+$config['per_page']-1;
							if($noend>$total_rows){ $noend=$total_rows; }
						} */
					?>
					
					<?php foreach($pyd as $c):  ?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>
							<td><?php echo $c->client_name; ?></td>
							<td><?php echo $c->group_name; ?></td>
							<td><a href="<?php echo site_url()."/branch/view/".$c->group_branch; ?>" title="View This Branch"><?php echo $c->branch_name; ?></a></td>
							<td><?php echo $c->area_name; ?></td>
							<td><?php echo $c->officer_name; ?></td>
							<td class="text-center">
								<a href="<?php echo site_url()."/group/view/".$c->group_id; ?>" title="View"><i class="fa fa-search"></i></a>
								<a href="<?php echo site_url()."/group/edit/".$c->group_id; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
								<a href="<?php echo site_url()."/group/delete/".$c->group_id; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a></td>
						</tr>
					<?php $no++; endforeach; ?>
					</tbody>	
				</table>  
			</div>
			<!-- <footer class="panel-footer">
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
	</section>
	</div>
</section>	