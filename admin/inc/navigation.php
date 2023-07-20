</style>


<!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-no-expand">
        <!-- Brand Logo -->
        <a href="<?php echo base_url ?>admin/index.php" class="brand-link bg-primary text-sm">
        <img src="<?php echo validate_image($_settings->info('logo'))?>" alt="Store Logo" class="brand-image img-circle elevation-3 bg-black" style="width: 1.8rem;height: 1.8rem;max-height: unset">
        <span class="brand-text font-weight-light"><?php echo $_settings->info('short_name') ?></span>
        </a>
        <!-- Sidebar -->
        <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-transition os-host-scrollbar-horizontal-hidden">
          <div class="os-resize-observer-host observed">
            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
          </div>
          <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
          </div>
          <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 646px;"></div>
          <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
              <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
                <!-- Sidebar user panel (optional) -->
                <div class="clearfix"></div>
                <!-- Sidebar Menu -->
                <nav class="mt-4">
                   <ul class="nav nav-pills nav-sidebar flex-column text-sm nav-compact nav-flat nav-child-indent nav-collapse-hide-child" data-widget="treeview" role="menu" data-accordion="false">
                   


					<?php
	   $qry0 = $conn->query("SELECT u.type FROM users u where u.id = '".$_SESSION['userdata']['id']."'");
	
     if($qry0->num_rows >0){
		 while($row0 = $qry0->fetch_assoc()):
       $profileid = $row0['type'];
	   endwhile;
     }	
	
					$qry = $conn->query("SELECT r.*,r.name,r.maintenance,r.db_name from `role` r inner join profile_role pr on r.id = pr.roleid where r.status = '1' and pr.profileid='". $profileid."' order by r.maintenance,r.role_order asc ");
					$cou = 0;
					while($row = $qry->fetch_assoc()):	?>
					<?php if($row['maintenance'] == 0): ?>				
				   <li class="nav-item dropdown">
                      <a href="<?php echo base_url.$row['pagename'] ?>" class="nav-link nav-home">
                        <i class="nav-icon <?php echo $row['aweclass']; ?>"></i>
                        <p>
                          <?php echo $row['name']; ?>
                        </p>
                      </a>
                    </li>
					 <?php endif; ?>
                    <?php if($row['maintenance'] == 1): 
					$cou =$cou +1 ;
					?>
					<?php if($cou == 1): ?>
					<li class="nav-header">Maintenance</li>
					 <?php endif; ?>
                    <li class="nav-item dropdown">
                      <a href="<?php echo base_url.$row['pagename'] ?>" class="nav-link nav-home">
                        <i class="nav-icon <?php echo $row['aweclass']; ?>"></i>
                        <p>
                          <?php echo $row['name']; ?>
                        </p>
                      </a>
                    </li>
							  <?php endif; ?>
							 
							 
                    <?php endwhile; ?>










				  

                  </ul>
                </nav>
                <!-- /.sidebar-menu -->
              </div>
            </div>
          </div>
          <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
              <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
          </div>
          <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
              <div class="os-scrollbar-handle" style="height: 55.017%; transform: translate(0px, 0px);"></div>
            </div>
          </div>
          <div class="os-scrollbar-corner"></div>
        </div>
        <!-- /.sidebar -->
      </aside>
      <script>
        var page;
    $(document).ready(function(){
      page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
      page = page.replace(/\//gi,'_');

      if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
        if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
          $('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
        }
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }

      }
      
		$('#receive-nav').click(function(){
      $('#uni_modal').on('shown.bs.modal',function(){
        $('#find-transaction [name="tracking_code"]').focus();
      })
			uni_modal("Enter Tracking Number","transaction/find_transaction.php");
		})
    })
  </script>