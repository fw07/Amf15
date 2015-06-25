<section class="main">
	<div class="container">
			<div id="module_title"><h1><?php echo $menu_title; ?></h1></div>
		<div class="panel panel-default">
			
			
			<div class="panel-body">
				<div class="table-responsive">	
					<?php if($this->session->flashdata('message')){ ?>
						<div class="alert alert-success"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button> <?php echo print_message($this->session->flashdata('message')); ?></div>
					<?php } ?>
					<table class="table table-striped m-b-none tbl" data-ride="">                
						<thead>                  
						  <tr>
							<th width="30px">No</th>
							<th width="150px">Account Number</th>
							<th>Client Name</th>
							<th>Group</th>
							<th>Branch</th>
							<th width="80px">Manage</th>
						  </tr>                  
						</thead> 
						<tbody>	
						<?php $no=1; ?>
						<?php foreach($clients as $c):  ?>
							<tr>     
								<td align="center"><?php echo $no; ?></td>					              
								<td><?php echo "100".$c->client_group."-00".$c->client_id; ?></td>
								<td><?php echo $c->client_firstname." ".$c->client_lastname; ?></td>
								<td><a href="<?php echo site_url()."/group/view/".$c->client_group; ?>" title="View This Group"><?php echo $c->group_name; ?></a></td>
								<td><a href="<?php echo site_url()."/branch/view/".$c->client_branch; ?>" title="View This Branch"><?php echo $c->branch_name; ?></a></td>
								<td>
									<a href="<?php echo site_url()."/clients/view/".$c->client_id; ?>" title="View"><span class="glyphicon glyphicon-file"></span></a>
									<a href="<?php echo site_url()."/clients/edit/".$c->client_id; ?>" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
									<a href="<?php echo site_url()."/clients/delete/".$c->client_id; ?>" title="Delete" onclick="return confirmDialog();" ><span class="glyphicon glyphicon-trash"></span></a></td>
							</tr>
						<?php $no++; endforeach; ?>
						</tbody>	
					</table>  
				</div>
			</div>
		</div>
	</div>
</div>	