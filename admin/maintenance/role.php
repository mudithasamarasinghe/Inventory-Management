
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Role</h3>
		<div class="card-tools">
			<a href="javascript:void(0)" id="create_new" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-striped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="5%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
					<col width="5%">
					<col width="5%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Role</th>
						<th>Page</th>
						<th>Seq.</th>
						<th>Awesome font</th>
						<th>DB Table</th>
						<th>bg color</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT * from `role`  order by `name` asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo $row['name'] ?></td>
							<td><?php echo $row['pagename'] ?></td>
							<td><?php echo $row['role_order'] ?></td>
							<td><?php echo $row['aweclass'] ?></td>
							<td><?php echo $row['db_name'] ?></td>
							<td><?php echo $row['bg_color'] ?></td>
							<td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-success rounded-pill">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger rounded-pill">Inactive</span>
                                <?php endif; ?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
				                  </div>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Role permanently-----------?","delete_category",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Add New Role","maintenance/manage_role.php","mid-large")
		})
		$('.edit_data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Edit Item Details","maintenance/manage_role.php?id="+$(this).attr('data-id'),"mid-large")
		})
		$('.view_data').click(function(){
			uni_modal("<i class='nav-icon fas fa-tasks'></i> Role Details","maintenance/view_role.php?id="+$(this).attr('data-id'),"")
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_category($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_role",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>

