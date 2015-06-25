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
					<a href="<?php echo site_url().'/jurnal/jurnal_add'; ?>" class="btn btn-sm btn-info" >Tambah Jurnal</a>
				</div>
				<div class="col-sm-3 pull-right">
					<div class="input-group">
						<input type="text" class="input-sm form-control" placeholder="Search"> <span class="input-group-btn"> <button class="btn btn-sm btn-default" type="button">Go!</button> </span> 
					</div>
				</div>
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead>                  
					  <tr>
						<th width="30px">No</th>
						<th width="160px">Tanggal</th>
						<th>Code</th>
						<th>Deskripsi</th>
						<th>Debet</th>
						<th>Kredit</th>
						<th>Keterangan</th>
						<!--<th width="80px" class="text-center">Manage</th>-->
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
					<?php foreach($jurnal as $c):  ?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>
							<td><?php echo $c->jurnal_tgl; ?></td>
							<td><?php echo $c->jurnal_code; ?></td>
							<td><?php echo $c->jurnal_desc; ?></td>
							<td><?php echo $c->jurnal_debet; ?></td>
							<td><?php echo $c->jurnal_credit; ?></td>
							<td><?php echo $c->jurnal_remark; ?></td>
							<!--<td class="text-center">
								<a href="#" title="View"><i class="fa fa-search"></i></a>
								<a href="#" title="Edit"><i class="fa fa-pencil"></i></a>
								<a href="#" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a></td>-->
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