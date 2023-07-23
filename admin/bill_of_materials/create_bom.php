<?php require_once('./../../config.php') ?>

   <style>
    #uni_modal .modal-footer{
        display:none;
    }
</style> 
<form action="" id="bom-form">
<div class="container-fluid" id="print_out">
    <div id='transaction-printable-details' class='position-relative'>
        <div class="row">
            <fieldset class="w-100">
                <div class="col-12">
                    
                    <dl>
                        <dt class="text-info">BOM Name:</dt>
                        <dd class="pl-3">
						<input type="text" name="bom_device_name" id="bom_device_name" class="form-control rounded-0" value="<?php echo isset($name) ? $name : ''; ?>"></dd>
						 <dt class="text-info">BOM Type:</dt>
                        <dd class="pl-3">
						<input type="text" name="bom_type" id="bom_type" class="form-control rounded-0" value=""></dd>
					


						<dt class="text-info">Remark:</dt>
                        <dd class="pl-3"><textarea name="bom_remark" id="remarks" rows="3" class="form-control rounded-0"></textarea>
						</dd>
                        <dt class="text-info">Status:</dt>
                        <dd class="pl-3">
						<div class="form-group">
			<select name="status" id="status" class="custom-select selevt">
			<option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Active</option>
			<option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>Inactive</option>
			</select>
		</div>
                        </dd>
                    </dl>
                </div>
            </fieldset>
        </div>
    </div>
</div>
</form>
<div class="form-group">
    <div class="col-12">
        <div class="card-tools">
           <button type="button" class="btn btn-primary" id="submit" onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>
    

<script>
    $(function(){
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
    })
	$(document).ready(function(){
    	$('#bom-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_bom",
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
					if(typeof resp =='object' && resp.status == 'success'){
						location.reload();
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
				}
			})
		})
	})
</script>