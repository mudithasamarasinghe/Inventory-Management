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
$id = $_GET['id'];
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title"><?php echo isset($id) ? "BOM Details" : 'Create New Purchase Order' ?></h4>
    </div>
    <div class="card-body">
        <form action="" id="po-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">BOM Id.</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($bom_id) ? $bom_id : '' ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bom_device_name" class="control-label text-info">BOM device name</label>
  <input type="text" class="form-control form-control-sm rounded-0" id="bom_device_name" value="<?php echo isset($bom_device_name) ? $bom_device_name : '' ?>" readonly>
                        </div>
                    </div>
                </div>
                <hr>
                <fieldset>
                    <legend class="text-info">Item Form</legend>
                    <div class="row justify-content-center align-items-end">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bomi_item_name" class="control-label">Item</label>
  								<input type="text" class="form-control rounded-0" name="bomi_item_name" id="bomi_item_name">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bomi_remark" class="control-label">Remark</label>
                                <input type="text" class="form-control rounded-0" id="bomi_remark">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bom_quantity" class="control-label">Qty</label>
                                <input type="number" step="any" class="form-control rounded-0" id="bom_quantity">
                            </div>
                        </div>
									                      <div class="col-md-3">
                            <div class="form-group">
                                <label for="status" class="control-label">Status</label>
                               <select name="status" id="status" class="custom-select selevt">
			<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
			<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
			</select>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-flat btn-sm btn-primary" id="add_to_list">Add to List</button>
                            </div>
                        </div>
                </fieldset>
                <hr>
                <table class="table table-striped table-bordered" id="list">
                    <colgroup>
                        <col width="5%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
                    </colgroup>
                    <thead>
                        <tr class="text-light bg-navy">
                          <th></th>
                            <th class="text-center py-1 px-2">Item</th>
                            <th class="text-center py-1 px-2">Remark</th>
                            <th class="text-center py-1 px-2">Qty</th>
                            <th class="text-center py-1 px-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        if(isset($id)):
                     $qry = $conn->query("SELECT p.*,s.bomi_item_name,s.bomi_remark,s.bom_quantity,s.status FROM bill_of_materials p inner join bill_of_materials_items s on p.bom_id = s.bom_id  where p.bom_id = '{$_GET['id']}'");
						   while($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td class="py-1 px-2 text-center">
                                <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
                            </td>
                            <td class="py-1 px-2 text-left bomi_item_name">
                                <span class="visible"><?php echo $row['bomi_item_name']; ?></span>
                                <input type="hidden" name="bomi_item_name[]" value="<?php echo $row['bomi_item_name']; ?>">
                                <input type="hidden" name="bomi_remark[]" value="<?php echo $row['bomi_remark']; ?>">
                                <input type="hidden" name="bom_quantity[]" value="<?php echo $row['bom_quantity']; ?>">
                                <input type="hidden" name="status[]" value="<?php echo $row['status']; ?>">
                                                    </td>
                
                            <td class="py-1 px-2 text-left bomi_remark">
                            <?php echo $row['bomi_remark']; ?>
                            </td>
                            <td class="py-1 px-2 text-right bom_quantity">
                            <?php echo number_format($row['bom_quantity']); ?>
                            </td>
							  <td class="py-1 px-2 text-right status">
                            <?php echo $row['status']; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php endif; ?>
                    </tbody>

                </table>

            </div>
        </form>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-primary" type="submit" form="po-form">Save</button>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=bill_of_materials' ?>">Cancel</a>
    </div>
</div>
<table id="clone_list" class="d-none">
    <tr>
        <td class="py-1 px-2 text-center">
            <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="py-1 px-2 text-left bomi_item_name">
            <span class="visible"></span>
            <input type="hidden" name="bomi_item_name[]">
            <input type="hidden" name="bomi_remark[]">
            <input type="hidden" name="bom_quantity[]">
            <input type="hidden" name="status[]"></td>
     
        <td class="py-1 px-2 bomi_remark"></td>
        <td class="py-1 px-2 text-right bom_quantity"></td>
        <td class="py-1 px-2 text-right status"></td>
 
    </tr>
</table>
<script>
    
    $(function(){
          $('#add_to_list').click(function(){
           var bomi_item_name = $('#bomi_item_name').val()
	       var bomi_remark = $('#bomi_remark').val()
            var bom_quantity = $('#bom_quantity').val() > 0 ? $('#bom_quantity').val() : 0;
            var status = $('#status').val()
            // console.log(supplier,item)
           var tr = $('#clone_list tr').clone()
            if(bomi_item_name == '' || bomi_remark == '' || bom_quantity == '' ){
                alert_toast('Form Item textfields are required.','warning');
                return false;
            }
            if($('table#list tbody').find('tr[data-id="'+bomi_item_name+'"]').length > 0){
	            alert_toast('Item is already exists on the list.','error');
                return false;
            }
            tr.find('[name="bomi_item_name[]"]').val(bomi_item_name)
            tr.find('[name="bomi_remark[]"]').val(bomi_remark)
            tr.find('[name="bom_quantity[]"]').val(bom_quantity)
            tr.find('[name="status[]"]').val(status)
			
			tr.attr('data-id',bomi_item_name)
            tr.find('.bomi_item_name .visible').text(bomi_item_name)
            tr.find('.bomi_remark').text(bomi_remark)
            tr.find('.bom_quantity').text(bom_quantity)
			tr.find('.status').text(status)
            $('table#list tbody').append(tr)
  
            tr.find('.rem_row').click(function(){
                rem($(this))
            })
      
        })
        $('#po-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_bom_item",
				data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
				error:err=>{
					console.log(err)
					alert_toast("An error occured",'error');
					end_loader();
				},
				success:function(resp){
					if(resp.status == 'success'){
						location.replace(_base_url_+"admin/index.php?page=bill_of_materials/view_bom&id="+resp.id);
					}else if(resp.status == 'failed' && !!resp.msg){
                        var el = $('<div>')
                            el.addClass("alert alert-danger err-msg").text(resp.msg)
                            _this.prepend(el)
                            el.show('slow')
                            end_loader()
                    }else{
						alert_toast("An error occured",'error');
						end_loader();
                        console.log(resp)
					}
                    $('html,body').animate({scrollTop:0},'fast')
				}
			})
		})

        if('<?php echo isset($id) && $id > 0 ?>' == 1){
            calc()
            $('#supplier_id').trigger('change')
            $('#supplier_id').attr('readonly','readonly')
            $('table#list tbody tr .rem_row').click(function(){
                rem($(this))
            })
        }
    })
    function rem(_this){
        _this.closest('tr').remove()
        calc()
        if($('table#list tbody tr').length <= 0)
            $('#supplier_id').removeAttr('readonly')

    }
    function calc(){
        var sub_total = 0;
        var grand_total = 0;
        var discount = 0;
        var tax = 0;
        $('table#list tbody input[name="total[]"]').each(function(){
            sub_total += parseFloat($(this).val())
            
        })
        $('table#list tfoot .sub-total').text(parseFloat(sub_total).toLocaleString('en-US',{style:'decimal',maximumFractionDigit:2}))
        var discount =   sub_total * (parseFloat($('[name="discount_perc"]').val()) /100)
        sub_total = sub_total - discount;
        var tax =   sub_total * (parseFloat($('[name="tax_perc"]').val()) /100)
        grand_total = sub_total + tax
        $('.discount').text(parseFloat(discount).toLocaleString('en-US',{style:'decimal',maximumFractionDigit:2}))
        $('[name="discount"]').val(parseFloat(discount))
        $('.tax').text(parseFloat(tax).toLocaleString('en-US',{style:'decimal',maximumFractionDigit:2}))
        $('[name="tax"]').val(parseFloat(tax))
        $('table#list tfoot .grand-total').text(parseFloat(grand_total).toLocaleString('en-US',{style:'decimal',maximumFractionDigit:2}))
        $('[name="amount"]').val(parseFloat(grand_total))

    }
</script>