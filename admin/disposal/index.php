<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">List of Disposal Items</h3>
        <div class="card-tools">
            <a href="<?php echo base_url ?>admin/index.php?page=disposal/manage_disposal"
               class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New</a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="10%">
                        <col width="10%">
                        <col width="15%">
                        <col width="10%">
                        <col width="10%">
                        <col width="25%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead class="text-center">
                    <tr>
                        <th>#</th>
                        <th>Disposal Id</th>
                        <th>Date/ Time Created</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Reason for Disposal</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT d.*, i.name as item_name FROM `disposal` d inner join items i on i.id = d.item_id order by d.`date_created` desc");

                    //                    fetch the current row as an associative array using fetch_assoc()
                    while ($row = $qry->fetch_assoc()):
                        $row['items'] = count(explode(',', $row['disposal_id']));
                        ?>
                        <tr class="text-center">
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['disposal_id'] ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                            <td><?php echo $row['item_name'] ?></td>
                            <td><?php echo $row['quantity'] ?></td>
                            <td><?php echo $row['disposal_reason'] ?></td>
                            <td>
                                <?php if ($row['status'] == 1): ?>
                                    <span class="badge badge-warning rounded-pill">Handled</span>
                                <?php elseif ($row['status'] == 0): ?>
                                    <span class="badge badge-success rounded-pill">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td align="center">
                                <button type="button"
                                        class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown">
                                    Action
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item"
                                       href="<?php echo base_url . 'admin/index.php?page=disposal/index&id=' . $row['id'] ?>"
                                       data-id="<?php echo $row['id'] ?>"><span class="fas fa-trash-alt"></span> Dispose</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"
                                       href="<?php echo base_url . 'admin/index.php?page=disposal/view_disposal&id=' . $row['id'] ?>"
                                       data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item"
                                       href="<?php echo base_url . 'admin/index.php?page=disposal/manage_disposal&id=' . $row['id'] ?>"
                                       data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span>
                                        Edit</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_data" href="javascript:void(0)"
                                       data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span>
                                        Delete</a>
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
    $(document).ready(function () {
        $('.delete_data').click(function () {
            _conf("Are you sure to delete this Return Record permanently?", "delete_return", [$(this).attr('data-id')])
        })
        $('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable();
    })

    function delete_return($id) {
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_return",
            method: "POST",
            data: {id: $id},
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        })
    }
</script>
