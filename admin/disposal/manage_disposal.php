<?php
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT d.*,i.name as item_name FROM items i inner join disposal d on d.item_id = i.id where d.disposal_id = '{$_GET['id']}'");
    if($qry->num_rows >0){
        foreach($qry->fetch_array() as $k => $v){
            $$k = $v;
        }
    }
}
?>

<div class="card card-outline card-primary">
    <div class="card-header">
        <h4 class="card-title"><?php echo isset($id) ? "Disposal Details - ".$disposal_id : 'Create New Disposal Request' ?></h4>
    </div>
    <div class="card-body">
        <form action="" id="disposal-form">
            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <label class="control-label text-info">Disposal Id</label>
                        <input type="text" class="form-control form-control-sm rounded-0" value="<?php echo isset($disposal_id) ? $disposal_id : '' ?>" readonly>
                    </div>
                </div>
                <hr>
                <fieldset>
                    <legend class="text-info">Item Form</legend>
                    <div class="row justify-content-center align-items-end">
                        <?php
                        $item_arr = array();
                        $item = $conn->query("SELECT * FROM `items` where status = 1 order by `name` asc");
                        while($row=$item->fetch_assoc()):
                            $item_arr[$row['id']] = $row;
//                            $cost_arr[$row['id']] = $row['cost'];
                        endwhile;
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="item_id" class="control-label">Item</label>
                                <select  id="item_id" class="custom-select ">
                                    <?php foreach ($item_arr as $item_id => $item) : ?>
                                        <option value="<?php echo $item_id; ?>"><?php echo $item['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="unit" class="control-label">Unit</label>
                                <input type="text" class="form-control rounded-0" id="unit">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="qty" class="control-label">Qty</label>
                                <input type="number" step="any" class="form-control rounded-0" id="qty">
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
                        <col width="10%">
                        <col width="30%">
                        <col width="30%">
                        <col width="30%">
                    </colgroup>
                    <thead>
                    <tr class="text-light bg-navy">
                        <th class="text-center py-1 px-2"></th>
                        <th class="text-center py-1 px-2">Disposal Code</th>
                        <th class="text-center py-1 px-2">Disposal Code</th>
                        <th class="text-center py-1 px-2">Item</th>
                        <th class="text-center py-1 px-2">Unit</th>
                        <th class="text-center py-1 px-2">Qty</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
//                    $total = 0;
                    if(isset($id)):
                        $qry = $conn->query("SELECT d.*,i.name,i.description FROM `po_items` p inner join items i on p.item_id = i.id where p.po_id = '{$id}'");
                        while($row = $qry->fetch_assoc()):
                            $total += $row['total']
                            ?>
                            <tr>
                                <td class="py-1 px-2 text-center">
                                    <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
                                </td>
                                <td class="py-1 px-2 text-center qty">
                                    <span class="visible"><?php echo number_format($row['quantity']); ?></span>
                                    <input type="hidden" name="disposal_code[]" value="<?php echo $row['disposal_code']; ?>">
                                    <input type="hidden" name="item_id[]" value="<?php echo $row['item_id']; ?>">
                                    <input type="hidden" name="unit[]" value="<?php echo $row['unit']; ?>">
                                    <input type="hidden" name="qty[]" value="<?php echo $row['quantity']; ?>">
                                </td>
                                <td class="py-1 px-2 text-center unit">
                                    <?php echo $row['unit']; ?>
                                </td>
                                <td class="py-1 px-2 item">
                                    <?php echo $row['name']; ?> <br>
                                    <?php echo $row['description']; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="remarks" class="text-info control-label">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3" class="form-control rounded-0"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card-footer py-1 text-center">
        <button class="btn btn-flat btn-primary" type="submit" form="disposal-form">Save</button>
        <a class="btn btn-flat btn-dark" href="<?php echo base_url.'/admin/index.php?page=disposal' ?>">Cancel</a>
    </div>
</div>
<table id="clone_list" class="d-none">
    <tr>
        <td class="py-1 px-2 text-center">
            <button class="btn btn-outline-danger btn-sm rem_row" type="button"><i class="fa fa-times"></i></button>
        </td>
        <td class="py-1 px-2 item">
        </td>
        <td class="py-1 px-2 text-center unit">
        </td>
        <td class="py-1 px-2 text-center qty">
            <span class="visible"></span>
            <input type="hidden" name="item_id[]">
            <input type="hidden" name="unit[]">
            <input type="hidden" name="qty[]">
        </td>
    </tr>
</table>
<script>
    var items = $.parseJSON('<?php echo json_encode($item_arr) ?>')

    $(function(){
        $('#item_id').select2({
            placeholder:"Please select item first",
            width:'resolve',
        })

        $('#add_to_list').click(function(){
            var item = $('#item_id').val();
            // alert(JSON.stringify(items));
            var qty = $('#qty').val() > 0 ? $('#qty').val() : 0;
            var unit = $('#unit').val()
            var item_name = items[item].name || 'N/A';
            var item_description = items[item].description || 'N/A';
            var tr = $('#clone_list tr').clone()
            if(item == '' || qty == '' || unit == '' ){
                alert_toast('Form Item textfields are required.','warning');
                return false;
            }
            if($('table#list tbody').find('tr[data-id="'+item+'"]').length > 0){
                alert_toast('Item is already exists on the list.','error');
                return false;
            }
            tr.find('[name="item_id[]"]').val(item)
            tr.find('[name="unit[]"]').val(unit)
            tr.find('[name="qty[]"]').val(qty)
            tr.attr('data-id',item)
            tr.find('.qty .visible').text(qty)
            tr.find('.unit').text(unit)
            tr.find('.item').html(item_name+'<br/>'+item_description)
            $('table#list tbody').append(tr)
            // calc()
            $('#item_id').val('').trigger('change')
            $('#qty').val('')
            $('#unit').val('')
            tr.find('.rem_row').click(function(){
                rem($(this))
            })
        })
        $('#disposal-form').submit(function(e){
            e.preventDefault();
            var _this = $(this)
            $('.err-msg').remove();
            start_loader();
            $.ajax({
                url:_base_url_+"classes/Master.php?f=save_disposal",
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
                        location.replace(_base_url_+"admin/index.php?page=disposal/view_disposal&id="+resp.id);
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

            $('table#list tbody tr .rem_row').click(function(){
                rem($(this))
            })
    })
    function rem(_this){
        _this.closest('tr').remove()
        if($('table#list tbody tr').length <= 0)
            $('#item_id').removeAttr('readonly')

    }
</script>
