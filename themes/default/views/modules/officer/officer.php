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
				<div class="col-sm-5 m-b-xs">
					<a href="<?php echo site_url().'/officer/register/'; ?>" class="btn btn-sm btn-info">Registrasi Pendamping Lapangan</a>
				</div>
				<form action="" method="post"> 
				<div class="col-sm-3 pull-right">
					<div class="input-group">
						<input type="text" name="q" class="input-sm form-control" placeholder="Search"> 
						<span class="input-group-btn"> <button class="btn btn-sm btn-default" type="submit">Go!</button> </span> 
					</div>
				</div>
				</form>
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>                  
					  <tr>
						<th width="30px">No</th>
						<th>Nama</th>
						<th>No Pegawai</th>
						<th>Phone</th>
						<th>Cabang</th>
						<th>Area</th>
						<th width="80px" class="text-center">Manage</th>
					  </tr>                  
					</thead> 
					<tbody>	
					<?php $no=1; ?>
					<?php foreach($officer as $c):  ?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>					              
							<td><?php echo $c->officer_name; ?></td>
							<td><?php echo $c->officer_number; ?></td>
							<td><?php echo $c->officer_phone; ?></td>
							<td><?php echo $c->branch_name; ?></td>
							<td><?php echo $c->area_name; ?></td>
							<td class="text-center">
								<a href="<?php echo site_url()."/officer/view/".$c->officer_id; ?>" title="View"><i class="fa fa-search"></i></a>
								<a href="<?php echo site_url()."/officer/edit/".$c->officer_id; ?>" title="Edit"><i class="fa fa-pencil"></i></a>
								<a href="<?php echo site_url()."/officer/delete/".$c->officer_id; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a></td>
						</tr>
					<?php $no++; endforeach; ?>
					</tbody>	
				</table>  
			</div>
			<footer class="panel-footer">
					<div class="row">
						<div class="col-sm-4 text-left"> <small class="text-muted inline m-t-sm m-b-sm">Total <?php echo $officer_total;?> items</small></div>
						<div class="col-sm-4 text-right text-center-xs pull-right">
							<ul class="pagination pagination-sm m-t-none m-b-none">
								<?php echo $this->pagination->create_links(); ?>
							</ul>
						</div>
				</div>
			</footer>
			
	</section>
	</div>
</section>	