<?php 
$user_level = $this->session->userdata('user_level');
?>

<section class="main">
	<div class="container">
			
		<div id="module_title">
			<div class="m-b-md">
				<div class="row">
					<div class="col-lg-6"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
					<div class="col-lg-6 text-right"><br/><a href="<?php echo site_url();?>/tabberjangka/register" class="btn btn-sm btn-primary">Registrasi Tabungan Berjangka</a></div>
				</div>
			</div>
		</div>
		
		<?php if($this->session->flashdata('message')){ ?>
				<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
		<?php } ?>
					
		<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				
				<!-- SEARCH FORM -->
				<form action="" method="post"> 
					<div class="col-sm-12 m-b-xs pull-left text-center">
						Cari Tabungan Berjangka : 
						<select name="key" class="input-sm form-control input-s-sm inline">
							<option value="fullname">Nama </option>
							<option value="account">No Rekening</option>
							<option value="group">Majelis</option>
						</select>
						<input type="text" name="q" class="input-sm form-control input-s-sm inline" placeholder="Search">
						<button class="btn btn-sm btn-default" type="submit">Go!</button>
					</div>
				</form>					
			</div>
			
			<!-- TABLE BODY -->
			<?php if($this->input->post('q')){ ?>
			<div class="table-responsive">
					<table class="table table-striped m-b-none text-sm">      
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th width="150px">Nomor Rekening</th>
							<th>Nama Lengkap</th>
							<th>Majelis</th>
							<th>Cabang</th>
							<th class="text-right">Plafond</th>
							<th class="text-center">Angs. Ke</th>
							<th class="text-right">Saldo</th>
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
								<td><a href="<?php echo site_url()."/clients/summary/".$c->client_id; ?>" title="View Client"><?php echo $c->client_account; ?></a></td>
								<td><a href="<?php echo site_url()."/clients/summary/".$c->client_id; ?>" title="View Client"><?php echo $c->client_fullname; ?></a></td>
								<td><a href="<?php echo site_url()."/group/view/".$c->client_group; ?>" title="View Group"><?php echo $c->group_name; ?></a></td>
								<td><?php echo $c->branch_name; ?></td>								
								<td class="text-right"><?php echo number_format($c->tabberjangka_plafond); ?></td>
								<td class="text-center"><?php echo number_format($c->tabberjangka_angsuranke); ?></td>
								<td class="text-right"><?php echo number_format($c->tabberjangka_saldo); ?></td>
								<td class="text-center">
									<a href="<?php echo site_url()."/tabberjangka/tabberjangka_view/".$c->client_account; ?>" title="View"><i class="fa fa-search"></i></a> 
									<?php if($user_level==1){ ?>
									<a href="<?php echo site_url()."/tabberjangka/tabberjangka_edit/".$c->client_account; ?>" title="Edit"><i class="fa fa-pencil"></i></a> 
									<?php } ?>
								</td>
							</tr>
							
						<?php $no++; endforeach; ?>
						<?php echo $list;?>
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
				<?php } ?>
			</section>
		</div>
</section>