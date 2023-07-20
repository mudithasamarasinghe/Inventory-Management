<?php 
if(isset($_GET['id'])){
   $qry = $conn->query("SELECT p.*,s.bomi_item_name FROM bill_of_materials p inner join bill_of_materials_items s on p.bom_id = s.bom_id  where p.bom_id = '{$_GET['id']}'");
 if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
            $$k = $v;
        }
    }
	
	  $qry = $conn->query("SELECT p.* FROM bill_of_materials p where p.bom_id = '{$_GET['id']}'");
if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
            $$k = $v;
        }
    }
}
?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title">BOM Item Detail - <?php echo $bom_id ?></h4>
    </div>
    <div class="card-body" id="print_out">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <label class="control-label text-info">BOM Id.</label>
                    <div><?php echo isset($bom_id) ? $bom_id : '' ?></div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="supplier_id" class="control-label text-info">BOM Device</label>
                        <div><?php echo isset($bom_device_name) ? $bom_device_name : '' ?></div>
                    </div>
                </div>
            </div>
            <h4 class="text-info">BOM Item Detail </h4>
            <table class="table table-striped table-bordered" id="list">
                <colgroup>
				<col width="5%">
                    <col width="10%">
                    <col width="30%">
                    <col width="10%">
					<col width="5%">
                    <col width="25%">
                    <col width="25%">
                </colgroup>
                <thead>
                    <tr class="text-light bg-navy">
					       <th>#</th>
                           <th nowrap>BOM item Id.</th>
                            <th>BOM Item Name</th>
							<th>Qty.</th>
							 <th >Status</th>
							 <th>Date Created</th>
                           
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $i = 1;
                    $qry = $conn->query("SELECT p.*,s.bomi_item_name,s.bomi_id,s.bom_quantity,s.date_created FROM bill_of_materials p inner join bill_of_materials_items s on p.bom_id = s.bom_id  where p.bom_id = '{$bom_id}'");
                	   while($row = $qry->fetch_assoc()):
                       
                    ?>
                      <tr><td class="text-center"><?php echo $i++; ?></td>
                        <td class="py-1 px-2 text-center"><?php echo $row['bomi_id'] ?></td>
                        <td class="py-1 px-2 text-left"><?php echo ($row['bomi_item_name']) ?></td>
                        <td class="py-1 px-2"><?php echo $row['bom_quantity']; ?> </td>
						
                       <td class="text-center">
                                    <?php if($row['status'] == 0): ?>
                                        <span class="badge badge-danger rounded-pill">Inactive</span>
                                     <?php else: ?>
                                        <span class="badge badge-primary rounded-pill">Active</span>
                                    <?php endif; ?>
                                </td>
								<td class="py-1 px-2"><?php echo $row['date_created']; ?> </td>
                    </tr>

                    <?php endwhile; ?>
                    
                </tbody>
            
            </table>
    
        </div>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-success" type="button" id="print">Print</button>
        <a class="btn btn-flat btn-primary" href="<?php echo base_url.'/admin/index.php?page=bill_of_materials/manage_bom&id='.(isset($id) ? $id : '') ?>">Edit</a>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=bill_of_materials' ?>">Back To List</a>
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
            <input type="hidden" name="price[]">
            <input type="hidden" name="total[]">
        </td>
        <td class="py-1 px-2 text-center unit">
        </td>
        <td class="py-1 px-2 item">
        </td>
        <td class="py-1 px-2 text-right cost">
        </td>
        <td class="py-1 px-2 text-right total">
        </td>
    </tr>
</table>
<script>
    
    $(function(){
        $('#print').click(function(){
            start_loader()
            var _el = $('<div>')
            var _head = $('head').clone()
                _head.find('title').text("Purchase Order Details - Print View")
            var p = $('#print_out').clone()
            p.find('tr.text-light').removeClass("text-light bg-navy")
            _el.append(_head)
            _el.append('<div class="d-flex justify-content-center">'+
                      '<div class="col-1 text-right">'+
                      '<img src="<?php echo validate_image($_settings->info('logo')) ?>" width="65px" height="65px" />'+
                      '</div>'+
                      '<div class="col-10">'+
                      '<h4 class="text-center"><?php echo $_settings->info('name') ?></h4>'+
                      '<h4 class="text-center">Purchase Order</h4>'+
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