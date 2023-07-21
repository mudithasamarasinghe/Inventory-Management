<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Purchase Order Details</h3>
        <div class="card-tools">
            <a href="<?php echo base_url ?>admin/index.php?page=purchase_order/manage_po"
               class="btn btn-flat btn-primary"><span class="fas fa-plus"></span> Create New PO</a>
        </div>
    </div>
    <div class="card-body">
        <div class="container-fluid">
            <div class="container-fluid">
                <table class="table table-bordered table-stripped">
                    <colgroup>
                        <col width="10%">
                        <col width="20%">
                        <col width="12%">
                        <col width="15%">
                        <col width="13%">
                        <col width="15%">
                        <col width="15%">
                    </colgroup>
                    <thead>
                    <tr class="text-center">
                        <th>#</th>
                        <th>Date/ Time Created</th>
                        <th>PO Code</th>
                        <th>Supplier Name</th>
                        <th>No of Items</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT p.*, s.name as supplier_name FROM `purchase_orders` p inner join suppliers s on p.supplier_id = s.id");
                    while ($row = $qry->fetch_assoc()):
                        $row['items'] = $conn->query("SELECT count(item_id) as `items` FROM `po_items` where po_id = '{$row['id']}' ")->fetch_assoc()['items'];
                        ?>
                        <tr class="text-center">
                            <td><?php echo $i++; ?></td>
                            <td><?php echo date("Y-m-d H:i", strtotime($row['date_created'])) ?></td>
                            <td><?php echo $row['po_code'] ?></td>
                            <td><?php echo $row['supplier_name'] ?></td>
                            <td><?php echo number_format($row['items']) ?></td>
                            <td>
                                <?php if ($row['status'] == 2): ?>
                                    <span class="badge badge-primary rounded-pill">Fully Received</span>
                                <?php elseif ($row['status'] == 1): ?>
                                    <span class="badge badge-warning rounded-pill">Partially received</span>
                                <?php elseif ($row['status'] == 0): ?>
                                    <span class="badge badge-success rounded-pill">Pending</span>
                                <?php else: ?>
                                    <span class="badge badge-danger rounded-pill">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td align="center">
                                <button type="button"
                                        class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon"
                                        data-toggle="dropdown">
                                    Action
                                </button>
                                <div class="dropdown-menu" role="menu" >
                                    <?php if ($row['status'] !=2): ?>
                                        <a class="dropdown-item"
                                           href="<?php echo base_url . 'admin/index.php?page=receiving/manage_receiving&po_id=' . $row['id'] ?>"
                                           data-id="<?php echo $row['id'] ?>"><span
                                                    class="fa fa-boxes text-dark"></span> Receive</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item"
                                           href="<?php echo base_url . 'admin/index.php?page=purchase_order/manage_po&id=' . $row['id'] ?>"
                                           data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span>
                                            Edit</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item delete_data" href="javascript:void(0)"
                                           data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span>
                                            Delete</a>
                                        <div class="dropdown-divider"></div>
                                    <?php endif; ?>
                                    <a class="dropdown-item"
                                       href="<?php echo base_url . 'admin/index.php?page=purchase_order/view_po&id=' . $row['id'] ?>"
                                       data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
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
    function delete_po($id) {
        // display a loading indicator
        start_loader();

        // AJAX allows you to send and receive data from the server without reloading the entire web page.
        // It provides a way to communicate with the server in the background, making the user experience more seamless and interactive.

        // send a request to the server to delete a purchase order.
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_po",
            method: "POST",
            data: {id: $id},
            // JSON is used as the data format in the AJAX request. JSON is a lightweight data interchange format that is easy to read and write for humans and machines.
            // It provides a simple and structured way to represent data.
            dataType: "json",
            error: err => {
                console.log(err)
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function (resp) {
                if (typeof resp == 'object' && resp.status == 'success') {
                    location.reload();
                    alert_toast("Record successfully deleted.", 'success');
                }
                else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        })
    }

    $(document).ready(function () {
        $('.delete_data').click(function () {
            _conf("Are you sure to delete this Purchase Order permanently?", "delete_po", [$(this).attr('data-id')])
        })
        $('.view_details').click(function () {
            uni_modal("Payment Details", "transaction/view_payment.php?id=" + $(this).attr('data-id'), 'mid-large')
        })
        $('.table td,.table th').addClass('py-1 px-2 align-middle')
        $('.table').dataTable();
    })
</script>
