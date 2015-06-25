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
					
		<section class="panel panel-default">
			<!-- TABLE HEADER -->
			<div class="row text-sm wrapper">
				<div class="col-sm-4 m-b-xs">
					<?php if($user_level==1 OR $user_level==2){ ?>
					<a href="<?php echo site_url().'/branch/client_unreg/'; ?>" class="btn btn-sm btn-info" ><i class="fa fa-plus"></i> Anggota Keluar</a>
					<?php } ?>
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
							<th width="120px">Nomor Rekening</th>
							<th>Nama</th>
							<th>Majelis</th>
							<th>Tgl Keluar</th>
							<th>Alasan</th>
							<th class="text-center">Ke</th>
							<th>Tab Wajib</th>
							<th>Tab Sukarela</th>
							<th>Pewawancara</th>
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
								<td class="text-left"><a href="<?php echo site_url()."/group/view/".$c->client_group; ?>" title="View This Group"><?php echo $c->group_name; ?></a></td>
								<td class="text-left"><?php echo $c->client_unreg_date; ?></td>
								<td class="text-left"><?php echo $c->alasan_name; ?></td>
								<td class="text-center"><?php echo $c->client_pembiayaan; ?></td>
								<td class="text-right"><?php echo $c->tabwajib_saldo; ?></td>
								<td class="text-right"><?php echo $c->tabsukarela_saldo; ?></td>
								<td class="text-left"><?php echo $c->officer_name; ?></td>
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