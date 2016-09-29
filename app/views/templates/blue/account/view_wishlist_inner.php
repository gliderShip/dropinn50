<!-- Required css stylesheets -->
<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />

<!-- End of stylesheet inclusion -->
<!--<?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>
<?php $this->load->view(THEME_FOLDER.'/includes/account_header'); ?>-->

<style>
.panel-overlay-listing-label {
    bottom: 30px;
    left: 0;
    padding: 7px 10px;
}
.panel-overlay-label {
    background-color: rgba(60, 63, 64, 0.898);
    color: #fff;
    padding: 10px;
}
div#map {
    border: 1px solid #fff;
    position: relative;
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
width: 33.33333%;
}
.row-space-4 {
margin-bottom: 10px;
}
.panel {
border: 1px solid #dce0e0;
background-color: #fff;
border-radius: 0;
margin-bottom: 15px !important;
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
 margin-top: 5px;
/*  margin-bottom: 38px;
width: calc(100% + 25px);*/
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
.col-top, .col-middle, .col-bottom {
/*float: none;
display: table-cell;*/
}
*, *:before, *:after, hr, hr:before, hr:after, input[type="search"], input[type="search"]:before, input[type="search"]:after {
-moz-box-sizing: border-box;
box-sizing: border-box;
}
.wishlist-unit {
height: 322px;
}
.wishlists-container.page-container.row-space-top-4 {
   /* margin-left: 20px;*/
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
width: 100%;
padding-top: 0;
}
.pull-left {
float: left;
}
.media>.pull-left {
margin-right: 5px;
padding-right:0px;
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
.icon-gray {
    color: #82888a;
}
.icon-gray:hover {
    color: #3d62b3;
}
.icon-graytw {
    color: #82888a;
}
.icon-graytw:hover{
	color: #00aced;
}
.icon-grayme {
    color: #82888a;
}
.icon-grayme:hover{
	color: #393c3d;
}
.gray
{
	text-decoration: none !important;
}
.btn-group.view-btn-group {
    margin-left: 20px;
}
.btn-group.social-share-widget-container {
    margin-top: 3px;
    vertical-align: top;
}
.row > .col-1, .row > .col-2, .row > .col-3, .row > .col-4, .row > .col-5, .row > .col-6, .row > .col-7, .row > .col-8, .row > .col-9, .row > .col-10, .row > .col-11, .row > .col-12 {
    padding-left: 12.5px;
   padding-right: 5.5px;
}
.col-9 {
    width: 75%;
    margin-bottom: 10px;
}
.row-space-2 {
    margin-bottom: 12.5px;
}
.row {
   /* margin-left: -12.5px;
    margin-right: -12.5px;*/
}
.col-8 {
    width: 56.6667%;
}
.media-body {
    display: table-cell;
    width: 999999px;
}
.row:after {
    clear: both;
}
ul.inline.spaced > li {
    margin-right: 15px;
    /*padding-bottom: 10px;*/
}
ul.inline > li {
    display: inline-block;
}
.row-space-1 {
    margin-bottom: 6.25px;
    margin-top:0px;
}
.col-3 {
    width: 25%;
}
textarea
{
	width: 100%;
}
.btn-group.social-share-widget-container {
   /* margin-top: -23px;*/
    vertical-align: middle;
}
sup {
    top: -0.5em;
    color: black;
}
h4, .h4, h5, .h5, h6, .h6 {
    font-weight: bold;
}
h5, .h5 {
    font-size: 16px;
}
.results-list .listing > label {
    border-color: transparent transparent #eee;
    border-style: solid;
    border-width: 1px;
    margin-bottom: 0;
}
.img-round, .media-round {
    border: 2px solid #fff;
    border-radius: 50%;
}
.pull-right {
    float: right;
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
    max-width: 700px;
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
.col-1, .col-2, .col-3, .col-4, .col-5, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
    float: left;
    min-height: 1px;
    position: relative;
}
.col-6
{
    float: left;
    min-height: 1px;
    position: inherit;
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
.row > .col-1, .row > .col-2, .row > .col-3, .row > .col-4, .row > .col-5, .row > .col-6, .row > .col-7, .row > .col-8, .row > .col-9, .row > .col-10, .row > .col-11, .row > .col-12 {
    padding-left: 12.5px;
    padding-right: 12.5px;
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

#panel-body {
    margin-left: -12.5px !important;
    margin-right: -12.5px !important
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
    padding-left:1px;
}
.wishlist-note {
    line-height: inherit;
    padding-bottom: 10px;
    padding-top: 10px;
    resize: vertical;
      display: block;
    padding: 8px 10px;
    width: 100%;
    margin-left:1px;
}
.wishlist-modal .selectList {
    margin: 0;
    max-height: 78px;
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
.loading:before {
    background-image: url("<?php echo base_url().'images/page2_spinner_old.gif';?>");
    content: " ";
    display: block;
    height: 33px;
    left: 50%;
    margin-left: -15px;
    margin-top: -15px;
    position: absolute;
    top: 50%;
    width: 33px;
    z-index: 10;
    background-size: 100%;
}
.loading:after {
    background-color: #fff;
    bottom: 0;
    content: " ";
    display: block;
    left: 0;
    opacity: 0.9;
    position: absolute;
    right: 0;
    top: 0;
    z-index: 9;
}
#infoBox:before {
    border-color: #fff transparent transparent;
    border-style: solid;
    border-width: 15px;
    bottom: -30px;
    content: "";
    display: inline-block;
    margin: 0 0 0 115px;
    position: absolute;
}
#infoBox {
    background: none repeat scroll 0 0 #fff;
    border-radius: 2px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    padding: 10px;
    left: 319.771px;
}
.infoBox1
{
	top: 35.232px !important;
	left: 446.771px !important;
}
.listing-map-popover {
    font-family: "Circular","Helvetica Neue",Arial,sans-serif;
    height: auto;
    margin: 0;
    width: 260px;
}
.listing-img {
    padding-bottom: 67%;
}
.panel-image {
    position: relative;
}
.img-large .wishlist-bg-img {
    background-size: contain;
}
.wishlist-bg-img {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}
.media-cover, .media-cover-dark:after {
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}
.listing-map-popover .panel-overlay-bottom-left {
    bottom: 0;
}
.panel-overlay-listing-label {
    bottom: 30px;
    left: 0;
    padding: 7px 10px;
}
.panel-overlay-label {
    background-color: rgba(60, 63, 64, 0.898);
    color: #fff;
    padding: 10px;
}
.panel-overlay-bottom-left {
   /* bottom: 15px;
    left: 15px;*/
}

.panel-overlay-top-left, .panel-overlay-top-right, .panel-overlay-bottom-left, .panel-overlay-bottom-right {
    position: absolute;
}
.listing-map-popover .panel-card-section {
    padding: 5px;
}
.panel-card-section {
    padding: 10px;
}
.panel-body {
    position: relative;
}
.panel-header, .panel-body, ul.panel-body > li, ol.panel-body > li, .panel-footer {
    /*border-top: 1px solid #dce0e0;*/
    margin: 0;
    padding: 20px;
    position: relative;
}
.media-cover, .media-cover-dark:after {
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}
.page-container {
    margin-left: auto;
    margin-right: auto;
  /*  padding-right: 25px;
    width: 1045px;*/
}
.row{
	margin-right: -12.5px !important;
}
.noteContainer, .col-12
{
margin-left: -12.5px !important;
}
.media > .pull-right {
    margin-left: 15px;
}
#privacy-tooltip-trigger:hover + #privacy-tooltip{
    display:block !important;
    z-index:3000;
    float:left;
    opacity: 1;
    display:block;
    /*margin:190px 0px 0px 250px;*/
}
.tooltip {
  background-color: #fff;
    border-radius: 2px;
    bottom: 40px;
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
    display: none;
    max-width: 280px;
    position: absolute;
    right: -20px;
    transition: opacity 0.2s ease 0s;
    width: 300px;
    z-index: 3000;
}

.tooltip-bottom-middle:after {
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
    left: 90%;
    margin-left: -9px;
    position: absolute;
    top: auto;
}
.tooltip-bottom-middle:before {
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
    left: 90%;
    margin-left: -10px;
    position: absolute;
    top: auto;
}

.socialshare {
float: right;
margin-right: 0px;
text-align: right;
}
.checkin{
	padding: 20px;
/* width: 100%; */
margin-left: 0px !important;
}

.anchor:hover + .message {
display: block !important;
z-index: 10;
float: left;
margin: 5px 0px 0px 5px;
}
.message {
display: none;
text-align: left;
color: #565a5c;
position: absolute;
top: 30px;
right: -15px;
background: #fff;
padding: 5px;
line-height: 22px;
width: 280px;
box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
}
.break{
	 word-wrap: break-word;
}
.space{
	padding-top:10px;
}
.text-center {
    text-align: right !important;
}
@media screen and (-webkit-min-device-pixel-ratio:0) {
.tooltip-bottom-middle:before {
 bottom: -10px;
    content: "";
    display: inline-block;
    left: 90%;
    margin-left: -10px;
    position: absolute;
    top: auto;
border: 10px solid transparent;
border-bottom: 0;
border-top-color: rgba(0,0,0,0.1);
}
.tooltip-bottom-middle:after {
    bottom: -9px;
    content: "";
    display: inline-block;
    left: 90%;
    margin-left: -9px;
    position: absolute;
    top: auto;
border: 9px solid transparent;
border-bottom: 0;
border-top-color: #fff;
}
}
</style>
<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>
<script>
$(document).ready(function()
{
	$('#map_view').click(function()
	{
		$('#list_view').css({'opacity':'1'});	
		$('#map_view').css({'opacity':'0.44'});
		$('#list_div').hide();
		$('#map_div').show();
		initialize();
	})
	
	$('#list_view').click(function()
	{
		$('#map_view').css({'opacity':'1'});
		$('#list_view').css({'opacity':'0.44'});
		$('#list_div').show();
		$('#map_div').hide();
	})
	
	$('textarea').focus(function()
	{
		 var id = $(this).next().attr('data');
		  
		$('#add_note_'+id).css({'opacity':'1'});
	});
	
	$('textarea').focusout(function()
	{
		$('.add-note').css({'opacity':'0'});
	});
	
	$('.row-space-top-2 label').hover(function()
	{
		$('#'+$(this).attr('for')).show();
		
	},function()
	{
		$('#'+$(this).attr('for')).hide();
	});
	
	$('.remove_listing_button').click(function()
	{
		var list_id = $(this).attr('data-hosting_id');
		
		var category_id = $(this).attr('data-category_id');
		
		 $.ajax({
		   type: "GET",
		   url: '<?php echo base_url()."rooms/remove_wishlist_inner";?>',
		   data: 'list_id='+list_id+'&category_id='+category_id,
		   success: function(data){
		   	
  				var value = $('#wishlist_count').text();
  				$('#wishlist_count').text(value-1);
    			$( "#li_"+list_id ).slideUp();
    		      				
				   }
		 });
	});
	
	add_shortlist = function(item_id,that) {
	 
		 $('body').css({'overflow':'hidden'});
		
		$.ajax({
  				url: "<?php echo site_url('rooms/wishlist_popup'); ?>",
  				type: "get",
  				data: "list_id="+item_id+"&status=account",
  				success: function(data) {
  					//alert(data);
  					$('.modal_save_to_wishlist:not(#share-via-email)').replaceWith(data);	
  					$('.modal_save_to_wishlist:not(#share-via-email)').show();
  				}
   				});		
    	};
    	
    	add_shortlist_other = function(item_id,that) {
	
		 $('body').css({'overflow':'hidden'});
		 
		 $.ajax({
      				url: "<?php echo site_url('search/login_check'); ?>",
      				async: true,
      				success: function(data) {
      				if(data == "error")
      				{
      				window.location.replace("<?php echo base_url(); ?>users/signin?account=1&id=<?php echo $this->uri->segment(3);?>");
      				}
      				else
      				{
      					$.ajax({
  				url: "<?php echo site_url('rooms/wishlist_popup'); ?>",
  				type: "get",
  				data: "list_id="+item_id+"&status=account_other",
  				success: function(data) {
  					
  					$('.modal_save_to_wishlist:not(#share-via-email)').replaceWith(data);	
  					$('.modal_save_to_wishlist:not(#share-via-email)').show();
  					
  				}
   				});
      				}
      				}
      				});
		
				
    	};
    	
    	add_shortlist_other_map = function(item_id,that) {
	
		 $('body').css({'overflow':'hidden'});
		 
		 $.ajax({
      				url: "<?php echo site_url('search/login_check'); ?>",
      				async: true,
      				success: function(data) {
      				if(data == "error")
      				{
      				window.location.replace("<?php echo base_url(); ?>users/signin?account=1&id=<?php echo $this->uri->segment(3);?>");
      				}
      				else
      				{
      					$.ajax({
  				url: "<?php echo site_url('rooms/wishlist_popup'); ?>",
  				type: "get",
  				data: "list_id="+item_id+"&status=account_other&status1=map",
  				success: function(data) {
  					
  					$('.modal_save_to_wishlist:not(#share-via-email)').replaceWith(data);	
  					$('.modal_save_to_wishlist:not(#share-via-email)').show();
  					
  				}
   				});
      				}
      				}
      				});
		
				
    	};
    	
    	
    	$( ".add-note" ).click(function( event ) {
    		    		
    		var list_id = $(this).attr('data');
    		
    		$('#add-note-form_'+list_id).addClass('loading');
    		
    		var note = $('#add-note-form_'+list_id+' textarea').val();
    		    		
    		$.ajax({
  				url: "<?php echo site_url('rooms/wishlist_note'); ?>",
  				type: "get",
  				data: "list_id="+list_id+'&wishlist_id='+<?php echo $this->uri->segment(3);?>+'&note='+note,
  				success: function(data) {
  					
  					setTimeout(function()
  					{
  						$('#add-note-form_'+list_id).removeClass('loading');
  					},1000);
  				}
   				});
    		
    	});
    	
    	$('#edit_wishlist').click(function()
    	{
    		$('#box_content').hide();
    		$('#box_content2').show();
    		$('#dashboard_container').hide();
    	})
    	
    	$('#save_wishlist').click(function()
    	{
    		$.ajax({
  				url: "<?php echo site_url('rooms/edit_wishlist'); ?>",
  				type: "get",
  				data: "name="+$('#wish-list-name').val()+'&id='+<?php echo $this->uri->segment(3);?>+'&privacy='+$('#private').val(),
  				success: function(data) {
  					window.location.href="<?php echo base_url().'account/mywishlist';?>";
  					//window.location.href="<?php //echo base_url().'account/wishlists/'.$this->uri->segment(3);?>";
  				}
   				});
    	})
    	
		$('#cancel_wishlist').click(function(){
			window.location.href="<?php echo base_url().'account/mywishlist';?>";
		})   
		
		$('#email_share').click(function()
		{
			$('#send_to').val('');
			//$("#send_to").prop('required',false);
			 $.ajax({
      				url: "<?php echo site_url('search/login_check'); ?>",
      				async: true,
      				success: function(data) {
      				if(data == "error")
      				{
      				window.location.replace("<?php echo base_url(); ?>users/signin?account=1&id=<?php echo $this->uri->segment(3);?>");
      				}
      				else
      				{
      				$('#share-via-email').show();	
      				}
      				}
      				});
		});
		
			$( "#email_share_form" ).submit(function( event ) {
								
// Stop form from submitting normally
event.preventDefault();
// Get some values from elements on the page:
var $form = $( this ),
email_id = $form.find( "input[name='send_to']" ).val(),

message = $form.find( "#email_message" ).val(),
wishlist_id = "<?php echo $this->uri->segment(3);?>",
url = $form.attr( "action" );

// Send the data using post
var posting = $.post( url, { email_id: email_id, message: message, wishlist_id: wishlist_id } );
// Put the results in a div
posting.done(function( data ) {

$('#share-via-email').hide();

});
});
		
 $('#wishlist_close').click(function()
{
	$('body').css({'overflow':'scroll'});
	$('.modal_save_to_wishlist').hide();
})

 $('#wishlist_close_share').click(function()
{
	$('body').css({'overflow':'scroll'});
	$('.modal_save_to_wishlist').hide();
})

})

function show_confirm()
    	{
    		if(confirm("Are you sure?"))
    		{
    			    		
    		$.ajax({
  				url: "<?php echo site_url('rooms/delete_wishlist'); ?>",
  				type: "get",
  				data: 'wishlist_id='+<?php echo $this->uri->segment(3);?>,
  				success: function(data) {
  					window.location.href="<?php echo base_url().'account/mywishlist';?>";
  				}
   				});

    		}
    		else
    		{
    			
    		}
    	}
      	
initialize();
function initialize() {
            	              
                var locations = [<?php echo $locations;?>];

                var myOptions = {
                    zoom: 10,
                   // center:  new google.maps.LatLng(13.03278079196330, 80.17733427583005),
                    scrollwheel: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                
                setMarkers(map,locations);
                
            }
            
            function setMarkers(map,locations){

      var marker, i
      
var bounds = new google.maps.LatLngBounds();

var infowindow = new google.maps.InfoWindow();

for (i = 0; i < locations.length; i++)
 {  

 var title = locations[i][0]
 var lat = locations[i][1]
 var long = locations[i][2]
 var address =  locations[i][3]
 var image = locations[i][4]
 var list_id = locations[i][5]
 var price = locations[i][6]
 var currency_code = locations[i][7]
 var wishlist_img = locations[i][8]

 latlngset = new google.maps.LatLng(lat, long);
  
      var content = '<div class="infoBox" style="position: absolute; visibility: visible; z-index: 330; width: 280px; left: -13.698px; bottom: -13.217px;"><div class="listing-map-popover" style="left: 497.302px; top: 200.217px;"><div class=""><div class="panel-image listing-img img-large"> <a style="background-image:url('+image+');background-size:cover;" class="media-photo media-cover wishlist-bg-img" href="<?php echo base_url();?>rooms/'+list_id+'"></a><div class="panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" style="margin-left: -15px;font-size:14px;"><sup class="h6 text-contrast"><?php echo get_currency_symbol1();?></sup><span class="h3 price-amount">'+price+'</span> <sup class="h6 text-contrast">'+currency_code+'</sup>    </div>    <div class="panel-overlay-top-right wl-social-connection-panel"> <span class="rich-toggle wish_list_button wishlist-button saved"> <label for="">  <a style="background-image:url('+wishlist_img+');background-repeat: no-repeat;right: -10px;" class="search_heart_normal search_heart" id="'+list_id+'" style="position: absolute;cursor:pointer;cursor: hand;" id="my_shortlist" onclick="add_shortlist_other_map('+list_id+',this);" href="#map_view"> </a>     </label>      </span>    </div>  </div>  <div class="panel-body panel-card-section">     <div class="media">  <a class="text-normal" href="<?php echo base_url();?>rooms/'+list_id+'" style="text-decoration:none;">         <div class="h5 listing-name text-truncate row-space-top-1" title="'+title+'">'+title+'</div>         <div class="text-muted listing-location text-truncate">'+address+'</div>       </a>     </div>   </div> </div> </div></div>'; 

 var marker = new google.maps.Marker({  
          map: map, title: content , position: latlngset  
        });
     
 		 bounds.extend(marker.position);

google.maps.event.addListener(marker,'click', function(){ 
   
        //infowindow.setContent(content);
        infowindow.setContent(this.title);
        infowindow.open(map,this);
});

  }
  //now fit the map to the newly inclusive bounds
  map.fitBounds(bounds);

//(optional) restore the zoom level after the map is done scaling
	var listener = google.maps.event.addListener(map, "idle", function () {
    map.setZoom(3);
    google.maps.event.removeListener(listener);
   });
  }
            
</script>

<style>
.gm-style-iw {
  width: 229px !important;
  height: 230px !important;
}
.gm-style-iw + div {
	display: none;
	}

.infoBox:before {
    border-color: #fff transparent transparent;
    border-style: solid;
    border-width: 15px;
    bottom: -30px;
    content: "";
    display: inline-block;
    margin: 0 0 0 115px;
    position: absolute;
}
.infoBox {
    background: none repeat scroll 0 0 #fff;
    border-radius: 2px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    padding: 10px;
}
.listing-map-popover {
    font-family: "Circular","Helvetica Neue",Arial,sans-serif;
    height: auto;
    margin: 0;
    width: 260px;
}
.listing-img {
    padding-bottom: 67%;
}
.panel-image {
    position: relative;
}
.img-large .wishlist-bg-img {
    background-size: contain;
}
.wishlist-bg-img {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}
.media-cover, .media-cover-dark:after {
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}
.listing-map-popover .panel-overlay-bottom-left {
    bottom: 0;
}
.panel-overlay-listing-label {
    bottom: 30px;
    left: 0;
    padding: 7px 10px;
}
.panel-overlay-label {
    background-color: rgba(60, 63, 64, 0.898);
    color: #fff;
    padding: 10px;
}
.panel-overlay-bottom-left {
    bottom: 15px;
    left: 15px;
}
.panel-overlay-top-left, .panel-overlay-top-right, .panel-overlay-bottom-left, .panel-overlay-bottom-right {
    position: absolute;
}
.panel-body:before {
    top: 0;
}
.listing-map-popover .panel-card-section {
    padding: 5px;
}
.panel-card-section {
    padding: 10px;
}
.panel-close:hover, .alert-close:hover, .modal-close:hover, .panel-close:focus, .alert-close:focus, .modal-close:focus {
    color: #b0b3b5;
    text-decoration: none;
}
.panel-body {
    position: relative;
}
.panel-body > *:last-child {
    margin-bottom: 0;
}
.panel-body > *:first-child {
    margin-top: 0;
}
</style>

<div id="dashboard_page" class="container_bg container">
<div id="dashboard_container">
	<div class="Box">
		<div class="Box_Content" id="box_content">
			
			<div class="top-bar">
    <div class="med_7 mal_7 pe_12 img-bot padding-zero">
      
        <div class="media">
  <a rel="nofollow" title="<?php echo $username;?>" class="pull-left media-photo media-round" href="<?php echo base_url().'users/profile/'.$user_id; ?>">
     
       <img width="50" height="50" alt="<?php echo $username;?>" src="<?php echo $this->Gallery->profilepic($user_id, 1); ?>">
     
  </a>
  <div class="media-body">
    <div class="wishlist-header-text">
      <div class="med_12 mal_12 pe_12 col-middle">
      	<?php
      	if($check_user == 0)
		{
		?>
		<a rel="nofollow" class="h4" href="<?php echo base_url().'account/users_wishlist/'.$user_id;?>">
          <?php echo $username."'s"; ?> Wish Lists
        </a>
		<?php
		}
		else
		{
		?>
		<a rel="nofollow" class="h4" href="<?php echo base_url().'account/mywishlist';?>">
          <?php echo $username."'s"; ?> Wish Lists
        </a>
		<?php
		}
      	?>
        
        <div>
          <span class="text-muted"><?php echo $wishlist_name;?>:</span>
          <strong id="wishlist_count"><?php echo $wishlists->num_rows();?></strong>
         <?php
         if($check_user == 1)
		 {
		 ?>
         <a id="edit_wishlist">Edit</a>       
        <?php 
		 } 
		 ?>
        </div>
      </div>
    </div>
  </div>
</div>

      
    </div>
    <div class="col-middle socialshare med_5 mal_5 pe_12 padding-zero">
          <div class="btn-group social-share-widget-container">

    <div class="col-middle col-middle-share socialshare" style="float:right;margin-right: 13px;">
     <?php
     if($privacy == 0)
	 {
     ?> 
<div class="btn-group social-share-widget-container">

      	<div class="social-share-widget"><span class="share-title">Share:</span>
<span class="share-triggers">
	<a class="share-btn share-facebook-btn" href="#" onclick="send_invitation1();">
		<i class="icon-gray fa fa-facebook"></i>
</a>
<a class="share-btn share-twitter-btn" href="https://twitter.com/intent/tweet?source=tweetbutton&amp;text=Wow.+I+love+this+Wish+List+on+%40<?php echo $this->dx_auth->get_site_title();?>+%23lovemywishlist&amp;url=<?php echo current_url();?>&amp;original_referer=<?php echo current_url();?>" onclick="window.open (this.href, 'child', 'height=300,width=500'); return false">
	<i class="icon-graytw fa fa-twitter"></i>
</a>
<a href="#" class="share-btn share-envelope-btn">
	<i class="icon-grayme fa fa-envelope-o fa-fw" id="email_share"></i>
</a>
</span>
</div>
</div>
      <?php } ?>
      <?php
  if($check_user == 1)
  {
  ?>
      <div class="btn-group view-btn-group">

      <button data-view="list" class="btn_dash" style="opacity: 0.44;" id="list_view">List View</button>
      <button data-view="map" class="btn_dash mapview listview_wish1" id="map_view">Map View</button>

   <!--   <a data-view="list" class="btn gray" id="list_view" style="float:left;opacity: 0.45;margin-right: 6px;" href="#list_view">List View</a>
      <a data-view="map" class="btn gray" id="map_view" style="float:left;" href="#map_view">Map View</a>-->

    </div>
      <?php
  }?>
    </div>
  </div>
</div>
</div>
    <div class="col-middle med_12 mal_12 pe_12 padding-zero top_margin">
  <?php
  if($check_user == 1)
  {
  ?>
  <ul class="results-list list-unstyled med_12 mal_12 pe_12 padding-zero" id="list_div">
  	<?php
  	if($wishlists->num_rows() != 0)
	{
		$i = 0;
		foreach($wishlists->result() as $row)
		{
			$i++;
			?>
			<script>
    // You can also use "$(window).load(function() {"
    $(function () {
      // Slideshow 4
      $("#slider<?php echo $i;?>").responsiveSlides({
        auto: false,
        pager: false,
        nav: true,
        speed: 500,
        namespace: "callbacks",
        prevText: "<i class='fa fa-angle-left slider-arrow-left' style='display:none;'></i>",
        nextText: "<i class='fa fa-angle-right slider-arrow-right' style='display:none;'></i>",
        before: function () {
          $('.events').append("<li>before event fired.</li>");
        },
        after: function () {
        	
          //$('.events').append("<li>after event fired.</li>");
        }
      });
   $('.rslides').css("cssText","overflow:visible !important");
$('.next').click(function()
{
	var value = parseInt($('.callbacks<?php echo $i;?>_on').attr('data'));
    var value1 = value-1;
    if(value1 == 0)
    {
       var value2 = $('#total_count_<?php echo $i;?>').val();
       $('#image_count_<?php echo $i;?>'+value2).removeClass('dot-dark-gray');
    }
       $('#image_count_<?php echo $i;?>'+value1).removeClass('dot-dark-gray');
       $('#image_count_<?php echo $i;?>'+value).addClass('dot-dark-gray');
})
$('.prev').click(function()
{
	var value = parseInt($('.callbacks<?php echo $i;?>_on').attr('data'));
    var value1 = value+1;
    var value2 = $('#total_count_<?php echo $i;?>').val();
    if(value1 > value)
    {
       $('#image_count_<?php echo $i;?>1').removeClass('dot-dark-gray');
    }
       $('#image_count_<?php echo $i;?>'+value1).removeClass('dot-dark-gray');
       $('#image_count_<?php echo $i;?>'+value).addClass('dot-dark-gray');
})
   
    var hashVal = window.location.hash.split("#")[1];
    
    if(hashVal == 'map_view')
    {
    	$('#map_view').css({'opacity':'0.44'});
		$('#list_view').css({'opacity':'1'});
    	$('#list_div').hide();
    	$('#map_div').show();
    }
        
    if(hashVal == 'list_view')
    {
    	$('#map_view').css({'opacity':'1'});
		$('#list_view').css({'opacity':'0.44'});
    	$('#list_div').show();
    	$('#map_div').hide();
    }
    
     });
   </script>
  	<li class="med_12 mal_12 pe_12 padding-zero" id="li_<?php echo $row->list_id;?>" style="overflow: inherit;">
  		<label for="<?php echo $row->list_id;?>" class="med_12 mal_12 pe_12 padding-zero">
  <div class="med_12 mal_12 pe_12 padding-zero">
    <div class="med_3 mal_3 pe_12">
      <div class="slideshow-container">
      	<div class="listing_slideshow_thumb_view">
 <div class="listing-slideshow">
<ul class="rslides" id="slider<?php echo $i;?>">
<?php
$conditions     = array('list_id' => $row->list_id);
$image          = $this->Gallery->get_imagesG(NULL, $conditions); 
$j = 0;
if($image->num_rows() != 0)
{
	foreach($image->result() as $image_list)
	{
		$j++;
		$name=$image_list->name;
		$pieces  = explode(".", $name);
		$image = base_url().'images/'.$image_list->list_id.'/'.$pieces[0].'_crop.jpg';
	
		?>
	<li data="<?php echo $j;?>">
	<img class="viewwish img_bor" width="216" height="144" data="<?php echo $j;?>" alt="" src="<?php echo $image;?>">
	</li>
		<?php
	}
	
}
/*else
{
	$image = base_url().'images/no_image.jpg';
}*/
		?>

</ul>
<input type="hidden" id="total_count_<?php echo $i;?>" value="<?php echo $j;?>">
<ul class="photo-dots-list list-layout" id="bullets_<?php echo $i;?>">
	<?php
	for($k=1; $k<=$j; $k++)
	{
		if($k == 1)
		$class= "dot-dark-gray";
		else
        $class = "";
		?>
<li class="photo-dots-list-item dot <?php echo $class;?>" id="image_count_<?php echo $i.$k;?>"></li>

<?php
// dot-dark-gray
	}
	
?>
</ul>
</div>


<a class="photo-paginate prev" href="javascript:void(0);"><i></i></a>
<a class="photo-paginate next" href="javascript:void(0);"><i></i></a>
</div></div>
    </div>
    <div class="med_9 mal_9 pe_12">
      <div class="room-info med_12 mal_12 pe_12 padding-zero">
        <div class="med_9 mal_9 pe_12 padding-zero">
          <h2 class="h3 row-space-1"><a href="<?php echo base_url().'rooms/'.$row->list_id;?>"><?php echo $row->title;?></a></h2>

          <p class="text-muted row-space-1">
           <?php echo $row->address; ?>
          </p>
          <ul class="list-unstyled inline spaced text-muted">
            <li><?php echo $row->type;?></li>
            <li><?php echo $row->bed_type;?> Bed</li>
            <li></li>
          </ul>
        </div>

        <div class="med_3 mal_3 pe_12 padding-zero break">
          <div class="text-center">
            <sup class="h5"></sup>
            <span class="h2 price-amount"><?php echo get_currency_value1($row->list_id,$row->price);?></span>
            <sup class="h5"><?php echo get_currency_code(); ?></sup>
            <br>
            <span class="text-muted">per night</span>
          </div>
        </div>
      </div>

      <div class="">
        <div class="media med_8 mal_8 pe_12 padding-zero space">
          
            <img width="28" height="28" class="pull-left img-round" alt="<?php echo $username;?>" src="<?php echo $this->Gallery->profilepic($user_id, 1); ?>">
          
          <!--<form class="note-container media-body text-right" id="add-note-form" method="post" action="">-->
          	<div class="note-container media-body text-right" id="add-note-form_<?php echo $row->list_id;?>">
            <input type="hidden" value="<?php echo $row->list_id;?>" name="hosting_id">
            <input type="hidden" value="<?php echo $row->wishlist_id;?>" name="id">

<!--      <textarea placeholder="Add Note" class="row-space-2" name="note"><?php echo $row->note; ?></textarea>
            <button class="btn gray add-note addnote" id="add_note_<?php echo $row->list_id;?>" data="<?php echo $row->list_id;?>" type="submit">-->

            <textarea placeholder="Add Note" class="row-space-2" name="note" <?php if($check_user == 0) echo 'readonly'; ?>><?php echo $row->note; ?></textarea>
            <?php
         if($check_user == 1)
		 {
		 ?>
            <button class="btn_dash add-note addnote" id="add_note_<?php echo $row->list_id;?>" data="<?php echo $row->list_id;?>" type="submit" style="float: right;opacity: 0;">

              
                Add Note
              
            </button>
            <?php
		 }?>
          </div>
        </div>

        <!--<div class="col-4 wishlist" style="display: none;" id="<?php echo $row->list_id;?>" >-->

        <?php
         if($check_user == 1)
		 {
		 ?>
        <div class="col-4 wishlist" style="display: none;width:43.333% !important;" id="<?php echo $row->list_id;?>" style="">

          <div class="btn-group pull-right wish_list_button_container">
            <a title="Save this listing to review later." class="wish_list_button saved btn gray wish_changes" onclick="add_shortlist(<?php echo $row->list_id;?>,this);">
               <span class="icon fa-heart"></span>
               Change
            </a>
            <a data-hosting_id="<?php echo $row->list_id;?>" data-category_id="<?php echo $row->wishlist_id;?>" class="btn gray remove_listing_button listbutton" >
              <span class="icon fa-remove"></span>
              Remove
            </a>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</label>


</li>
<?php
}
		
	}
  	?>
</ul>

</div>
</div>
</div>
<div class="results-map row-space-8 mapwish" id="map_div" style="display: none;" data-map-container="" >
 <body onLoad="initialize()">
        <div id="map" class="mapful">
            <div id="map_canvas" class="mapcanvas"></div>

<?php
}
?>
<!--<div class="results-map row-space-8" id="map_div" style="display:none;" data-map-container="" style="position: relative; background-color: rgb(229, 227, 223);height: 100%;">
 <body onLoad="initialize()">
        <div id="map" style="width:100%; height:100%" >
            <div id="map_canvas" style="width:100%; height:420px"></div>-->

            <div id="crosshair"></div>
        </div>
        </body>
</div>

</div>
<div class="" id="box_content2" style="display:none;">
<div class="wishlists-container page-container row-space-top-4">
	<div class="edit_view row-space-8">
		<div class="row-space-4 med_12 mal_12 pe_12 padding-zero">
  <div class="med_12 mal_12 pe_12 padding-zero">
    <div class="media wishlist_in">
     
      <a title="<?php echo $username;?>" class="media-photo media-round pull-left" href="<?php echo base_url().'users/profile/'.$user_id; ?>">
         <img width="50" height="50" alt="<?php echo $username;?>" src="<?php echo $this->Gallery->profilepic($user_id, 1); ?>">
      </a>
     
      <div class="media-body">
        <div class="row-table wishlist-header-text">
          <div class="col-middle">
            <span>Edit Wish List:</span>
            <strong></strong>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>


<div class="wishlists-body med_12 mal_12 pe_12 padding-zero">
  <div class="wishlist_main med_4 mal_4 pe_12">
    <div class="panel">
  <a class="panel-image media-photo media-link media-photo-block wishlist-unit" href="<?php echo base_url();?>account/wishlists/<?php echo $this->uri->segment(3);?>" style="display:block !important;">
  	<?php
  			$user_wishlist = $this->Common_model->getTableData('user_wishlist',array('wishlist_id'=>$this->uri->segment(3),'user_id'=>$user_id));	
  			$wishlist_category = $this->Common_model->getTableData('wishlists',array('id'=>$this->uri->segment(3),'user_id'=>$user_id));
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
			   	   	$name=$data['images']->row()->name;
				$pieces = explode(".", $name);
			   	 $image = base_url().'images/'.$user_wishlist->row()->list_id.'/'.$pieces[0].'_crop.jpg';
			  
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
  <!--<div style="background-image:url(<?php echo base_url();?>);" class="media-cover media-cover-dark wishlist-bg-img">
    </div>-->

    <div class="row-table row-full-height med_12 col-sx-12 pe_12">
      <div class="col-12 col-middle text-center text-contrast checkin">
        <div class="panel-body">
          <?php
        	if($wishlist_category->row()->privacy == 1)
			{
				echo '<i class="fa fa-lock fa-2x"></i>'; 
			}
        	?>
          <div class="h2"><strong><?php echo $wishlist_name;?></strong></div>
        </div>
        <div class="btn btn-guest"><?php echo $wishlists->num_rows();?> Listing</div>
      </div>
    </div>
  </a>
</div>

  </div>
  <div class="med_8 mal_8 pe_12">
      <div class="row-space-2 med_12 mal_12 pe_12 padding-zero">
        <div class="med_12 mal_12 pe_12 padding-zero">
          <div class="panel">

            <div class="panel-body list_panel">

          <!--  <div class="panel-body" style="padding-top:16px;padding-bottom:90px;box-shadow: 0 3px 3px 0 rgba(0, 0, 0, 0.15);">-->

              <div class="med_12 mal_12 pe_12 padding-zero">
                <div class="med_6 mal_6 pe_12 wishmain">
                  <label for="wish-list-name wishlistname" style="display: block;">Title</label>
                  <input type="text" value="<?php echo $wishlist_name;?>" name="wishlist[name]" id="wish-list-name" class="wishlist_name" style="display: block;">
                </div>
                <div class="med_6 mal_6 pe_12 listbutton padding-zero">
                  <label for="wishlist-edit-privacy-setting" style="display: block;">Who can see this?</label>
                  <div class="padding-zero">
                    <div class="pull-right question-icon-container">
                      <i id="privacy-tooltip-trigger" class="fa fa-question-circle fa-1g" style="line-height: 2.3;padding-left:10px;"></i>

                      <div data-trigger="#privacy-tooltip-trigger" role="tooltip" id="privacy-tooltip" class="tooltip tooltip_edit tooltip-bottom-middle"  aria-hidden="true">

                   <!--   <div data-trigger="#privacy-tooltip-trigger" role="tooltip" id="privacy-tooltip" class="tooltip_edit tooltip-bottom-middle" style="" aria-hidden="true">-->

                        <div class="panel-body">
                          <h5>Everyone</h5>
                          <p>Visible to everyone and included on your public <?php echo $this->dx_auth->get_site_title();?> profile.</p>
                        </div>
                        <div class="panel-body onlylist" >
                          <h5>Only Me</h5>
                          <p>Visible only to you and not shared anywhere.</p>
                        </div>
                      </div>
                    </div>
                    <div class="media-body">
                      <div id="wishlist-edit-privacy-setting" class="select select-block">

                        <select name="wishlist[private]" id="private" class="everyone" style="display: block;">

                        <!--<select name="wishlist[private]" id="private" style="display: block;padding: 7px 10px;border-radius:2px !important;width: 100%;background-color: #FFFFFF">-->

                          <option value="0" <?php if($wishlist_details->privacy == 0) echo 'selected';?>>
                            Everyone
                          </option>
                          <option value="1" <?php if($wishlist_details->privacy == 1) echo 'selected';?>>
                            Only Me
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="med_12 mal_12 pe_12 padding-zero">
        <div class="med_12 mal_12 pe_12 padding-zero">
          <button class="btn_dash" type="submit" id="save_wishlist">Save Changes</button>
          <button class="btn_dash" id="cancel_wishlist">Cancel</button>
          <a class="delete pull-right deletlist" onclick='show_confirm();'>Delete Wish List</a>
        </div>
      </div>
  </div>
</div>

</div>
</div>
<div class="clearfix"></div>
</div>
</div>
</div>

<div class="modal_save_to_wishlist" style="display: none;">
</div>

<div class="modal_save_to_wishlist" id="share-via-email" style="display: none;">
	<div class="modal-table">
	<div class="modal-cell">
		<div class="new-modal wishlist-modal modal-content show_share_fb_checkbox" style="max-width: 520px;">
  <div class="panel-header">
   <span class="no_fb">Share Wish List</span>
    <a class="panel-close" data-behavior="modal-close" id="wishlist_close_share" href="#">×</a>
  </div>
   <form id="email_share_form" action="	<?php echo base_url().'account/email_share';?>">
   
        <div class="panel-body" style="margin-bottom: 15px;">
          <p>
            <label for="send_to" class="email_label">Send to:</label>
            <input type="email" placeholder="Enter up to three emails (comma-separated)" class="input-large input-block" value="" multiple="" name="send_to" id="send_to" pattern="^([\w+-.%]+@[\w-.]+\.[A-Za-z]{2,4},*[\W]*)+$" required="required"/>
          </p>
          <span class="share-error"></span>
          <p>
            <label for="email_message" class="email_label">Personal Message:</label>
            <textarea rows="3" name="message" id="email_message">Check out this cool wish list on <?php echo $this->dx_auth->get_site_title();?>!</textarea>
          </p>
        </div>
<div class="panel-footer">
    <button class="blue gotomsg btn" id="wishlist_send_email" type="submit">Send Email</button>
  </div>
</form> 

</div>
</div>
</div>
</div>

<div id="" role="dialog" class="modal" aria-hidden="false" style="display:none;">
  <div class="modal-table">
    <div class="modal-cell">
      <div class="modal-content">
        <div class="panel-header">
          <a data-behavior="modal-close" class="panel-close" href="#"></a>
          Share Wishlist
        </div>
             </div>
    </div>
  </div>
</div>
<?php
if($check_user == 0)
{
	?>
<div class="wishlists-body">
  <div class="row wishlists-list">
  <?php
  	if($wishlists->num_rows() != 0)
	{
		foreach($wishlists->result() as $row)
		{
  			$conditions              = array('list_id' => $row->list_id,'is_featured'=>1);
			$data['images']          = $this->Gallery->get_imagesG(NULL, $conditions);
			   
			if($data['images']->num_rows() != 0)
			{
			   $image = base_url().'images/'.$row->list_id.'/'.$data['images']->row()->name;
			}
			else
			{
			   $image = base_url().'images';
			}
	?>
	<style>
	.col-middle-share
	{
	margin-top:18px;
	}
	</style>
    <div class="col-6 row-space-4">
      <div class="">
  <div class="panel-image listing-img img-large">
    <a style="background-image:url(<?php echo $image;?>);background-size: cover;" class="media-photo media-cover wishlist-bg-img" href="/rooms/281390">
    </a>
    <div class="panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label">
      <sup class="h6 text-contrast" style="font-size: 14px;"><?php echo get_currency_symbol1();?></sup>
      <span class="h3 price-amount" style="font-size: 24px;"><?php echo get_currency_value1($row->list_id,$row->price);?></span>
      <sup class="h6 text-contrast" style="font-size: 14px;"><?php echo get_currency_code();?></sup>
    </div>
    
    <div class="panel-overlay-top-right wl-social-connection-panel">
      <span class="rich-toggle wish_list_button wishlist-button not_saved">
       
        <label for="">
    		<?php
    		
    		$wishlist_user_check = $this->Common_model->getTableData('user_wishlist',array('list_id'=>$row->list_id,'user_id'=>$this->dx_auth->get_user_id()));
			
    		if(!$this->dx_auth->is_logged_in())
			{
    		?>
    		<img class="search_heart_normal search_heart" id="<?php echo $row->list_id;?>" style="position: absolute;cursor:pointer;cursor: hand;" src="<?php echo base_url() ?>images/search_heart_hover.png" value="Save To Wish List" id="my_shortlist" onclick="add_shortlist_other(<?php echo $row->list_id;?>,this);">
		    <?php
			} 
			else if($wishlist_user_check->num_rows() != 0)
			{
			?>
			<img class="search_heart_hover search_heart" id="<?php echo $row->list_id;?>" style="position: absolute;cursor:pointer;cursor: hand;" src="<?php echo base_url() ?>images/heart_rose.png" value="Saved to Wish List" id="my_shortlist"  onclick="add_shortlist_other(<?php echo $row->list_id;?>,this);">	
			<?php 
			}
			else {
			?>
			<img class="search_heart_normal search_heart" id="<?php echo $row->list_id;?>" style="position: absolute;cursor:pointer;cursor: hand;" src="<?php echo base_url() ?>images/search_heart_hover.png" value="Save To Wish List" id="my_shortlist" onclick="add_shortlist_other(<?php echo $row->list_id;?>,this);">
			<?php	
			}
			?>
			<!--<img class="search_heart_hover search_heart" style="position: absolute;cursor:pointer;cursor: hand;" src="<?php echo base_url() ?>images/heart_rose.png" value="Saved to Wish List" id="my_shortlist"  onclick="add_shortlist(1,this);">-->
		
        </label>
      </span>
    </div>
  </div>
  <div class="panel-body panel-card-section" style="padding: 10px;">
    <div class="media">
      <a title="Sunil" class="pull-right media-photo media-round card-profile-picture card-profile-picture-offset" href="/users/1466805/wishlists">
        <img height="60" width="60" alt="Sunil" src="<?php echo $this->Gallery->profilepic($user_id, 1); ?>">
      </a>
      <a class="text-normal" href="/rooms/281390" style="text-decoration: none;">
        <div class="h5 listing-name text-truncate row-space-top-1" title="5 Star Budget homestay B&amp;B, Mumbai">
         <?php echo $row->title; ?>
        </div>
        <div class="text-muted listing-location text-truncate"><?php echo $row->state.', '.$row->country; ?></div>
      </a>
    </div>
  </div>
</div>

    </div>
<?php
}
}
?>
  </div>

</div>
<?php
}
?>
<script>
function send_invitation1() {
    var winTop = (screen.height / 2) - (winHeight / 2);
    var winLeft = (screen.width / 2) - (winWidth / 2);
    var title = 'test';
    var descr = 'test_desc';
    var url = "<?php echo current_url();?>";
    var image = "<?php echo current_url();?>";
    var winWidth = '500';
    var winHeight = '300';
    
    window.open('http://www.facebook.com/sharer.php?s=100&p[title]=' + title + '&p[summary]=' + descr + '&p[url]=' + url + '&p[images][0]=' + image, 'sharer', 'top=' + winTop + ',left=' + winLeft + ',toolbar=0,status=0,width=' + winWidth + ',height=' + winHeight);
    }
</script>


   <style>
   .results-map.row-space-8 {
    margin-top: 10px;
}
.search_heart_hover:hover {
	opacity: 1;
	}   
.search_heart_hover {
    float: right;
    opacity: 0.2;
    padding: 10px;
    position: absolute;
    transform: translateZ(0px);
    transition: opacity 0.3s ease-out 0s;
}
.search_heart_normal:hover
{
   opacity: 1;
}
.search_heart_normal 
{
    float: right;
    opacity: 0.2;
    padding: 10px;
    position: absolute;
    transform: translateZ(0px);
    transition: opacity 0.3s ease-out 0s;
}
   .panel-overlay-top-right {
    right: 15px;
    top: 15px;
}
.wishlist-button .search_heart {
    padding: 22px;
    position: absolute;
    right: 0;
    top: -7px;
}
   .col-6 {
    float: left;
    width: 50%;
}
.col-6.row-space-4 > div {
    width: 460px;
}
.row-space-4 {
display: block !important;
}
.card-profile-picture-offset {
    margin-bottom: -40px;
    position: relative;
    top: -40px;
}
   .listing-img {
    padding-bottom: 71%;
}
.panel-image {
    position: relative;
}
.img-large .wishlist-bg-img {
    background-size: contain;
}
.wishlist-bg-img {
    background-position: center center;
    background-repeat: no-repeat;
    background-size: cover;
}
.media-cover, .media-cover-dark:after {
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
}
.media-photo {
    backface-visibility: hidden;
    background-color: #cacccd;
    overflow: hidden;
   /* 
     display: inline-block;
     position: relative;*/
    vertical-align: bottom;
}
  /* [type="email"]:invalid {
    background-color: #fff8e5;
    border-color: #ffb400;
}*/
   textarea {
    line-height: inherit;
    padding-bottom: 10px;
    padding-top: 10px;
    resize: vertical;
    border-radius: 0;
}
/*[type="text"], [type="password"], [type="search"], [type="email"], [type="url"], [type="tel"], textarea, select, .input-prefix, .input-suffix {
    display: block;
    padding: 8px 10px;
    width: 100%;
}*/
	.email_label {
    display: block;
    padding-bottom: 8px;
    padding-top: 9px;
}
.input-large {
    font-size: 15px;
    padding-bottom: 10px;
    padding-top: 10px;
}
.input-large {
    border-radius: 5px;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 0 #000;
    color: #000;
}
#street, .input-large {
    width: 100%;
}
   .rslides
   {
   	overflow:none !important;
   }
   .callbacks
   {
   	overflow:none !important;
   }
   .callbacks_nav
   {
   	/*margin-top:0px !important;
   	height:129px !important;
   	width:100px !important;
   	background: none !important;
   	text-indent: 0 !important;
   	text-decoration: none !important;
   	background: url("images/slide_arrow.png") no-repeat scroll left top transparent;*/
height: 40px;
left: 0;
margin-top: -28px;
opacity: 0.7;
overflow: hidden;
position: absolute;
text-decoration: none;
text-indent: -9999px;
top: 52%;
width: 38px;
z-index: 3;
margin-left: 10px;
   }
   .next{
   /*	margin: 0px 12px 0 0 !important;*/
  margin-right: 10px;
  top:49% !important; 
   }
   .slider-arrow-left {
    font-size: 50px;
    margin-top: 32px;
    margin-left: 12px;
    color: white !important;
    }
    .slider-arrow-right {
    font-size: 50px;
    margin-top: 32px;
    float: right;
    color: white !important;
    }
    .prev:hover > i
    {
      display:block !important;	
    }
    .next:hover > i
    {
      display:block !important;	
    }
    .dot-dark-gray {
background-color: #3c3f40 !important;
}
.show_view .photo-dots-list {
bottom: -20px;
}
.photo-dots-list {
/*width: 206px;
bottom: -161px;
height: 25px;*/
}
.photo-dots-list {
/*position: absolute;*/
text-align: center;
z-index: 99;
}
.list-layout, .subnav-list, .sidenav-list {
margin-bottom: 0;
}
.photo-dots-list-item {
margin: 3px 2px;
font-size: 10px;
}
.list-unstyled, .list-layout, .subnav-list, .sidenav-list {
padding-left: 0;
list-style: none;
}
.dot {
display: inline-block;
-webkit-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
border-radius: 50%;
height: 10px;
width: 10px;
background-color: #cacccd;
}
.top_margin{
	margin-top:10px;
}
.listview_wish1{
	margin-right:10px;
}
.img_bor{
	border:1px solid #E3E3E3 !important;
}
.wishlist_name{
	width:100%;
}
@media (min-width:320px) and (max-width:767px){
.socialshare {
float: left;
margin-right: 0px;
text-align: left;
}
}
</style>

