<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `role` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=stripslashes($v);
        }
    }
}
?>
<style>
    span.select2-selection.select2-selection--single {
        border-radius: 0;
        padding: 0.25rem 0.5rem;
        padding-top: 0.25rem;
        padding-right: 0.5rem;
        padding-bottom: 0.25rem;
        padding-left: 0.5rem;
        height: auto;
    }
</style>
<form action="" id="role-form">
     <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
    <div class="container-fluid">
        <div class="form-group">
            <label for="name" class="control-label">Role</label>
            <input type="text" name="name" id="name" class="form-control rounded-0" value="<?php echo isset($name) ? $name :"" ?>" required>
        </div>
		        <div class="form-group">
            <label for="pagename" class="control-label">Page</label>
            <input type="text" name="pagename" id="pagename" class="form-control rounded-0" value="<?php echo isset($pagename) ? $pagename :"" ?>" required>
        </div>
		        <div class="form-group">
            <label for="role_order" class="control-label">Seq.</label>
            <input type="text" name="role_order" id="role_order" class="form-control rounded-0" value="<?php echo isset($role_order) ? $role_order :"" ?>" required>
        </div>
		        <div class="form-group">
            <label for="aweclass" class="control-label">Awesome font</label>
            <input type="text" name="aweclass" id="aweclass" class="form-control rounded-0" value="<?php echo isset($aweclass) ? $aweclass :"" ?>" required>
        </div>

        <div class="form-group">
            <label for="status" class="control-label">Status</label>
            <select name="status" id="status" class="form-control rounded-0" required>
                <option value="1" <?php echo isset($status) && $status =="1" ? "selected": "1" ?> >Active</option>
                <option value="0" <?php echo isset($status) && $status =="0" ? "selected": "0" ?>>Inactive</option>
            </select>
        </div>
    </div>
</form>


<script>
  $(document).ready(function(){
		$('#role-form').submit(function(e){
			e.preventDefault();
            var _this = $(this)
			 $('.err-msg').remove();
			start_loader();
			$.ajax({
				url:_base_url_+"classes/Master.php?f=save_role",
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
