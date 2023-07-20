<?php
require_once('../../config.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
$id=$_GET['id'];
$qry = $conn->query("SELECT * FROM `admin_profiles` where  profile_id = '{$_GET['id']}' ");
 if($qry->num_rows > 0){
while($row = $qry->fetch_assoc()):
 echo $row['profile_name'];
 endwhile;
 }
}
?>
<div class="container-fluid">
<form method="post" action="../classes/Master.php?f=save_rtop" id=myForm>
		<input type="hidden" name ="id" value="<?php echo isset($id) ? $id : '' ?>">
<div class="container-fluid" id="print_out">
    <div id='transaction-printable-details' class='position-relative'>
<table class="table">
	
<?php 
					$i = 1;
					$j = 1;
					$k = 0;
						$qry = $conn->query("SELECT * from `role`  order by `maintenance`,`name` asc ");
						while($row = $qry->fetch_assoc()):
						
						
						
						
						
						
						if ($row['maintenance']==1 && $k == 0 ) {
							echo "<tr><td colspan=\"4\"><strong>Maintenance</strong></td></tr>";
							$i = 1;
					        $j = 1;
							$k=1;
						}
						
						if($j == 0) { echo '<tr>'; }


 $sql = "SELECT *  FROM profile_role WHERE  profileid ='".$_GET['id']."'"." and roleid =".$row['id'];
            $resultInner = $conn->query($sql);

            if ($resultInner->num_rows > 0) 
            {

                    echo "<td nowrap><input  type=\"checkbox\" value=\"".$row['id']."\" name=\"myCheckboxes[]\" id=\"myCheckboxes\" checked><label class=\"form-check-label\" for=\"flexCheckChecked\">&nbsp;&nbsp;".$row['name']."</label></td>";

            }
			else
			{
			        echo "<td nowrap><input  type=\"checkbox\" value=\"".$row['id']."\"  name=\"myCheckboxes[]\" id=\"myCheckboxes\" ><label class=\"form-check-label\" for=\"flexCheckChecked\">&nbsp;&nbsp;".$row['name']."</label></td>";

			}





						
					
						
						if($i % 4 == 0) { echo '</tr>'; $j=0;}
                        $i++;
						$j++;
						?>
						
					<?php endwhile; ?>
	</form>

</div>


