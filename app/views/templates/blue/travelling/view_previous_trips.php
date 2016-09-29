<!-- Required css stylesheets -->
<script type="text/javascript" src="<?php echo base_url().'js/jquery.fancybox-1.3.4.pack.js'; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo css_url().'/jquery.fancybox-1.3.4.css' ?>" media="screen" />

<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />


<!-- End of stylesheet inclusion -->
<?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); 

// Print The Reservation List
$content = '';
$content .= '<p style="padding:5px 5px 5px 725px"><a style="color:#38859B;cursor:pointer;" onClick="javascript:window.print();"><strong>'.translate('Print').'</strong></a></p>';
$content .= '<table border="1" width="100%">';
$content .= '<tr>';
$content .= '<th>'.translate("Status").'</th>';
$content .=	'<th>'.translate("Property").'</th>';
$content .= '<th>'.translate("Host").'</th>';
$content .= '<th>'.translate("Dates").'</th>';
$content .=	'</tr>';

foreach($result->result() as $row) {

$content .= '<tr>';
$content .= '<td>'.$row->name.'</td>';
$content .= '<td><p><strong>'.get_list_by_id($row->list_id)->title.'</strong></p><p><em>'.get_list_by_id($row->list_id)->address.'</em></p></td>';
$content .= '<td><p><img height="50" width="50" alt="image" style="float:left; margin:0 10px 10px 0;" src="'.$this->Gallery->profilepic($row->userto, 2).'" />'.ucfirst(get_user_by_id($row->userto)->username).'</p</td>';
$content .= '<td>'.date("F j, Y",$row->checkin).' - '.date("F j, Y",$row->checkout).'</td>';
$content .= '</tr>';

}

$content .= '</table>';

?>
  		<style>

@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "Status"; }
	.res_table td:nth-of-type(2):before { content: "Property" ; }
	.res_table td:nth-of-type(3):before { content: "Host "; }
	.res_table td:nth-of-type(4):before { content: "Dates"; }
	.res_table td:nth-of-type(5):before { content: "Options"; }
}
</style>
  
<script type="text/javascript">

function print_reservation() {
	var myWindow;
	myWindow=window.open('','_blank','width=800,height=500');
	myWindow.document.write("<p><?php echo addslashes($content); ?></p>");
	myWindow.print();
}
</script>
 
					<?php $this->load->view(THEME_FOLDER.'/includes/travelling_header'); ?>
<div id="dashboard_container">
    <div class="Box" id="ViewPrevious_Trips">
    	<div class="Box_Head msgbg"><h2 class="pol_msgbg"><?php echo translate("Previous Trips"); ?></h2>
    		<span class="clsFloatRight View_MyPrint" id="print"> <a href="javascript:void(0);" onclick="javascript:print_reservation();"><?php echo translate("Print this page"); ?></a> </span></div>	
        <div class="Box_Content">

                <?php if($result->num_rows() > 0) { ?>
                
                    
<table width="100%" cellpadding="0" cellspacing="0" class="clsTable_View res_table">
            	<thead>        <tr>
         <th> <?php echo translate("Status"); ?> </th>
            <th> <?php echo translate("Property"); ?> </th>
            <th> <?php echo translate("Host"); ?> </th>
            <th> <?php echo translate("Dates"); ?> </th>
            <th> <?php echo translate("Options"); ?> </th>
        </tr>
        
  <?php foreach($result->result() as $row) { ?>
<tr>
	</thead>   
         <td> <p class="View_my_Accept_Bg"><span><?php echo $row->name; ?></span></p> </td>
            <td>
                <p class="clsBold"> <?php echo anchor('rooms/'.$row->list_id, get_list_by_id($row->list_id)->title); ?> </p> 
                <p><em><?php echo get_list_by_id($row->list_id)->address; ?></em></p>
            </td>
            <td>
             <p> <img height="50" width="50" alt="image" class="yourtrip" src="<?php echo $this->Gallery->profilepic($row->userto,2); ?>" />
                <span class="clsBold">
																<a href="<?php echo site_url('users/profile').'/'.$row->userto; ?>"><?php echo ucfirst(get_user_by_id($row->userto)->username); ?></a>
																</span></p>
             <p><a class="clsLink2_Bg" href="<?php echo site_url('trips/send_message/'.$row->userto); ?>"><?php echo translate("View").' / '.translate("Send").' '.translate("Message"); ?></a></p>
            </td>
            <td><?php echo date("F j, Y",$row->checkin).' - '.date("F j, Y",$row->checkout); ?></td>
         <td>
                <p class="clsBold"><?php echo anchor('travelling/billing/'.$row->id,translate("View Billing"));  ?></p>
                <p class="clsBold"><a href="<?php echo site_url('trips/send_message/'.$row->userto); ?>"><?php echo translate("Message History"); ?></a></p>
         </td>
        </tr>
<?php } ?>
        </table>

        
                    <?php }	else	{ ?>
							 <script type="text/javascript">$('#print').hide()</script>
                            <div id="searching">
                                            <?php echo form_open("search",array('id' => 'search_form')); ?>  
                                            <p><?php echo translate("You have no Previous Trips."); ?> </p>
                                            <p><input placeholder="Where are you going?" onclick="clear_location(this);" class="seachinginput med_4 pe_6" type="text" autocomplete="off" id="location" name="location" />
                                            
                                     
                                         
                                         <button id="submit_location" class="btn_dash" onclick="if (check_inputs()) {$('#search_form').submit();}return false;" type="button" name="submit_location"><span><span><?php echo translate("Search"); ?></span></span></button></p>
										 <?php echo form_close(); ?>
                            </div>
            <?php }?>	

        </div>
</div> <!-- this tag for Header file -->
</div>
<!-- Footer Scripts -->

<script type="text/javascript">
$(document).ready(function () {
	
      var input = document.getElementById('location');
    autocomplete = new google.maps.places.Autocomplete(input);    
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
    });
 });
    function is_not_set_location() { return (!$('#location').val() || ("Where are you going?"== $('#location').val())) }
    
    function clear_location (box) {
        if (is_not_set_location()) box.value = '';
    }
    
    function check_inputs() {
        if (is_not_set_location()) { alert("Please set location"); return false; }
        return true;
    }
</script>
<?php echo $pagination; ?>