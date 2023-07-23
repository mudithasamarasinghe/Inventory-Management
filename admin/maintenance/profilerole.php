
<div class="card card-outline card-primary">
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-striped">
				<colgroup>
					<col width="5%">
					<col width="5%">
					<col width="25%">
					<col width="5%">
					<col width="25%">
					<col width="5%">

				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th class="text-nowrap">Profile id.</th>
						<th>Profile name</th>
						<th class="text-nowrap">Role Id</th>
						<th>Role Name</th>
						<th>Status</th>

					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT pr.*,ap.profile_name,r.name from `profile_role` pr inner join admin_profiles ap on pr.profileid = ap.profile_id inner join role r on pr.roleid = r.id order by pr.profileid asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class="text-center"><?php echo $row['profileid'] ?></td>
							<td><?php echo $row['profile_name'] ?></td>
							<td class="text-center"><?php echo $row['roleid'] ?></td>
							<td><?php echo $row['name'] ?></td>
							<td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-success rounded-pill">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-danger rounded-pill">Inactive</span>
                                <?php endif; ?>
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
			_conf("Are you sure to delete this profile role permanently?","delete_profilerole",[$(this).attr('data-id')])
		})
		$('#create_new').click(function(){
			uni_modal("<i class='fa fa-plus'></i> Add New profile Role","maintenance/manage_profilerole.php","mid-large")
		})
		$('.edit_data').click(function(){
			uni_modal("<i class='fa fa-edit'></i> Edit profile Role Details","maintenance/manage_profilerole.php?id="+$(this).attr('data-id'),"mid-large")
		})
		
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
	function delete_profilerole($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_profilerole",
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

