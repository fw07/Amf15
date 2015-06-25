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
				</div>
				<form action="" method="post"> 
				<div class="col-sm-3 pull-right">
					<div class="input-group">
						
						<input type="text" name="qw" class="input-sm form-control" placeholder="Search"> <span class="input-group-btn">
						<button class="btn btn-sm btn-default" type="submit">Go!</button> </span> 
						
					</div>
				</div>
				</form>
			</div>
			
			<div class="table-responsive">  
				
				<table class="table table-striped m-b-none text-sm">              
					<thead >
					<tr>
						<th width="15px">No</th>
						<th>Nomor Rekening</th>
						<th>Nama Lengkap</th>
						<th>Majelis</th>
						<th>Cabang</th>
						<th>Tanggal Keluar</th>
						<th width="120px">Status</th>
					 </tr>
                </thead>
				<tbody>
				<?php 
				$no=1;
				foreach($clients as $row){ ?>
                
                <tr>
                   <td align="center" ><?php echo $no; ?></td>
                    <td><?php echo $row->client_account; ?></td>
                    <td><?php echo $row->client_fullname; ?></td>
                    <td><?php echo $row->group_name; ?></td>
                    <td><?php echo $row->branch_name; ?></td>
                    <td><?php echo $row->client_unreg_date; ?></td>
                    <td>
					<form name="" action="<?php echo site_url();?>/clients_unreg/update_status/<?php echo $row->client_id; ?> " method="post" >
					<select name="status" onChange='this.form.submit()'>
							  <option value="<?php echo $row->client_status; ?>" class="input-sm form-control"><?php 
									if($row->client_status==0){ echo "Keluar"; }
									else{ echo "Aktif"; }
								?></option>
							  <option value="1">Aktif</option>
							  <option value="0">Keluar</option>
							  
						</select>
					<!--<button class="btn btn-success" type="submit">Update</button>-->
					</form>
						</td>
                </tr>
				<?php 
				$no++;
                } 
				
                ?></tbody>
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