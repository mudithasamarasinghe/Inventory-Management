<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">List of Received Orders</h3>
        <!-- <div class="card-tools">
			<a href="<?php echo base_url ?>admin/index.php?page=purchase_order/manage_po" class="btn btn-flat btn-primary"><span class="fas fa-plus"></span>  Create New</a>
		</div> -->
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="5%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                        <col width="20%">
                    </colgroup>
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>Date/ Time Created</th>
                            <th>From</th>
                            <th>No of Items</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        <?php 
                        $i = 1;
                        $qry = $conn->query("SELECT * FROM `receivings` order by `date_created` desc");
                        while($row = $qry->fetch_assoc()):
                           // $row['items'] = explode(',',$row['stock_ids']);
							$row['items'] = explode(',', $result['comp_uids'] ?? '');
							//  $row['items'] = count(explode(',',$row['stock_ids']));
                            if($row['from_order'] == 1){
                                $code = $conn->query("SELECT po_code from `purchase_orders` where id='{$row['form_id']}' ")->fetch_assoc()['po_code'];
                            }else{
                                $code = $conn->query("SELECT bo_code from `back_orders` where id='{$row['form_id']}' ")->fetch_assoc()['bo_code'];
                            }
                        ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                <td><?php echo $code ?></td>
                                <td><?php echo number_format($row['items']) ?></td>
                                <td><?php echo number_format(count($row['items'])) ?></td>
                                <td align="center">
                                    <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                            Action
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                        <a class="dropdown-item" href="<?php echo base_url.'admin/index.php?page=receiving/view_receivingv&id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?php echo base_url.'admin/index.php?page=receiving/manage_receiving&id='.$row['id'] ?>" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Edit</a>
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
		$('.view_details').click(function(){
			uni_modal("Receiving Details","receiving/view_receiving.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable();
	})
</script>
