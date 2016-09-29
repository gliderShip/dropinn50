<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- End of stylesheet inclusion -->
<?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>

<?php $this->load->view(THEME_FOLDER.'/includes/account_header'); ?>
<script>
function show(id)
{
	$('.add_more_'+id).toggle(0,function()
	{
		if ( $(this).is(':visible')) 
		{
       	 $('#less_'+id).show();
       	 $('#less_up_arrow_'+id).show();
       	 $('#add_more_up_arrow_'+id).hide();
       	 $('#add_more_'+id).hide();
   		}
   		else
   		{
   		 $('#less_'+id).hide();
   		 $('#less_up_arrow_'+id).hide();
   		 $('#add_more_up_arrow_'+id).show();
       	 $('#add_more_'+id).show();
   		}
	});
}
	
function logout_login_history(login_history_id,session_id)
{
	$.ajax
	({
  		url: "<?php echo site_url('account/logout'); ?>",
  		async: true,
  		type: "GET",
  		data: "login_history_id="+login_history_id+"&session_id="+session_id,
  		success: function(data) 
  		{
  		if(data == 'success')
  		{
  		$('#logout_'+login_history_id).hide();
    	$('#logged_out_'+login_history_id).show();
        }
        else
        {
        location.reload();
        }
    	}
   	});
}
</script>
<div id="dashboard_container">	
    <div class="Box" id="View_Notification">
        <div class="Box_Head msgbg"><h2><?php echo translate("Login History"); ?></h2></div>
        <div class="Box_Content">
         
        <!-- <section class="panel-body">-->
        <table id="login_history" class="clsTable_View res_table" cellspacing="0" cellpadding="0" width="100%">
          <thead>
            <tr>
              <th><?php echo translate("Browser/Device"); ?></th>
              <th><?php echo translate("Location"); ?>
                  &nbsp;
                  <i class="fa fa-question-circle location-tooltip" data-behavior="tooltip" data-position="right" data-sticky="true" aria-label="Location is approximated."></i>
                  <div class="tooltip tooltip-left-middle" role="tooltip" style="left: 547.602px; top: 233.5px;" aria-hidden="true">  <p class="panel-body" style="padding:11px !important;font-weight: normal;">Location is approximated.</p></div>
        </div>
              </th>
              <th><?php echo translate("Recent Activity"); ?></th>
              <th><?php echo translate(""); ?>&nbsp;</th>
            </tr>
          </thead>
          <tbody>
          	<?php
          	$i = +221;
          	foreach($login_historys->result() as $login_history)
          	{
          	$this->agent->parse_agent_string($login_history->user_agent);
			$query_inner = $this->db->query('select * from login_history where id != '.$login_history->id.' AND ip_address = "'.$login_history->ip_address.'" AND user_id = '.$this->dx_auth->get_user_id().' AND user_agent REGEXP "[[:<:]]'.$login_history->user_agent1.'[[:>:]]" = 1 order by last_activity DESC');
          	?>
                  <tr>
                    <td>
                      <ul class="list-unstyled">
                        <li><?php echo $login_history->user_agent1;?></li>
                        <li><span class="text-muted"><?php echo $this->agent->platform();?></span></li>
                      </ul>
                    </td>
                    <td>
                      <?php
                      if($login_history->location == '')
					  {
                      $result = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip='.$login_history->ip_address));
                      if($result['geoplugin_countryName'] != '')
					  {
					  	if($result['geoplugin_city'] != '')
						{
                      $location = $result['geoplugin_city'].', '.$result['geoplugin_region'].', '.$result['geoplugin_countryName'];
						}
						else {
							if($result['geoplugin_region'] != '')
						{
						$location = $result['geoplugin_region'].', '.$result['geoplugin_countryName'];	
						}
						else {
						$location = $result['geoplugin_countryName'];		
						}
						}
					  }
					  else
					  	{
					  		$location = 'No Location';
					  	}
						$this->Common_model->updateTableData('login_history',0,array('id'=>$login_history->id),array('location'=>$location));
						echo $location;
					  }
					  else {
						  echo $login_history->location;
					  }
                      ?>
                    </td>
                    <td>
                      <?php 
                      $date1 = new DateTime(date('Y-m-d H:i:s', time()));
					  $date2 = new DateTime(date('Y-m-d H:i:s',$login_history->last_activity));
					  $interval = $date1->diff($date2);
					 
					  if($interval->y == 0)
					  {
					  if($interval->m == 0)
					  {
					  if($interval->days == 0)
					  {
					  	if($interval->h == 0)
					  	{
					  	if($interval->i == 0)
					  	{
					  	if($interval->s == 0)
					  	{
					  		
					  	}
						else
						{
						echo $interval->s.' second';
						echo ($interval->s != 1) ? 's':'';		
						}
					  	}
						else
						{
						echo $interval->i.' mintue';
						echo ($interval->i != 1) ? 's':'';	
						}
					  	}
						else
						{
						echo $interval->h.' hour';
						echo ($interval->h != 1) ? 's':'';	
						}
					  }
					  else {
						  echo $interval->days.' day';
						  echo ($interval->days != 1) ? 's':'';
					  }
					  }
					  else {
						  echo $interval->m.' month';
						  echo ($interval->m != 1) ? 's':'';	
					  }
					  }
 					 else {
						  echo $interval->y.' year';
						  echo ($interval->y != 1) ? 's':'';	
					  }
                       ?> 
                      ago&nbsp;
                      <i class="fa fa-question-circle text-muted question_ip_<?php echo $login_history->session_id;?>" data-behavior="tooltip"></i>
                      <div class="tooltip_<?php echo $login_history->session_id;?> tooltip-left-middle" role="tooltip" style="left: 413.92px; top: <?php echo $i;?>px;" aria-hidden="true">
                      	<i class="fa fa-angle-right"></i>
                      	  <p class="panel-body" style="padding:6px !important;">Time: <?php echo date('F d',gmt_to_local($login_history->last_activity, get_user_timezone(), FALSE)).' at '.date('h:ia T',gmt_to_local($login_history->last_activity, get_user_timezone(), FALSE));?><br>IP: <?php echo $login_history->ip_address; ?></p></div>
                     
                      <style>
                      .question_ip_<?php echo $login_history->session_id;?>:hover + .tooltip_<?php echo $login_history->session_id;?>
                      {
                      	opacity: 1;
                      	display:block;
                      }
                      .tooltip_<?php echo $login_history->session_id;?>
                      {
                      	position: absolute;
						z-index: 3000;
						max-width: 250px;
						display:none;
						filter: alpha(opacity=0);
						-webkit-transition: opacity 0.2s;
						transition: opacity 0.2s;
						border-radius: 2px;
						box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
						background-color: #fff;
						top: 0 !important;
                      }
                      .clsTable_View td{
	position:relative !important;
}

@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "Browser/Device"; }
	.res_table td:nth-of-type(2):before { content: "Location" ; }
	.res_table td:nth-of-type(3):before { content: "Recent Activity"; }
	.res_table td:nth-of-type(4):before { content: ""; }
      .tooltip_<?php echo $login_history->session_id;?>
                      {left:65px !important}
                     }
@media (min-width:767px){
	.tooltip_<?php echo $login_history->session_id;?>
                      {left:-126px !important}
}
                      </style>
                      <br>
                      <?php
                      if($query_inner->num_rows() != 0)
					  {
					  	?>
                        <a href="javascript:void(0)" class="show-more" id="add_more_<?php echo $login_history->id; ?>" onclick="show('<?php echo $login_history->id; ?>')"><?php echo $query_inner->num_rows();?> more</a>
                        <a href="javascript:void(0)" class="show-more" id="less_<?php echo $login_history->id; ?>" onclick="show('<?php echo $login_history->id; ?>')" style="display:none;">less</a>
                        <i class="fa fa-caret-down" id="add_more_up_arrow_<?php echo $login_history->id; ?>"></i>
                        <i class="fa fa-caret-up" id="less_up_arrow_<?php echo $login_history->id; ?>" style="display:none;"></i>
                      <?php 
					  } ?>
                    </td>
                    <td class="text-right" id="">
                         <?php
                          if($login_history->logout == 0)
						  {
                          ?>
                          <a href="javascript:void(0)" class="kill-session-action" id="logout_<?php echo $login_history->id;?>" title="Log Out" onclick="logout_login_history('<?php echo $login_history->id;?>','<?php echo $login_history->session_id;?>');">
                            Log Out
                          </a>
                          <span class="text-muted" id="logged_out_<?php echo $login_history->id;?>" style="display:none;">Logged Out</span>
                          <?php
                          }
						  else {
						   ?>
						   <span class="text-muted" id="logged_out_<?php echo $login_history->id;?>">Logged Out</span>
						   <?php
						  }
                          ?>
                    </td>
                  </tr>
                  <?php
                  
				  if($query_inner->num_rows() != 0)
				  {
				  	$j = $i+55;
				  	foreach($query_inner->result() as $inner_history)
					{
                  ?>
                  <tr class="add_more_<?php echo $login_history->id; ?>" style="display:none;">
                    <td>
                      <ul class="list-unstyled">
                        <li><?php echo $login_history->user_agent1;?></li>
                        <li><span class="text-muted"><?php echo $this->agent->platform();?></span></li>
                      </ul>
                    </td>
                    <td>
                      <?php
                      if($inner_history->location == '')
					  {
                      $result = unserialize(@file_get_contents('http://www.geoplugin.net/php.gp?ip='.$inner_history->ip_address));
                      if($result['geoplugin_countryName'] != '')
					  {
					  	if($result['geoplugin_city'] != '')
						{
                      $location = $result['geoplugin_city'].', '.$result['geoplugin_region'].', '.$result['geoplugin_countryName'];
						}
						else {
							if($result['geoplugin_region'] != '')
						{
						$location = $result['geoplugin_region'].', '.$result['geoplugin_countryName'];	
						}
						else {
						$location = $result['geoplugin_countryName'];		
						}
						}
					  }
					  else
					  	{
					  		$location = 'No Location';
					  	}
						$this->Common_model->updateTableData('login_history',0,array('id'=>$inner_history->id),array('location'=>$location));
						echo $location;
					  }
					  else {
						  echo $inner_history->location;
					  }
                      ?>
                    </td>
                    <td>
                      <?php 
                      $date1 = new DateTime(date('Y-m-d H:i:s', time()));
					  $date2 = new DateTime(date('Y-m-d H:i:s',$inner_history->last_activity));
					  $interval = $date1->diff($date2);
					 
					  if($interval->y == 0)
					  {
					  if($interval->m == 0)
					  {
					  if($interval->days == 0)
					  {
					  	if($interval->h == 0)
					  	{
					  	if($interval->i == 0)
					  	{
					  	if($interval->s == 0)
					  	{
					  		
					  	}
						else
						{
						echo $interval->s.' second';
						echo ($interval->s != 1) ? 's':'';		
						}
					  	}
						else
						{
						echo $interval->i.' mintue';
						echo ($interval->i != 1) ? 's':'';	
						}
					  	}
						else
						{
						echo $interval->h.' hour';
						echo ($interval->h != 1) ? 's':'';	
						}
					  }
					  else {
						  echo $interval->days.' day';
						  echo ($interval->days != 1) ? 's':'';
					  }
					  }
					  else {
						  echo $interval->m.' month';
						  echo ($interval->m != 1) ? 's':'';	
					  }
					  }
 					 else {
						  echo $interval->y.' year';
						  echo ($interval->y != 1) ? 's':'';	
					  }
                       ?> 
                      ago&nbsp;
                      <i class="fa fa-question-circle text-muted question_ip_<?php echo $inner_history->session_id;?>" data-behavior="tooltip"></i>
                      <div class="tooltip_<?php echo $inner_history->session_id;?> tooltip-left-middle" role="tooltip" style="left: 413.92px; top: <?php echo $j;?>px;" aria-hidden="true"> 
                      	<i class="fa fa-angle-right"></i>
                      	  <p class="panel-body" style="padding:6px !important;">Time: <?php echo date('F d',gmt_to_local($inner_history->last_activity, get_user_timezone(), FALSE)).' at '.date('h:ia T',gmt_to_local($inner_history->last_activity, get_user_timezone(), FALSE));?><br>IP: <?php echo $inner_history->ip_address; ?></p>
                      	
                      </div>
                      <style>
                      .question_ip_<?php echo $inner_history->session_id;?>:hover + .tooltip_<?php echo $inner_history->session_id;?>
                      {
                      	opacity: 1;
                      	display:block;
                      }
                      .tooltip_<?php echo $inner_history->session_id;?>
                      {
                      	position: absolute;
						z-index: 3000;
						max-width: 280px;
						display:none;
						filter: alpha(opacity=0);
						-webkit-transition: opacity 0.2s;
						transition: opacity 0.2s;
						border-radius: 2px;
						box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
						background-color: #fff;
						top: 30px !important;
						left: 0 !important;
                      }
                      </style>
                      <br>
                    </td>
                    <td class="text-right">
                          <?php
                          if($inner_history->logout == 0)
						  {
                          ?>
                          <a href="javascript:void(0)" class="kill-session-action" id="logout_<?php echo $inner_history->id;?>" title="Log Out" onclick="logout_login_history('<?php echo $inner_history->id;?>','<?php echo $inner_history->session_id;?>');">
                            Log Out
                          </a>
                           <span class="text-muted" id="logged_out_<?php echo $inner_history->id;?>" style="display:none;">Logged Out</span>
                          <?php
                          }
						  else {
						   ?>
						   <span class="text-muted" id="logged_out_<?php echo $inner_history->id;?>">Logged Out</span>
						   <?php
						  }
                          ?>
                    </td>
                  </tr>
                  <?php
                  $j = $j+55;
                  }
                  }
                  $i = $i+55;
                  }
                  ?>
          </tbody>
        </table>
      <!--</section>-->
       <div class="panel-footer">
        If you see something unfamiliar, <a href="<?php echo base_url().'users/change_password/'.$this->dx_auth->get_user_id(); ?>" class="change-password-link">change your password</a>.
      </div>     
	</div>
</div>
</div>
<style>
.list-unstyled > li
{
	padding : 0px !important;
}
.location-tooltip:hover + .tooltip
{
display:block;
}
.table>tbody>tr>th, .table>tbody>tr>td, .table>tfoot>tr>th, .table>tfoot>tr>td {
padding: 8px;
border-top: 1px solid #dce0e0;
vertical-align: top;
}
*, *:before, *:after, hr, hr:before, hr:after, input[type="search"], input[type="search"]:before, input[type="search"]:after {
-moz-box-sizing: border-box;
box-sizing: border-box;
}
td
{
/*width: 222px;*/
}
.table>thead>tr>th
{
padding: 8px;
}
.text-muted {
color: #82888a;
}
.tooltip {
position: absolute;
z-index: 3000;
max-width: 280px;
display:none;
filter: alpha(opacity=0);
-webkit-transition: opacity 0.2s;
transition: opacity 0.2s;
border-radius: 2px;
box-shadow: 0 0 0 1px rgba(0,0,0,0.1);
background-color: #fff;
top: 0;
left: 0;
}
.tooltip-bottom-middle::after {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #fff transparent -moz-use-text-color;
    border-image: none;
    border-style: solid solid none;
    border-width: 9px 9px 0;
    bottom: -9px;
    content: "";
    display: inline-block;
    left: 20px;
    margin-left: -9px;
    position: absolute;
    top: auto;
}

.tooltip-bottom-middle::before {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: rgba(0, 0, 0, 0.1) transparent -moz-use-text-color;
    border-image: none;
    border-style: solid solid none;
    border-width: 10px 10px 0;
    bottom: -10px;
    content: "";
    display: inline-block;
    left: 20px;
    margin-left: -10px;
    position: absolute;
    top: auto;
}
.fa-angle-right{
position: absolute;
right: -9px;
top: 0;
color: #ddd;
font-size: 26px;
}
</style>