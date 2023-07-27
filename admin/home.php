 <style>
 .badge {
     border-radius: 0;
     font-size: 12px;
     line-height: 1;
     padding: .375rem .5625rem;
     font-weight: normal
 }
 
 .badge-outline-primary {
    color: #405189;
    border: 1px solid #405189;
}

.badge.badge-pill {
    border-radius: 50%;
    font-size: 1.1em;padding: 10px;
	align-items: center;
  text-align: center;
  vertical-align:middle;
}

.badge-outline-info {
    color: #3da5f4;
    border: 2px solid #3da5f4;
}

.badge-outline-danger {
    color: #f1536e;
    border: 1px solid #f1536e;
}

.badge-outline-success {
    color: #00c689;
    border: 1px solid #00c689;
}

.badge-outline-warning {
    color: #fda006;
    border: 1px solid #fda006;
}
</style>

<h2 class="text-center">Welcome to <?php echo $_settings->info('name') ?></h2>
<hr>




<div class="row">
<?php
     $qry0 = $conn->query("SELECT u.type FROM users u where u.id = '".$_SESSION['userdata']['id']."'");
	
     if($qry0->num_rows >0){
		 while($row0 = $qry0->fetch_assoc()):
       $profileid = $row0['type'];
	   endwhile;
     }

$qry = $conn->query("SELECT r.*,r.name,r.maintenance,r.db_name from `role` r inner join profile_role pr on r.id = pr.roleid where r.status = '1' and pr.profileid='". $profileid."' order by r.maintenance,r.role_order asc ");
					$cou = 0;
while($row = $qry->fetch_assoc()):	
if ($row['db_name'] <> "notable") {
?>
  <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box bg-light shadow">
            <span class="info-box-icon <?php echo $row['bg_color'] ?> elevation-1"><a href="<?php echo base_url.$row['pagename'] ?>" class="nav-link nav-receiving"><span class="info-box-text"><i class="<?php echo $row['aweclass'] ?>"></i></span></a></span>
            <div class="info-box-content">
			<button type="button" class="btn" onclick="window.location.href='<?php echo base_url ?><?php echo $row['pagename']?>'">
			<span class="info-box-number text-center"><?php echo $row['name'] ?></span>
                <?php
                if ($row['db_name'] == "reports") {
                    echo "</span>";
                }else{
                    //stock_list record count
                if ($row['db_name'] == "stock_list") {
                    $rnsql = "SELECT * FROM ".$row['db_name']." group by item_id";
                    echo $conn->query($rnsql)->num_rows;
                    echo "</span>";
                }else{
                    $rnsql = "SELECT * FROM ".$row['db_name'];
                    echo $conn->query($rnsql)->num_rows;
                    echo "</span>";
                }
                    //end
                }

                ?>
            
			</button>             
            </div>
        </div>
    </div>
	<?php 
}
	endwhile; ?>

</div>
