<?php $user_level = $this->session->userdata('user_level'); ?>

<section class="main">
	<div class="container">
	
	<div class="row text-sm wrapper">
		<!-- SEARCH FORM -->
		<div id="module_title" class="col-sm-4 m-b-xs">
				<h3 class="m-b-none"><?php echo $menu_title; ?></h3>
		</div>
		<!--<?php if($user_level==1 OR $user_level==3){ ?>
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
		<?php } ?>-->	
	</div>
	<?php if($this->session->flashdata('message')){ ?>
			<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
	<?php } ?>
	<div class="row">
		<div class="col-lg-6">		
			<section class="panel panel-default">
				<header class="panel-heading"><b>SENIN</b></header>
				<div class="table-responsive">					
					<table class="table table-striped m-b-none text-sm">              
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th>Majelis</th>
							<th>Pendamping</th>
							<th>Jam</th>
							<th><i class='fa fa-check '></i></th>
							<th><i class='fa fa-bookmark-o '></i></th>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php 
							$date = date("Y-m-d"); 
			
							function week_range($date) {
								$ts = strtotime($date);
								$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
								return array(date('Y-m-d', $start),
											 date('Y-m-d', strtotime('next saturday', $start)));
							}
							list($date_start, $date_end) = week_range($date); 
						?>
						<?php $no=1; foreach($group_senin as $c):  ?>
						<?php $check = $this->tsdaily_model->check_entry_topsheet($c->group_id, $date_start, $date_end); ?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>
								<td><?php echo $c->group_name; ?></td>
								<td><?php echo $c->officer_name; ?></td>
								<td><?php echo $c->group_schedule_time; ?></td>
								<td><?php if($check>0){ echo "<i class='fa fa-check text-primary'></i>";} ?></td>
								<td>
									<?php if($check>0){?>										
										<a href="<?php echo site_url()."/topsheet/download/".$c->group_id; ?>" title="Download Topsheet" target="_blank" ><i class="fa fa-save"></i></a>
									<?php }else{ ?>
										<?php if($user_level==1 OR $user_level==3){ ?>
										<a href="<?php echo site_url()."/topsheet/ts_entry/".$c->group_id; ?>" title="Entry Topsheet"><i class="fa fa-search"></i></a>
										<?php } ?>
									
									<?php } ?>
								</td>
								
							</tr>
						<?php $no++; endforeach; ?>
						</tbody>	
					</table>  
				</div>				
			</section>
		</div>
		
		<div class="col-lg-6">		
			<section class="panel panel-default">
				<header class="panel-heading"><b>SELASA</b></header>
				<div class="table-responsive">					
					<table class="table table-striped m-b-none text-sm">              
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th>Majelis</th>
							<th>Pendamping</th>
							<th>Jam</th>
							<th><i class='fa fa-check '></i></th>
							<th><i class='fa fa-bookmark-o '></i></th>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php $no=1; foreach($group_selasa as $c):  ?>
						<?php $check = $this->tsdaily_model->check_entry_topsheet($c->group_id, $date_start, $date_end); ?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>
								<td><?php echo $c->group_name; ?></td>
								<td><?php echo $c->officer_name; ?></td>
								<td><?php echo $c->group_schedule_time; ?></td>
								<td><?php if($check>0){ echo "<i class='fa fa-check text-primary'></i>";} ?></td>
								<td>
									<?php if($check>0){?>										
										<a href="<?php echo site_url()."/topsheet/download/".$c->group_id; ?>" title="Download Topsheet" target="_blank" ><i class="fa fa-save"></i></a>
									<?php }else{ ?>
										<?php if($user_level==1 OR $user_level==3){ ?>
										<a href="<?php echo site_url()."/topsheet/ts_entry/".$c->group_id; ?>" title="Entry Topsheet"><i class="fa fa-search"></i></a>
										<?php } ?>
									
									<?php } ?>
								</td>
							</tr>
						<?php $no++; endforeach; ?>
						</tbody>	
					</table>  
				</div>				
			</section>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6">		
			<section class="panel panel-default">
				<header class="panel-heading"><b>RABU</b></header>
				<div class="table-responsive">					
					<table class="table table-striped m-b-none text-sm">              
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th>Majelis</th>
							<th>Pendamping</th>
							<th>Jam</th>
							<th><i class='fa fa-check '></i></th>
							<th><i class='fa fa-bookmark-o '></i></th>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php $no=1; foreach($group_rabu as $c):  ?>
						<?php $check = $this->tsdaily_model->check_entry_topsheet($c->group_id, $date_start, $date_end); ?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>
								<td><?php echo $c->group_name; ?></td>
								<td><?php echo $c->officer_name; ?></td>
								<td><?php echo $c->group_schedule_time; ?></td>
								<td><?php if($check>0){ echo "<i class='fa fa-check text-primary'></i>";} ?></td>
								<td>
									<?php if($check>0){?>										
										<a href="<?php echo site_url()."/topsheet/download/".$c->group_id; ?>" title="Download Topsheet" target="_blank" ><i class="fa fa-save"></i></a>
									<?php }else{ ?>
										<?php if($user_level==1 OR $user_level==3){ ?>
										<a href="<?php echo site_url()."/topsheet/ts_entry/".$c->group_id; ?>" title="Entry Topsheet"><i class="fa fa-search"></i></a>
										<?php } ?>
									
									<?php } ?>
								</td>
							</tr>
						<?php $no++; endforeach; ?>
						</tbody>	
					</table>  
				</div>				
			</section>
		</div>
		
		<div class="col-lg-6">		
			<section class="panel panel-default">
				<header class="panel-heading"><b>KAMIS</b></header>
				<div class="table-responsive">					
					<table class="table table-striped m-b-none text-sm">              
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th>Majelis</th>
							<th>Pendamping</th>
							<th>Jam</th>
							<th><i class='fa fa-check '></i></th>
							<th><i class='fa fa-bookmark-o '></i></th>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php $no=1; foreach($group_kamis as $c):  ?>
						<?php $check = $this->tsdaily_model->check_entry_topsheet($c->group_id, $date_start, $date_end); ?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>
								<td><?php echo $c->group_name; ?></td>
								<td><?php echo $c->officer_name; ?></td>
								<td><?php echo $c->group_schedule_time; ?></td>
								<td><?php if($check>0){ echo "<i class='fa fa-check text-primary'></i>";} ?></td>
								<td>
									<?php if($check>0){?>										
										<a href="<?php echo site_url()."/topsheet/download/".$c->group_id; ?>" title="Download Topsheet" target="_blank" ><i class="fa fa-save"></i></a>
									<?php }else{ ?>
										<?php if($user_level==1 OR $user_level==3){ ?>
										<a href="<?php echo site_url()."/topsheet/ts_entry/".$c->group_id; ?>" title="Entry Topsheet"><i class="fa fa-search"></i></a>
										<?php } ?>
									
									<?php } ?>
								</td>
							</tr>
						<?php $no++; endforeach; ?>
						</tbody>	
					</table>  
				</div>				
			</section>
		</div>
	</div>
	</div>
</section>	