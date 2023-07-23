<?php
$qry = $conn->query("SELECT * FROM receivings where id = '{$_GET['id']}'");

if($qry->num_rows >0){
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
    if($from_order == 1){
        $po_qry = $conn->query("SELECT p.*,s.name as supplier FROM `purchase_orders` p inner join `suppliers` s on p.supplier_id = s.id where p.id= '{$form_id}' ");

        if($po_qry->num_rows >0){
            foreach($po_qry->fetch_array() as $k => $v){
                if(!isset($$k))
                $$k = $v;
            }
        }
    }else{
        $qry = $conn->query("SELECT b.*,s.name as supplier,p.po_code FROM back_orders b inner join suppliers s on b.supplier_id = s.id inner join purchase_orders p on b.po_id = p.id  where b.id = '{$form_id}'");

        if($qry->num_rows >0){
                foreach($qry->fetch_array() as $k => $v){
                    if($k == 'id')
                    $k = 'bo_id';
                    if(!isset($$k))
                    $$k = $v;
                }
            }
    }
}
?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">Received Order Details - <?php echo $po_code ?></h4>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label text-info">FROM P.O. Code</label>
                    <div><?php echo isset($po_code) ? $po_code : '' ?></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_id" class="control-label text-info">Supplier</label>
                        <div><?php echo isset($supplier) ? $supplier : '' ?></div>
                    </div>
                </div>
                <?php if(isset($bo_id)): ?>
                <div class="col-md-6">
                    <label class="control-label text-info">FROM B.O. Code</label>
                    <div><?php echo isset($bo_code) ? $bo_code : '' ?></div>
                </div>
                <?php endif; ?>
            </div>
            <h4 class="text-info">Orders</h4>
            <table class="table table-striped table-bordered" id="list">
                <colgroup>
                    <col width="10%">
                    <col width="10%">
                    <col width="30%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <thead>
                    <tr class="text-light bg-navy">
                        <th class="text-center py-1 px-2">Qty</th>
                        <th class="text-center py-1 px-2">Unit</th>
                        <th class="text-center py-1 px-2">Item</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    echo("SELECT s.*,i.name,i.description FROM `stock_list` s inner join items i on s.item_id = i.id where s.id in '{$stock_ids}'");
                    $qry = $conn->query("SELECT s.*,i.name,i.description FROM `stock_list` s inner join items i on s.item_id = i.id where s.id in '{$stock_ids}'");

					//   $qry = $conn->query("SELECT b.*,s.name as supplier,p.po_code FROM back_order_list b inner join suppliers s on b.supplier_id = s.id inner join purchase_order_list p on b.po_id = p.id  where b.id = '{$form_id}'");

                    while($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <td class="py-1 px-2 text-center"><?php echo number_format($row['quantity'],2) ?></td>
                        <td class="py-1 px-2 text-center"><?php echo ($row['unit']) ?></td>
                        <td class="py-1 px-2">
                            <?php echo $row['name'] ?> <br>
                            <?php echo $row['description'] ?>
                        </td>
                    </tr>

                    <?php endwhile; ?>

                </tbody>
                </table>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="remarks" class="text-info control-label">Remarks</label>
                        <p><?php echo isset($remarks) ? $remarks : '' ?></p>
                    </div>
                </div>
                <?php if($status > 0): ?>
                <div class="col-md-6">
                    <span class="text-info"><?php echo ($status == 2)? "RECEIVED" : "PARTIALLY RECEIVED" ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-success" type="button" id="print">Print</button>
        <a class="btn btn-flat btn-primary" href="<?php echo base_url.'/admin/index.php?page=receiving/manage_receiving&id='.(isset($id) ? $id : '') ?>">Edit</a>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=receiving' ?>">Back To List</a>
    </div>
</div>
<table id="clone_list" class="d-none">
    <tr>
        <td class="py-1 px-2 text-center">
            <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="py-1 px-2 text-center qty">
            <span class="visible"></span>
            <input type="hidden" name="item_id[]">
            <input type="hidden" name="unit[]">
            <input type="hidden" name="qty[]">
        </td>
        <td class="py-1 px-2 text-center unit">
        </td>
        <td class="py-1 px-2 item">
        </td>
    </tr>
</table>
<script>

    $(function(){
        $('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Received Order Details - Print View")
            var p = $('#print_out').clone()
            p.find('tr.text-light').removeClass("text-light bg-navy")
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">'+
                      '<div class="col-1 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />'+
                      '</div>'+
                      '<div class="col-10">'+
                      '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>'+
                      '<h4 class="text-center">Received Order</h4>'+
                      '</div>'+
                      '<div class="col-1 text-right">'+
                      '</div>'+
                      '</div><hr/>')
            _el.append(p.html())
            var nw = window.open("","","width=1200,height=900,left=250,location=no,titlebar=yes")
                     nw.document.write(_el.html())
                     nw.document.close()
                     setTimeout(() => {
                         nw.print()
                         setTimeout(() => {
                            nw.close()
                            end_loader()
                         }, 200);
                     }, 500);
        })
    })
</script>
