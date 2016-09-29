<!-- Required css stylesheets -->
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- End of stylesheet inclusion -->

 
<!--<?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>
<?php $this->load->view(THEME_FOLDER.'/includes/account_header'); ?>-->
<div id="dashboard_page" class="container container_bg">
<style>
.h2 > strong {
    word-wrap: break-word;
}
@media (max-width:767px){
	.cre-wish{float:left !important;}
	.text-right{text-align:left !important;}
}
.img-bot{
	margin-bottom: 10px;
}
.cre-wish{
	float:right;
}
.top-bar{
	margin-bottom:15px !important;
	overflow: hidden;
	  padding: 10px 0px;
}
.Box
{
box-shadow: none !important;
}
#main_content .container_bg
{
margin-top: 0px;
min-height: 0px;
}
.container_bg {
background: none !important;
}
.row>.col-1, .row>.col-2, .row>.col-3, .row>.col-4, .row>.col-5, .row>.col-6, .row>.col-7, .row>.col-8, .row>.col-9, .row>.col-10, .row>.col-11, .row>.col-12 {
padding-left: 12.5px;
padding-right: 12.5px;
}
.col-4 {
width: 32.33333%;
}
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
position: relative;
min-height: 1px;
overflow: hidden;
}
.row-space-4 {
margin-bottom: 10px;
display: inline-block !important;
}
.panel {
border: 1px solid #dce0e0;
background-color: #fff;
border-radius: 0;
}
.media-photo {
-webkit-backface-visibility: hidden;
backface-visibility: hidden;
position: relative;
display: inline-block;
vertical-align: bottom;
overflow: hidden;
background-color: #cacccd;
}
.wishlist-bg-img {
background-size: cover;
background-position: center;
background-repeat: no-repeat;
}
.wishlist-bg-img {
background-size: cover;
background-position: center;
background-repeat: no-repeat;
}
.media-cover, .media-cover-dark:after {
position: absolute;
top: 0;
bottom: 0;
left: 0;
right: 0;
}
.media-cover-dark:after {
background: #000;
opacity: 0.3;
filter: alpha(opacity=30);
content: " ";
}
.media-cover, .media-cover-dark:after {
position: absolute;
top: 0;
bottom: 0;
left: 0;
right: 0;
}
.row.row-table {
width: 100%;
width: calc(100% + 25px);
}
.row {
margin-left: -12.5px;
margin-right: -12.5px;
}
.row-full-height {
height: 100%;
}
.row-table {
display: table;
table-layout: fixed;
}
.col-middle {
vertical-align: middle;
}
*, *:before, *:after, hr, hr:before, hr:after, input[type="search"], input[type="search"]:before, input[type="search"]:after {
-moz-box-sizing: border-box;
box-sizing: border-box;
}
.wishlist-unit {
height: 322px;
max-height: 322px;
}
.wishlists-container.page-container.row-space-top-4 {
    margin-left: 20px;
    margin-right: -14px;
}
 .h2 {
font-size: 32px;
}
 strong {
font-weight: bold;
line-height: 1;
}
.text-contrast {
color: #fff;
}
.btn-guest:active {
border-color: #7c713f !important;
background-color: #7c713f !important;
color: #fff;
}
.btn-guest:hover, .btn-guest:focus {
border-color: #c0b584 !important;
border-bottom-color: #8d8048 !important;
background-color: #c0b584 !important;
color: #fff;
}
.btn-guest {
padding: 7px 21px;
font-size: 14px;
border-color: #b4a76c !important;
border-bottom-color: #7c713f !important;
background-color: #b4a76c !important;
color: #fff;
background-image: none !important;
}
.media-body {
display: table-cell;
width: 668px;
}
.pull-left {
float: left;
}
.media>.pull-left {
  margin-right: 0px;
  margin-top: -6px;
}
.h4 {
font-size: 15px;
}
h4, .h4, h5, .h5, h6, .h6 {
font-weight: bold;
}
.text-muted {
color: #82888a;
}
.modal_save_to_wishlist {
    opacity: 1;
}
.modal_save_to_wishlist {
    background-color: rgba(0, 0, 0, 0.75);
   bottom: 0;
    left: 0;
    opacity: 1;
    overflow-y: auto;
    position: fixed;
    right: 0;
    top: 0;
    transition: opacity 0.2s ease 0s;
    z-index: 2000;
}
.modal-table {
    display: table;
    height: 100%;
    table-layout: fixed;
    width: 100%;
}
.modal-cell {
    display: table-cell;
    height: 100%;
    padding: 50px;
    vertical-align: middle;
    width: 100%;
}
.wishlist-modal {
    max-width: 700px;
    overflow: visible;
}
.modal-content {
    background-color: #fff;
    border-radius: 2px;
    margin-left: auto;
    margin-right: auto;
    max-width: 520px;
    overflow: hidden;
    position: relative;
}
.panel-dark, .panel-header {
    background-color: #edefed;
}
.panel-header {
    border-bottom: 1px solid #dce0e0;
    color: #565a5c;
    font-size: 16px;
    padding-bottom: 14px;
    padding-top: 14px;
}
.panel-header, .panel-body, ul.panel-body > li, ol.panel-body > li, .panel-footer {
  /*  border-top: 1px solid #dce0e0;*/
    margin: 0;
    padding: 20px;
    position: relative;
}
.panel-footer {
    text-align: right;
}
.panel-footer {
    border-top: 1px solid #dce0e0;
    margin: 0;
    padding: 20px;
    position: relative;
}
.wishlist-modal .dynamic-listing-photo-container {
    height: 64px;
}
.panel-close, .alert-close, .modal-close {
    color: #cacccd;
    cursor: pointer;
    float: right;
    font-size: 2em;
    font-style: normal;
    font-weight: normal;
    line-height: 0.7;
    vertical-align: middle;
}
.row > .col-1, .row > .col-2, .row > .col-3, .row > .col-4, .row > .col-5, .row > .col-6, .row > .col-7, .row > .col-8, .row > .col-9, .row > .col-10, .row > .col-11, .row > .col-12 {
    padding-left: 12.5px;
    padding-right: 12.5px;
}
.col-2 {
    width: 16.6667%;
}
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
    min-height: 1px;
    position: relative;
}
.img-responsive-height {
    height: 100%;
    width: auto;
}
.media-cover, .media-cover-dark:after {
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}
.wishlist-modal .dynamic-listing-photo-container {
    height: 64px;
}
.media-photo-block {
    display: block;
}
.col-10 {
    width: 83.3333%;
    text-align: left;
}
.text-lead {
    font-size: 16px;
}
.row-space-2 {
    margin-bottom: 12.5px;
}
#panel-body
{
    padding-left: 12.5px;
    padding-right: 12.5px;
}
#panel-body:before, #panel-body:after {
    content: "";
    display: table;
    line-height: 0;
}
#panel-body:after {
    clear: both;
}
.wishlist-modal .selectContainer {
    overflow: inherit;
}
.wishlist-modal .selectContainer {
    border: 1px solid #c3c3c3;
}
.select-block {
    display: block;
    width: 100%;
}
.select {
    display: inline-block;
    position: relative;
    vertical-align: bottom;
}
.wishlist-modal #selected {
    display: block;
    height: 43px;
    line-height: 43px;
    margin-left: 20px;
    overflow: hidden;
    width: 252px;
}
.col-12 {
    width: 100%;
}
.noteContainer label {
    display: block;
    padding-bottom: 8px;
    padding-top: 9px;
}
.wishlist-note {
    line-height: inherit;
    padding-bottom: 10px;
    padding-top: 10px;
    resize: vertical;
      display: block;
    padding: 8px 10px;
    width: 100%;
}
.wishlist-modal .selectWidget {
    background-color: white;
    border: 1px solid #dce0e0;
    margin: -1px 0 0 -1px;
    position: absolute;
    width: 100%;
    z-index: 99999;
}
.wishlist-modal .selectList {
    margin: 0;
    max-height: 180px;
    overflow: auto;
    padding: 0;
}
.wishlist-modal .selectList li {
    border-bottom: 1px solid #dce0e0;
}
.wishlist-modal .selectContainer .checkbox.text-truncate {
    white-space: normal;
}
.wishlist-modal .selectList label {
    padding: 10px 15px;
}
.checkbox {
    cursor: pointer;
}
.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.wishlist-modal .selectList input {
    display: inline-block;
}
input[type="radio"], input[type="checkbox"] {
    height: 1.25em;
    margin-bottom: -0.25em;
    margin-right: 5px;
    position: relative;
    vertical-align: top;
    width: 1.25em;
}
.wishlist-modal .selectList label span {
    margin-left: 5px;
    width: 245px;
}
.wishlist-modal .newWLContainer {
    border-top: 1px solid #dce0e0;
    padding: 8px;
}
.wishlist-modal .newWLContainer .doneContainer {
    overflow: hidden;
}
.tooltip-bottom-left:before {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: rgba(0, 0, 0, 0.1) transparent -moz-use-text-color;
    border-image: none;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-style: solid solid none;
    border-width: 10px 10px 0;
    bottom: -10px;
    content: "";
    display: inline-block;
    left: 14px;
    position: absolute;
    top: auto;
}
.tooltip-bottom-left:after {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: #fff transparent -moz-use-text-color;
    border-image: none;
    border-left: 9px solid transparent;
    border-right: 9px solid transparent;
    border-style: solid solid none;
    border-width: 9px 9px 0;
    bottom: -9px;
    content: "";
    display: inline-block;
    left: 15px;
    position: absolute;
    top: auto;
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
    right: 30px;
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
    right: 29px;
    margin-left: -10px;
    position: absolute;
    top: auto;
}
  .tooltip{
    background-color: #fff;
    border-radius: 2px;
    bottom: 34px;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
    display: none;
    right: -16px;
    width: 180px;
    position: absolute;
    transition: opacity 0.2s ease 0s;
    z-index: 3000;
}
.anchor:hover + .tooltip{
    display:block !important;
    z-index:3000;
    float:left;
      opacity: 1;
    margin:190px 0px 0px 250px;
}
.hosting_address {
    margin-bottom: 15px;
}
.wishlist-modal .hide {
    border: 0 none;
    clip: rect(0px, 0px, 0px, 0px);
    height: 1px;
    margin: -1px;
    opacity: 0;
    overflow: hidden;
    padding: 0;
    pointer-events: none;
    position: absolute;
    width: 1px;
}
.wishlist-modal .selectWidget {
    background-color: white;
    border: 1px solid #dce0e0;
    margin: -1px 0 0 -1px;
    position: absolute;
    width: 100%;
    z-index: 99999;
}
.selectList li
{
	padding: 10px;
}
#new_wishlist {
    overflow:hidden;
}
#privacy-tooltip-trigger:hover + #privacy-tooltip{
    display:block !important;
    z-index:3000;
    float:left;
    opacity: 1;
    /*margin:190px 0px 0px 250px;*/
}
/*.tooltip {
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
    left: 24px;
    max-width: 280px;
    opacity: 0;
    position: absolute;
    top: -155px;
    transition: opacity 0.2s ease 0s;
    width: 149%;
    z-index: 3000;
}*/
@media screen and (-webkit-min-device-pixel-ratio:0) {
.tooltip-bottom-middle::after {
    bottom: -10px;
    content: "";
    display: inline-block;
    right: 30px;
    margin-left: -10px;
    position: absolute;
    top: auto;
  border: 10px solid transparent;
  border-bottom: 0;
  border-top-color:  #fff;
}

.tooltip-bottom-middle::before {
    bottom: -10px;
    content: "";
    display: inline-block;
    right: 29px;
    margin-left: -10px;
    position: absolute;
    top: auto;
  border: 10px solid transparent;
  border-bottom: 0;
  border-top-color: #666;
}
}

@media (max-width:767px){
	.tool-padd{padding:0px 12px !important;}
}
</style>

<script>
$(document).ready(function()
{
	$('#create_new_wishlist').click(function()
	{
		$('.modal_save_to_wishlist').show();
		$('body').css({'overflow':'none'});
	})
	
	 $('#wishlist_close').click(function()
			{
				$('body').css({'overflow':'scroll'});
				$('.modal_save_to_wishlist').hide();
			})
			$('#wishlist_save').click(function()
			{
			var dataString = 'name='+$('#wishlist_name').val()+'&privacy='+$('#private').val();
			
			if($('#wishlist_name').val() != '')
			{
				 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/wishlist_category_inner";?>',
		   data: dataString,
		   success: function(data){
		   	   		$('.wishlists-body').replaceWith(data);
		   	   		$('.modal_save_to_wishlist').hide();
				   }
		       });
		    }
		})	
})
</script>

<div id="dashboard_container">
	<div class="Box">
		<div class="Box_Content">
<div class="wishlists-container page-container"><div class="index_view">

  <div class="top-bar">
    <div class="med_6 mal_6 pe_12 col-middle img-bot">
      
        <div class="media">
  <a rel="nofollow" title="<?php echo get_user_by_id($user_id)->username;?>" class="pull-left media-photo media-round" href="<?php echo base_url().'users/profile/'.$user_id; ?>">
     
       <img width="50" height="50" alt="<?php echo get_user_by_id($user_id)->username;?>" src="<?php echo $this->Gallery->profilepic($user_id, 1); ?>">
     
  </a>
  <div class="media-body">
    <div class="wishlist-header-text">
      <div class="med_12 mal_12 pe_12 col-middle">
        <a rel="nofollow" class="h4" href="">
          <?php echo get_user_by_id($user_id)->username."'s"; ?> Wish Lists
        </a>
        <div>
        
          <span class="text-muted">Wishlists:</span>
          <strong><?php echo $wishlist_category->num_rows();?></strong>
        
        
        </div>
      </div>
    </div>
  </div>
</div>

      
    </div>
    <div class="col-middle text-right med_6 mal_6 pe_12">
      <div class="btn-group social-share-widget-container hide"></div>
      <?php
      if($this->uri->segment(2) != 'users_wishlist')
	  {
      ?>
      <div class="med_6 mal_8 pe_12 padding-zero cre-wish">
        <button class="btn_dash" id="create_new_wishlist"> Create New Wish List</button>
      </div>
      <?php 
	  }
      ?>
    </div>
  </div>

  <div class="wishlists-body">
  	
  	<?php
  	if($wishlist_category->num_rows() != 0)
  	{
  		foreach($wishlist_category->result() as $row)
  		{
  				?>
  				<div class="med_4 mal_4 pe_12">
      <div class="panel">
  <a class="panel-image media-photo media-link media-photo-block wishlist-unit" href="<?php echo base_url().'account/wishlists/'.$row->id;?>" style="text-decoration:none !important;">
  				<?php
  			$user_wishlist = $this->Common_model->getTableData('user_wishlist',array('wishlist_id'=>$row->id,'user_id'=>$user_id));	
  			
			if($user_wishlist->num_rows() != 0)
			{
				if($user_wishlist->num_rows() != 1)
				$count_status = 's';
				else
				$count_status = '';
					
				$conditions              = array('list_id' => $user_wishlist->row()->list_id,'is_featured'=>1);
			   $data['images']          = $this->Gallery->get_imagesG(NULL, $conditions);
			   
			   if($data['images']->num_rows() != 0)
			   {
			   	 $image = base_url().'images/'.$user_wishlist->row()->list_id.'/'.$data['images']->row()->name;
			   }
			   else
			   	{
			   		$image = base_url().'images';
			   	}
				?>
				
				<div style="background-image:url('<?php echo $image; ?>');" class="media-cover media-cover-dark wishlist-bg-img">
   				 </div>
				<?php
			}
			else
				{
					$count_status = '';
					?>
					<div style="background-image:url('<?php echo base_url().'images/'; ?>');" class="media-cover media-cover-dark wishlist-bg-img">
   				 </div>
					<?php
				}
  			?>
      
    <div class="row-full-height">
      <div class="med_12 mal_12 pe_12 col-middle text-center text-contrast">
        <div class="panel-body">
        	<?php
        	if($row->privacy == 1)
			{
				echo '<i class="fa fa-lock fa-2x"></i>'; 
			}
        	?>
          <div class="h2"><strong><?php echo $row->name;?></strong></div>
        </div>
        
        <div class="btn btn-guest"><?php echo $user_wishlist->num_rows();?> Listing<?php echo $count_status;?></div>
      </div>
    </div>
  </a>
</div>
      </div>
       <?php
  		}
  	}
  	?>
  </div>

</div>
</div>
</div>
</div>
</div>
</div>
<div class="modal_save_to_wishlist" style="display: none;">
	<div class="modal-table">
	<div class="modal-cell"><div class="new-modal wishlist-modal modal-content show_share_fb_checkbox">
  <div class="panel-header">
    <span class="no_fb" style="font-weight: bold;">Create New Wish List</span>
  </div>
  <div class="panel-body" style="text-align: left;">
   <input type="hidden" value="10346170" name="user_id">
    <label for="wishlist_name" style="padding-bottom: 8px;padding-top: 9px;display: block;font-size: 14px;">Wish List Name</label>
    <input type="text" id="wishlist_name" name="name" class="med_11 mal_11 pe_10">
    <label class="row-space-top-2" style="padding-bottom: 8px;padding-top: 9px;display: block;font-size: 14px;width:100%;float:left;">Who can see this?</label>
    <div>
      <div class="med_11 mal_11 pe_10 col-middle padding-zero">
        <div id="wishlist-edit-privacy-setting" class="select select-block">
          <select name="private" id="private" style=" background-color: #fff;width: 100%;">
            <option selected="" value="0">
              Everyone
            </option>
            <option value="1">
              Only Me
            </option>
          </select>
        </div>
      </div>
      <div class="med_1 mal_1 pe_2 col-middle tool-padd" style="font-size: 14px;">
         <i id="privacy-tooltip-trigger" class="fa fa-question-circle fa-1g" style="line-height: 2.3;float:right;"></i>
        <div data-trigger="#privacy-tooltip-trigger" role="tooltip" id="privacy-tooltip" class="tooltip tooltip-bottom-middle" style="" aria-hidden="true">
                        <div class="panel-body" style="padding:6px 10px;">
                          <h5>Everyone</h5>
                          <p>Visible to everyone and included on your public <?php echo $this->dx_auth->get_site_title();?> profile.</p>
                        </div>
                        <div class="panel-body" style=" border-top: 1px solid #dce0e0 !important;padding:0px 10px;">
                          <h5>Only Me</h5>
                          <p>Visible only to you and not shared anywhere.</p>
                        </div>
                      </div>
      </div>
    </div>
  </div>

  <div class="panel-footer">
    <button class="btn_dash" id="wishlist_save" type="submit">Save</button>
    <button class="btn_dash" id="wishlist_close" type="submit" >Cancel</button>
  </div>
  
</div>
</div>
</div>

</div>