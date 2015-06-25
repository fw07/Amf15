<section class="main">
	<div class="container">
			
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
					
		<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-4 m-b-xs">
					<a href="<?php echo site_url().'/clients/register/'; ?>" class="btn btn-sm btn-info" >Registrasi Anggota</a>
				</div>
				
				<!-- SEARCH FORM -->
				<form action="" method="post"> 
					<div class="col-sm-4 m-b-xs pull-right text-right">
						<select name="key" class="input-sm form-control input-s-sm inline">
							<option value="fullname">Nama </option>
							<option value="account">No Rekening</option>
						</select>
						<input type="text" name="q" class="input-sm form-control input-s-sm inline" placeholder="Search">
						<button class="btn btn-sm btn-default" type="submit">Go!</button>
					</div>
				</form>					
			</div>
			
			<!-- TABLE BODY -->
			<div class="table-responsive">
					<table class="table table-striped m-b-none text-sm">      
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th width="150px">Nomor Rekening</th>
							<th>Nama Lengkap</th>
							<th>Majelis</th>
							<th>Cabang</th>
							<!--<th class="text-center" width="50px"><i class="fa fa-money"></i></th>-->
							<th width="100px" class="text-center">Manage</th>
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
						<?php foreach($clients as $c):  ?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>					              
								<td><?php echo $c->client_account; ?></td>
								<td><?php echo $c->client_fullname; ?></td>
								<td><a href="<?php echo site_url()."/group/view/".$c->client_group; ?>" title="View This Group"><?php echo $c->group_name; ?></a></td>
								<td><a href="<?php echo site_url()."/branch/view/".$c->client_branch; ?>" title="View This Branch"><?php echo $c->branch_name; ?></a></td>
								<!--<td class="text-center"><span class="label label-success"><?php echo $c->client_pembiayaan; ?></span></td>								-->
								<td class="text-center">
									<a href="<?php echo site_url()."/clients/view/".$c->client_id; ?>" title="View"><i class="fa fa-search"></i></a> 
									<a href="<?php echo site_url()."/clients/edit/".$c->client_id; ?>" title="Edit"><i class="fa fa-pencil"></i></a> 
									<a href="<?php echo site_url()."/clients/summary/".$c->client_id; ?>" title="Pembiayaan"><i class="fa fa-money"></i></a> 
									<a href="<?php echo site_url()."/clients/delete/".$c->client_id; ?>" title="Delete" onclick="return confirmDialog();" ><i class="fa fa-trash-o"></i></a></td>
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