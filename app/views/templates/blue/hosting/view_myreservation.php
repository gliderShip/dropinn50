<!-- Required css stylesheets -->
<script type="text/javascript" src="<?php echo base_url().'js/jquery.fancybox-1.3.4.pack.js'; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo css_url().'/jquery.fancybox-1.3.4.css' ?>" media="screen" />
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- End of stylesheet inclusion -->
<?php
$this->load->view(THEME_FOLDER.'/includes/dash_header'); 
$this->load->view(THEME_FOLDER.'/includes/hosting_header'); 

// Print The Reservation List
$content = '';
$content .= '<p style="padding:5px 5px 5px 725px"><a style="color:#38859B;cursor:pointer;" onClick="javascript:window.print();"><strong>'.translate('Print').'</strong></a></p>';
$content .= '<table border="1" width="100%">';
$content .= '<tr>';
$content .= '<th>'.translate("Status").'</th>';
$content .=	'<th>'.translate("Dates and Location").'</th>';
$content .= '<th>'.translate("Guest").'</th>';
$content .=	'<th>'.translate("Details").'</th>';
$content .=	'</tr>';

foreach($result->result() as $row) {

$content .= '<tr>';
$content .= '<td>'.$row->name.'</td>';
$content .= '<td><p>'.get_user_times($row->checkin, get_user_timezone()).' - '.get_user_times($row->checkout, get_user_timezone()).'</p><p><strong>'.get_list_by_id($row->list_id)->title.'</strong></p><p><em>'.get_list_by_id($row->list_id)->address.'</em></p></td>';
$content .= '<td><p><img height="50" width="50" alt="image" style="float:left; margin:0 10px 10px 0;" src="'.$this->Gallery->profilepic($row->userby,2).'" />'.ucfirst(get_user_by_id($row->userby)->username).'</p</td>';
$content .= '<td>'.get_currency_symbol($row->list_id).get_currency_value1($row->list_id,$row->topay).'</td>';
$content .= '</tr>';

}

$content .= '</table>';

?>
<style>

@media only screen and (max-width: 760px) {
	.res_table td:nth-of-type(1):before { content: "Status"; }
	.res_table td:nth-of-type(2):before { content: "Dates and Location" ; }
	.res_table td:nth-of-type(3):before { content: "Guest "; }
	.res_table td:nth-of-type(4):before { content: "Details"; }
}
</style>

	<script type="text/javascript">
	<?php foreach($result->result() as $row) { ?>
		$(document).ready(function() {
				$("#cancellation_<?php echo $row->id; ?>").fancybox({	});
		});
		<?php } ?>
	</script>
		
	<script type="text/javascript">
	function print_reservation() {
		var myWindow;
		myWindow=window.open('','_blank','width=800,height=500');
		myWindow.document.write("<p><?php echo addslashes($content); ?></p>");
		myWindow.print();
	}
</script>
<div id="dashboard_container">					
    <div class="Box" id="View_MyReserve">
    	<div class="Box_Head msgbg">
        	<h2 class="my_resv_head"><?php echo translate("My Reservations"); ?></h2><span class="View_MyPrint clsFloatRight" id="print"> <a href="javascript:void(0);" onclick="javascript:print_reservation();"><?php echo translate("Print this page"); ?></a> </span>
            <div class="clearfix"></div>
        </div>
        <div class="Box_Content">
            <?php if($result->num_rows() > 0) { ?>
            <p class="Txt_Right_Align"></p>
           <table width="100%" cellpadding="0" cellspacing="0" class="clsTable_View res_table">
            	<thead>
            <tr>
             <th> <?php echo translate("Status"); ?> </th>
                <th> <?php echo translate("Dates, Times and Location"); ?> </th>
                <th> <?php echo translate("Guest"); ?> </th>
                <th> <?php echo translate("Details"); ?> </th>
            </tr>
            </thead>
            
            <?php foreach($result->result() as $row) { ?>
            <tr>
             <td> <p class="View_my_Accept_Bg"><span><?php echo $row->name; ?></span></p> </td>
                <td>
                 <p> <?php echo get_user_times($row->checkin, get_user_timezone()).' - '.get_user_times($row->checkout, get_user_timezone()); ?> </p> <br />
                    <p class="clsBold"> <?php echo anchor('rooms/'.$row->list_id,get_list_by_id($row->list_id)->title); ?> </p> 
                    <p> <?php echo get_list_by_id($row->list_id)->address; ?> </p> 
                </td>
                <td>
                 <p> <img height="50" width="50" alt="image" class="yourtrip" src="<?php echo $this->Gallery->profilepic($row->userby,2); ?>" />
                    <span class="clsBold">
																				<a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo ucfirst(get_user_by_id($row->userby)->username); ?></a>
																				</span></p>
                    <p class="yourtrip1"><a class="clsLink2_Bg" href="<?php echo site_url('trips/send_message1/'.$row->userby); ?>"><?php echo translate("View").' / '.translate("Send").' '.translate("Message"); ?></a></p>
                </td>
             <td>
                 <p><?php echo get_currency_symbol($row->list_id).get_currency_value_lys($row->currency,get_currency_code(),$row->topay); ?></p>
                    <p class="clsBold"><?php echo anchor('trips/request/'.$row->id,translate("View Confirmation"));  ?></p>
              <?php if ($row->status < 8 && $row->status != 5 && $row->status !=6 && $row->status !=4 && $row->status !=2 && $row->status !=1) { ?>
                <p class="clsBold"><a id="cancellation_<?php echo $row->id; ?>" href="<?php echo site_url('travelling/cancel_travel/'.$row->id.'/'.$row->list_id.'/'.'host'); ?>"><?php echo translate("Cancel Reservation");  ?></a></p>
                <?php } ?>
             </td>
            </tr>
            <?php } ?>
            </table>
            <?php } else { ?>
            	 <script type="text/javascript">$('#print').hide()</script>
            <p class="no_listings paddingzero"><?php echo translate("You have no reservations."); ?>
            <br>
            <a class="clsLink2_Bg myreser_create" href="<?php echo site_url('rooms/new'); ?>"><span><?php echo translate("Create a new listing"); ?></span></a>
            </p>
            <?php } ?>
        </div>
    </div> 

<!-- Required css stylesheets -->
<!--<script type="text/javascript" src="<?php echo base_url().'js/jquery.fancybox-1.3.4.pack.js'; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo css_url().'/jquery.fancybox-1.3.4.css' ?>" media="screen" />
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- End of stylesheet inclusion -->
<!--<?php
$this->load->view(THEME_FOLDER.'/includes/dash_header'); 
$this->load->view(THEME_FOLDER.'/includes/hosting_header'); 

// Print The Reservation List
$content = '';
$content .= '<p style="padding:5px 5px 5px 725px"><a style="color:#38859B;cursor:pointer;" onClick="javascript:window.print();"><strong>'.translate('Print').'</strong></a></p>';
$content .= '<table border="1" width="100%">';
$content .= '<tr>';
$content .= '<th>'.translate("Status").'</th>';
$content .=	'<th>'.translate("Dates and Location").'</th>';
$content .= '<th>'.translate("Guest").'</th>';
$content .=	'<th>'.translate("Details").'</th>';
$content .=	'</tr>';

foreach($result->result() as $row) {

$content .= '<tr>';
$content .= '<td>'.$row->name.'</td>';
$content .= '<td><p>'.get_user_times($row->checkin, get_user_timezone()).' - '.get_user_times($row->checkout, get_user_timezone()).'</p><p><strong>'.get_list_by_id($row->list_id)->title.'</strong></p><p><em>'.get_list_by_id($row->list_id)->address.'</em></p></td>';
$content .= '<td><p><img height="50" width="50" alt="image" style="float:left; margin:0 10px 10px 0;" src="'.$this->Gallery->profilepic($row->userby,2).'" />'.ucfirst(get_user_by_id($row->userby)->username).'</p</td>';
$content .= '<td>'.get_currency_symbol($row->list_id).get_currency_value_lys($row->currency,get_currency_code(),$row->topay).'</td>';
$content .= '</tr>';

}

$content .= '</table>';

?>


	<script type="text/javascript">
	<?php foreach($result->result() as $row) { ?>
		$(document).ready(function() {
				$("#cancellation_<?php echo $row->id; ?>").fancybox({	});
		});
		<?php } ?>
	</script>
		
	<script type="text/javascript">
	function print_reservation() {
		var myWindow;
		myWindow=window.open('','_blank','width=800,height=500');
		myWindow.document.write("<p><?php echo addslashes($content); ?></p>");
		myWindow.print();
	}
</script>
<div id="dashboard_container">					
    <div class="Box" id="View_MyReserve">
    	<div class="Box_Head msgbg">
        	<h2><?php echo translate("My Reservations"); ?><span class="View_MyPrint clsFloatRight"> <a href="javascript:void(0);" onclick="javascript:print_reservation();"><?php echo translate("Print this page"); ?></a> </span></h2>
            <div style="clear:both"></div>
        </div>
        <div class="Box_Content">
            <?php if($result->num_rows() > 0) { ?>
            <p class="Txt_Right_Align"></p>
            <table width="100%" cellpadding="0" cellspacing="0" class="clsTable_View">
            <tr>
             <th> <?php echo translate("Status"); ?> </th>
                <th> <?php echo translate("Dates and Location"); ?> </th>
                <th> <?php echo translate("Guest"); ?> </th>
                <th> <?php echo translate("Details"); ?> </th>
            </tr>
            
            <?php foreach($result->result() as $row) { ?>
            <tr>
             <td width="15%"> <p class="View_my_Accept_Bg"><span><?php echo $row->name; ?></span></p> </td>
                <td width="30%">
                 <p> <?php echo get_user_times($row->checkin, get_user_timezone()).' - '.get_user_times($row->checkout, get_user_timezone()); ?> </p> <br />
                    <p class="clsBold"> <?php echo anchor('rooms/'.$row->list_id,get_list_by_id($row->list_id)->title); ?> </p> 
                    <p> <?php echo get_list_by_id($row->list_id)->address; ?> </p> 
                </td>
                <td width="25%">
                 <p> <img height="50" width="50" alt="image" style="float:left; margin:0 10px 10px 0;" src="<?php echo $this->Gallery->profilepic($row->userby,2); ?>" />
                    <span class="clsBold">
																				<a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo ucfirst(get_user_by_id($row->userby)->username); ?></a>
																				</span></p>
                    <p style="margin:5px 0 0 0;"><a class="clsLink2_Bg" href="<?php echo site_url('trips/send_message1/'.$row->userby); ?>"><?php echo translate("View").' / '.translate("Send").' '.translate("Message"); ?></a></p>
                </td>
             <td width="15%">
                 <p><?php echo get_currency_symbol($row->list_id).get_currency_value_lys($row->currency,get_currency_code(),$row->topay); ?></p>
                    <p class="clsBold"><?php echo anchor('trips/request/'.$row->id,translate("View Confirmation"));  ?></p>
              <?php if ($row->status < 8 && $row->status != 5 && $row->status !=6 && $row->status !=4 && $row->status !=2 && $row->status !=1) { ?>
                <p class="clsBold"><a id="cancellation_<?php echo $row->id; ?>" href="<?php echo site_url('travelling/cancel_travel/'.$row->id.'/'.$row->list_id.'/'.'host'); ?>"><?php echo translate("Cancel Reservation");  ?></a></p>
                <?php } ?>
             </td>
            </tr>
            <?php } ?>
            </table>
            <?php } else { ?>
            <p class="no_listings" style="padding:0px;"><?php echo translate("You have no reservations."); ?>
            <br>
            <a class="clsLink2_Bg" style="margin:10px 0 10px 0" href="<?php echo site_url('rooms/new'); ?>"><span><?php echo translate("Create a new listing"); ?></span></a>
            </p>
            <?php } ?>
        </div>
    </div> -->

</div>