<div class="container-fluid top-sp body-color">
	<div class="container">
		
		<?php
			//Show Flash Message
			if($msg = $this->session->flashdata('flash_message'))
			{
				//echo $msg;
			}
	  ?>
	<div class="col-xs-9 col-md-9 col-sm-9">
  <h1 class="page-header"><?php echo translate_admin('Dashboard'); ?> <span class="page-sub"><?php echo translate_admin('Latest Activity'); ?></span></h1>
 </div>
  <?php
  $ci = & get_instance();
  $ci->load->model('Trips_model');
  $ci->load->model('Users_model');
	$no_user          = $ci->Users_model->get_all()->num_rows();
	$no_list          = $this->db->get('list')->num_rows(); 
	$totalreservation =  $ci->Trips_model->get_reservation()->num_rows(); 
	?>
  
  <!--   <p><img src="<?php echo base_url().'images/chat.gif';?>" height="40" width="45" alt="img" /></p>-->
    <div class="col-xs-9 col-md-9 col-sm-9">
    <div class="col-xs-12 col-md-3 col-sm-3">
      <div class="panel text-center bg-color-green">
      	<div class="panel-body">
      	<?php if(isset($no_user))?><a href="<?php echo admin_url('members'); ?>"> <?php echo $no_user; ?></a>
        <a href="<?php echo admin_url('members'); ?>"><?php echo translate_admin('Members'); ?></a>
      	</div>
      <div class="panel-footer back-footer-green"><?php echo translate_admin('Total Users'); ?></div>
      </div>
       </div>       

	<div class="col-xs-12 col-md-3 col-sm-3">
      <div class="panel text-center bg-color-pink">
		<div class="panel-body">
		<?php if(isset($no_list))?><a href="<?php echo admin_url('lists'); ?>"> <?php echo $no_list; ?></a>
         <a href="<?php echo admin_url('lists'); ?>"> <?php echo translate_admin('Lists'); ?></a>
		</div>
		<div class="panel-footer back-footer-pink">
         <?php echo translate_admin('Total List'); ?>
         </div>
         </div> 
         </div>    
           

	<div class="col-xs-12 col-md-3 col-sm-3">
      <div class="panel text-center bg-color-violet">
		<div class="panel-body">
		<?php if(isset($totalreservation))?> <a href="<?php echo admin_url('payment/finance'); ?>"> <?php echo $totalreservation;  ?></a>
         <a href="<?php echo admin_url('payment/finance'); ?>"><?php echo translate_admin('Reservation'); ?></a>
		</div>
		<div class="panel-footer back-footer-violet">
         <?php echo translate_admin('Total Reservation'); ?>
         </div>
         </div> 
         </div>    
         </div>

		<div class="col-xs-9 col-md-9 col-sm-9">                
       <div class="col-xs-12 col-md-3 col-sm-3">
      <div class="panel text-center bg-color-green">
		<div class="panel-body">
               <?php if(isset($todayuser)) echo $todayuser; else echo '0'; ?>
              </div>
              <div class="panel-footer back-footer-green">
              <?php echo translate_admin('Today Users'); ?></td>
             </div>
            </div>
           </div>

            <div class="col-xs-12 col-md-3 col-sm-3">
      <div class="panel text-center bg-color-pink">
		<div class="panel-body">
              <?php if(isset($today_userlist)) echo $today_userlist; else echo '0'; ?>
           </div>
           <div class="panel-footer back-footer-pink">
              <?php echo translate_admin('Today Lists'); ?>
            </div>
           </div>
           </div>
              
              
              <div class="col-xs-12 col-md-3 col-sm-3">
      <div class="panel text-center bg-color-violet">
		<div class="panel-body">
                <?php if(isset($today_reservation)) echo $today_reservation; else echo '0'; ?>
               </div>
         <div class="panel-footer back-footer-violet">
                 <?php echo translate_admin('Today Reservation'); ?>
             </div>
            </div>
            </div>
            </div>   
              
<div class="col-xs-9 col-md-9 col-sm-9">       
  <h1 class="page-header"><?php echo translate_admin('Version'); ?></h1>
    <p class="ins-text"><a href="#"><?php echo translate_admin('Installed Version'); ?> - 5.0</a> 
  </ul>
</div>
</div>
</div>
