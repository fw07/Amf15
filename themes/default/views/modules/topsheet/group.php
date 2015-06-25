<?php $user_level = $this->session->userdata('user_level'); ?>

<section class="main">
	<div class="container">
	
	<div class="row text-sm wrapper">
		<!-- SEARCH FORM -->
		<div id="module_title" class="col-sm-4 m-b-xs">
				<h3 class="m-b-none"><?php echo $menu_title; ?></h3>
		</div>
		<?php if($user_level==1 OR $user_level==3){ ?>
		<div class="col-sm-4 m-b-xs pull-right text-right">
			<br/><form action="<?php echo site_url(); ?>/topsheet/ts_filter" method="post"> 
				<select name="key" class="input-sm form-control input-s-sm inline">
					<option value="fullname">Majelis</option>
					<?php foreach($listgroup as $list):  ?>
					<option value="<?php echo $list->group_id; ?>"><?php echo $list->group_name ." - ". $list->branch_name; ?></option>
					<?php endforeach; ?>
				</select>
				<button class="btn btn-sm btn-default" type="submit">Go!</button>
			</form>
		</div>	
		<?php } ?>	
	</div>
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
		
	<section class="panel panel-default">
			<!-- TABLE HEADER 
			<div class="row text-sm wrapper">
				<div class="col-sm-4 m-b-xs">
					<a href="<?php echo site_url().'/group/register/'; ?>" class="btn btn-sm btn-info" >Registrasi Majelis</a>
				</div>
				<div class="col-sm-3 pull-right">
					<div class="input-group">
						<input type="text" class="input-sm form-control" placeholder="Search"> <span class="input-group-btn"> <button class="btn btn-sm btn-default" type="button">Go!</button> </span> 
					</div>
				</div>
			</div>
			-->
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
						<th>Hari</th>
						<th>Jam</th>
						<th width="80px" class="text-center">Manage</th>
					  </tr>                  
					</thead> 
					<tbody>	
					<?php
						//$total_rows=$config['total_rows'];
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
					<?php foreach($group as $c):  ?>
						<tr>     
							<td align="center"><?php echo $no; ?></td>
							<td><?php echo $c->group_number; ?></td>
							<td><?php echo $c->group_name; ?></td>
							<td><?php echo $c->branch_name; ?></td>
							<td><?php echo $c->area_name; ?></td>
							<td><?php echo $c->officer_name; ?></td>
							<td><?php echo $c->group_schedule_day; ?></td>
							<td><?php echo $c->group_schedule_time; ?></td>
							<td class="text-center">
								<?php if($user_level==1 OR $user_level==3){ ?>
								<a href="<?php echo site_url()."/topsheet/ts_entry/".$c->group_id; ?>" title="Entry Topsheet"><i class="fa fa-search"></i></a>
								<?php } ?>
								<a href="<?php echo site_url()."/topsheet/ts_download/daily/".$c->group_id; ?>" title="Download Topsheet" target="_blank" ><i class="fa fa-save"></i></a>
								
							</td>
								
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
	</section>
	</div>
</section>	