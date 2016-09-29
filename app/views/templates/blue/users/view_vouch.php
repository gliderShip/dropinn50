<link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url().'/combine.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<style>
	.Box_Content h2{
		font-size:20px !important;}
		.Box_Content p{
			padding-left:10px;
		}
		.address-list{
			padding-left:6px;
		}
		.Box_Content h2 {
font-size: 20px !important;
line-height: 25px;
/* width: 100%; */
overflow: hidden;}
.verification_list > li{
	word-wrap:break-word;
	overflow: auto;
	padding-bottom:10px;height:65px;
}
</style>
<script>
$(document).ready(function()
{
	$('#button_recom').click(function()
	{
		var message = $('#recommend').val();
		 if(/\b(\w)+\@(\w)+\.(\w)+\b/g.test(message))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
            else if(message.match('@') || message.match('hotmail') || message.match('gmail') || message.match('yahoo'))
				{
					alert('Sorry! Email or Phone number is not allowed');return false;
				}
         	if(/\+?[0-9() -]{7,18}/.test(message))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
	})
})
</script>
<script type="text/javascript">
// Current Server Time script (SSI or PHP)- By JavaScriptKit.com (http://www.javascriptkit.com)
// For this and over 400+ free scripts, visit JavaScript Kit- http://www.javascriptkit.com/
// This notice must stay intact for use.

//Depending on whether your page supports SSI (.shtml) or PHP (.php), UNCOMMENT the line below your page supports and COMMENT the one it does not:
//Default is that SSI method is uncommented, and PHP is commented:

var currenttime = '<?php echo date("F d, Y H:i:s", get_user_time(local_to_gmt(),get_user_timezoneL($this->uri->segment(3)))); ?>' //PHP method of getting server date

///////////Stop editting here/////////////////////////////////

var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var serverdate=new Date(currenttime)

function padlength(what){
var output=(what.toString().length==1)? "0"+what : what
return output
}

function displaytime(){
serverdate.setSeconds(serverdate.getSeconds()+1)
var datestring=montharray[serverdate.getMonth()]+" "+padlength(serverdate.getDate())+", "+serverdate.getFullYear()
var timestring=padlength(serverdate.getHours())+":"+padlength(serverdate.getMinutes())+":"+padlength(serverdate.getSeconds())
document.getElementById("show_date_time").innerHTML="<b>"+datestring+"</b>"+"&nbsp;<b>"+timestring+"</b>";
}

window.onload=function(){
setInterval("displaytime()", 1000)
}

</script>

<style>
#left {
float:left;
}
#main {
    float: right;
}
#main p {
padding:0 0 10px 0;
}
.clsH1_long_Border h1 {
background:#F4F4F4;
font-size:15px;
padding:5px 10px;
margin:0 0 10px 0;
position:relative;
}
.clsH1_long_Border h1 span {
position:absolute;
right:10px;
top:5px;
}
.page-container
{
	margin-left: auto;
    margin-right: auto;
    padding-left: 25px;
    padding-right: 25px;
}
.row-space-top-4 {
    margin-top: 25px;
}
.row {
    margin-left: -12.5px;
    margin-right: -12.5px;
}
.col-12 {
    width: 100%;
}
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
    float: left;
    min-height: 1px;
    position: relative;
}
.col-6 {
    width: 43%;
}
textarea {
    line-height: inherit;
    padding-bottom: 10px;
    padding-top: 10px;
    resize: vertical;
    padding-left: 7px;
   /*padding-right: 78px;*/
   width: 94%;
}

.icon-caret-down:before, .select:before {
    content: "";
}
.select:before {
    display: none;
}
.select:before {
    bottom: 1px;
    color: #82888a;
    font-family: Airglyphs,sans-serif;
    font-size: 1em;
    line-height: 1;
    padding-top: 0.7em;
    pointer-events: none;
    position: absolute;
    right: 0;
    text-align: center;
    top: 0;
    width: 2em;
}
.select select {
    padding-bottom: 7px;
    padding-right: 50px;
    padding-top: 7px;
    background-color: #fff;
     width: 100%;
}
.panel {
    background-color: #fff;
    border: 1px solid #dce0e0;
    border-radius: 0;
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
    border-top: 1px solid #dce0e0;
    margin: 0;
    padding: 20px;
    position: relative;
}
.panel-body > *:last-child {
    margin-bottom: 0;
}
.panel-body > *:first-child {
    margin-top: 0;
}
.row {
    margin-left: -12.5px;
    margin-right: -12.5px;
}
.col-5 {
    width: 45.667%;
}
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
    float: left;
    min-height: 1px;
    position: relative;
}
.col-7 {
    width: 54.333%;
}
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12 {
    float: left;
    min-height: 1px;
    position: relative;
}
p {
    margin-bottom: 15px;
    margin-top: 0;
}
.tips_ul {
    margin-bottom: 15px;
    margin-top: 10px;
    padding-left: 25px;
}
.tips_ul > li{
	list-style-type: circle !important;
	float: left;
	text-align: left !important;
}
.media > .pull-left {
    margin-right: 15px;
}
.pull-left {
    float: left;
}
.media-photo {
    backface-visibility: hidden;
    background-color: #cacccd;
    display: inline-block;
    overflow: hidden;
    position: relative;
    vertical-align: bottom;
}
.img-round, .media-round {
    border: 2px solid #fff;
    border-radius: 50%;
}
.media-body {
    display: table-cell;
    width: 999999px;
}
.media-body:before, .media-body:after {
    content: " ";
    display: table;
}
.media-body:after {
    clear: both;
}
.panel-quote.panel-dark:before, .panel-quote.panel-header:before {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: transparent #dce0e0 transparent -moz-use-text-color;
    border-image: none;
    border-style: solid solid solid none;
    border-width: 10px 10px 10px 0;
    content: "";
    display: inline-block;
    left: -10px;
    position: absolute;
    right: auto;
    top: 15px;
}
.panel-quote:before {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: transparent #dce0e0 transparent -moz-use-text-color;
    border-image: none;
    border-style: solid solid solid none;
    border-width: 10px 10px 10px 0;
    content: "";
    display: inline-block;
    left: -10px;
    position: absolute;
    right: auto;
    top: 15px;
}
.panel-quote.panel-dark:after, .panel-quote.panel-header:after {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: transparent #edefed transparent -moz-use-text-color;
    border-image: none;
    border-style: solid solid solid none;
    border-width: 9px 9px 9px 0;
    content: "";
    display: inline-block;
    left: -9px;
    position: absolute;
    right: auto;
    top: 16px;
}
.panel-quote:after {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    border-color: transparent #fff transparent -moz-use-text-color;
    border-image: none;
    border-style: solid solid solid none;
    border-width: 9px 9px 9px 0;
    content: "";
    display: inline-block;
    left: -9px;
    position: absolute;
    right: auto;
    top: 16px;
}
.panel-quote {
    margin-left: 10px;
    position: relative;
}
.row:before, .row:after {
    content: " ";
    display: table;
}
.panel-body > *:first-child {
    margin-top: 0;
}
.row-space-2 {
    margin-bottom: 12.5px;
}
.row {
    margin-left: -12.5px;
    margin-right: -12.5px;
}
.row:before, .row:after {
    content: " ";
    display: table;
}
.panel-header + .panel-body, .panel-body + .panel-body, ul.panel-body > li + .panel-body, ol.panel-body > li + .panel-body, .panel-footer + .panel-body {
    border-top: medium none;
}
.panel-body {
    position: relative;
}
.panel-header, .panel-body, ul.panel-body > li, ol.panel-body > li, .panel-footer {
    border-top: 1px solid #dce0e0;
    margin: 0;
    padding: 20px;
    position: relative;
}
.row:after {
    clear: both;
}
.row:before, .row:after {
    content: " ";
    display: table;
}

.media:before, .media:after {
    content: " ";
    display: table;
}
.media:after {
    clear: both;
}
h4
{
margin-bottom: 10px;
    float: left;
     font-size: 18px;
}
@media screen and (-webkit-min-device-pixel-ratio:0) {

.panel-quote.panel-dark:before, .panel-quote.panel-header:before {
content: "";
display: inline-block;
position: absolute;
left: -10px;
top: 15px;
right: auto;
border: 10px solid transparent;
border-left: 0;
border-right-color: #dce0e0;
}

.panel-quote:before {
content: "";
display: inline-block;
position: absolute;
left: -10px;
top: 15px;
right: auto;
border: 10px solid transparent;
border-left: 0;
border-right-color: #dce0e0;
}

.panel-quote.panel-dark:after, .panel-quote.panel-header:after {
content: "";
display: inline-block;
position: absolute;
left: -9px;
top: 16px;
right: auto;
border: 9px solid transparent;
border-left: 0;
border-right-color: #edefed;
}

.panel-quote:after {
content: "";
display: inline-block;
position: absolute;
left: -9px;
top: 16px;
right: auto;
border: 9px solid transparent;
border-left: 0;
border-right-color: #fff;
}
</style>

<!-- End of style sheet inclusion -->
<?php 

$ref_id_check = $this->Common_model->getTableData('users',array('ref_id'=>$this->input->get('id'),'id'=>$this->uri->segment(3)));

if($ref_id_check->num_rows() != 0)
{
	$ref_id = 1;
}
else
	{
		$ref_id = 0;
	}

$recommends_edit_check = $this->Common_model->getTableData('recommends',array('userto'=>$this->uri->segment(3)));

if($recommends_edit_check->num_rows() != 0)
{
	 $date1 = new DateTime(date('Y-m-d H:i:s', $recommends_edit_check->row()->created));
$date2 = new DateTime(date('Y-m-d H:i:s'));
$interval = $date1->diff($date2);
$days = 15 - $interval->days;
if($days >= 0)
{
	$recommends_edit_check = 1;
}
else
	{
		$recommends_edit_check = 0;
	}
}
else
	{
		$recommends_edit_check = 0;
	}
if( ($this->dx_auth->is_logged_in()) && $this->uri->segment(3) != $this->dx_auth->get_user_id())
{
	if($reference_request == 1 || $ref_id == 1 || $recommends_edit_check == 1)
	{
	 ?>
<div class="clearfix container" id="recommendation_container"  >
<div class="page-container">
  <div class="row row-space-top-4">
    <div class="col-12">
      <form method="post" id="recommendation_form" class="edit_friend_recommendation" action="<?php echo base_url().'users/vouch/'.$this->uri->segment(3);?>" accept-charset="UTF-8"><div style="margin:0;padding:0;display:inline"><input type="hidden" value="✓" name="utf8"><input type="hidden" value="put" name="_method">
      <input type="hidden" name="userto" value="<?php echo $this->uri->segment(3); ?>">
      <input type="hidden" name="userby" value="<?php echo $this->dx_auth->get_user_id(); ?>">
      <div class="panel">
        <div class="panel-header">
        	<?php
        	if($recommends_check->num_rows() != 0)
			{
				echo 'Edit';
			}
			else {
				echo 'Add';
			}
            ?> your reference about <?php echo ucfirst($user->username); ?>
        </div>

        <div class="panel-body">
          <div class="row">
            <div class="col-5">
              <p align="left">
                <?php echo $this->dx_auth->get_site_title(); ?> is built on trust and reputation.
                By writing this reference, you are recommending Venkatesh to other members of the <?php echo $this->dx_auth->get_site_title(); ?> community.

                <strong>
                  You should only provide references for people who know you well.
                </strong>
                <a href="<?php echo base_url();?>home/help/18">
                  Learn more about References on <?php echo $this->dx_auth->get_site_title(); ?>
                </a>
              </p>
              <h4>Tips for a helpful reference:</h4>
              <br>
              <ul class="tips_ul">
              	<br>
                <li>
                  How do you know each other?
                </li>
                <br>
                <li>
                  What style of traveler or <?php echo $this->dx_auth->get_site_title(); ?> host is this person?
                </li>
                <li>
                  Tell a story about an experience you've had together.
                </li>
                <li>Why do you recommend this person to other<?php echo $this->dx_auth->get_site_title(); ?> members?
                </li>
              </ul>
            </div>
            <div class="col-7">
              <div class="media">
                <div class="pull-left media-photo media-round">
                  <img width="68" height="68" src="<?php echo $this->Gallery->profilepic($this->dx_auth->get_user_id(), 2); ?>" alt="Original">
                </div>
                <div class="media-body">
                  <div class="panel panel-quote panel-dark">
                    <div class="panel-body">
                      <div class="row row-space-2">
                        <div class="col-6">
                          <label for="friend_recommendation[relationship_type]">
                            Your relationship to this person
                          </label>
                        </div>
                        <div class="col-6">
                          <div class="select select-block">
                          	    <?php
        	if($recommends_check->num_rows() != 0)
			{
				$type = $recommends_by_you->row()->relationship;
			}
			else
				{
					$type = '';
				}	
				?>
                            <select name="relationship_type" id="relationship_type">
                            <option value="0" <?php if($type == 0) echo 'selected'; ?>>Friend</option>
							<option value="1" <?php if($type == 1) echo 'selected'; ?>>Travel Buddy</option>
							<option value="2" <?php if($type == 2) echo 'selected'; ?>>Colleague</option>
							<option value="3" <?php if($type == 3) echo 'selected'; ?>>Family Member</option></select>
                          </div>
                        </div>
                      </div>
                      <?php
        	if($recommends_check->num_rows() != 0)
			{
				$textarea = $recommends_by_you->row()->message;
			}
			else
				{
					$textarea = '';
				}	
				?>
                      <textarea rows="5" id="recommend" name="message" placeholder="Write your reference..." cols="40"><?php echo $textarea; ?></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="panel-footer">
          <a href="#" class="cancel-link" style="margin-right: 10px;">
            Cancel
          </a>
          <?php
        	if($recommends_check->num_rows() != 0)
			{
				$status =  'Edit';
			}
			else {
				$status =  'Create';
			}
            ?>
            <input type="submit" value="<?php echo $status;?> Reference" onclick="return validate_recommendation();" name="commit" id="update-button" class="btn blue gotomsg">
        </div>
      </div>
</form>    </div>
  </div>
</div>

<script>
  (function($) {
    var $update = $("#update-button").attr("disabled", "disabled");
    var enableSubmit = function() {
      $update.removeAttr("disabled");
      $update = null;
      enableSubmit = function() {};
    };

    $("#recommendation_container select").one("change", enableSubmit);
    $("#recommendation_container form").one("keyup", enableSubmit);
  })(jQuery);

  function validate_recommendation() {
    if (jQuery("#friend_recommendation_recommendation").val() === "") {
      alert('Please write a reference first!');
      return false;
    }
  }
</script>
</div>

<?php } 
}?>
<div class="container" style="margin-bottom:30px;">
<div class="container_bg" id="View_Vouch">

  <div id="dashboard" class="clsDes_Top_Spac">

    <div style="clear:both"></div>
    <div id="left" class="med_3 mal_3 pe_12">
      <div id="user_box" class="Box">
          <div class="Box_Content">
            <div id="user_pic" onClick="show_ajax_image_box();" style="text-align:center; padding:0 0 10px 0;"> 
												
			<img  src="<?php echo $this->Gallery->profilepic($user->id, 2); ?>" />
           </div>
          </div>
          <!-- middle -->
      </div>
      <!-- /user -->
       <style>
       .img-responsive, .thumbnail > img, .thumbnail a > img, .carousel-inner > .item > img, .carousel-inner > .item > a > img
       {
       	border-radius:3px;
       }
    .list1
    {
    	color: #999999;
    	 font-size: 12px;
    font-weight: bold;
    }
    </style>
    <?php
if($user->facebook_verify == 'yes' || $user->google_verify == 'yes' || $user->email_verify == 'yes' || $user->phone_verify == 'yes')
	{
    ?>
     <div class="Box" id="quick_links">
      <div class="Box_Head msgbg">
        <h2 class="mybox-header"><?php echo translate("Verifications");?> </h2>
        <a class="add_more" href="<?php echo base_url().'users/verify'?>"><?php echo translate("Add more"); ?></a></div>
      
      <div class="Box_Content" style="padding: 20px;height:auto;">
        <ul class="verification_list">
        	<?php 
        	if($user->facebook_verify == 'yes')
			{
				$url = 'https://graph.facebook.com/fql?q=SELECT%20friend_count%20FROM%20user%20WHERE%20uid%20='.$user->fb_id;
                            $json = file_get_contents($url);
               $count = json_decode($json, TRUE);	
                        
        	?>
          <li class="verifications-list-item" ><b><?php echo translate("Facebook");
		   ?><span style="padding:4px 6px 0px 0px;"><img src="<?php echo base_url();?>images/follow-us-facebook-plus.png" /></span></b>
		  <?php foreach($count['data'] as $row)
                        { 
                            echo '<p class="list1">'.$row["friend_count"].' '.'Friends</p>';
                        }?></li>
          
          <?php
			}
			?>
         <?php 
        	if($user->google_verify == 'yes')
			{
        	?>
          <li class="verifications-list-item"><b><?php echo translate("Google"); ?><span><img src="<?php echo base_url();?>images/follow-us-google-plus.png" /></span></b><p class="list1">Verified</p></li>
          
          <?php
			}
			?>
			<?php 
        	if($user->email_verify == 'yes')
			{
        	?>
          <li class="verifications-list-item"><b><?php echo translate("Email"); ?><span><img src="<?php echo base_url();?>images/follow-us-email-plus.png" /></span></b><p class="list1">Verified</p></li>
          
          <?php
			}
			?>
			
			<?php 
        	if($user->phone_verify == 'yes')
			{
        	?>
          <li class="verifications-list-item"><b><?php echo translate("Phone Number"); ?><span></span></b><p class="" style="color: #82888a;">▒▒▒▒▒▒▒▒▒▒ <?php echo $phone_number;?></p></li>
          
          <?php
			}
			?>
			
        </ul>
      </div>
      <div style="clear:both"></div>
    </div>
    <?php } ?>
       <div class="Box" id="quick_links">
      <div class="Box_Head msgbg">
        <h2 class="mybox-header"><?php echo translate("About Me");?> </h2>
        </div>
      <div class="Box_Content" style="padding: 20px;height:115px;">
        <ul class="verification_list">
        <li><?php if($profile->describe != '') echo $profile->describe.'<br>'; ?><li>
        	<?php if($profile->work != '')
			{?>
        <li><?php echo '<br><strong>Work </strong><br>'.$profile->work; ?><li>
        	<?php } ?>
        <li>
        	<?php
        	if($profile->language != '')
			{ echo '<strong>Languages</strong><br>';
				$language = explode(',',$profile->language);
				$i = 0;
				foreach($language as $row)
				{
					$i++;
					echo $this->Common_model->getTableData('language',array('id'=>$row))->row()->name;
					if($i != count($language))
					{
					echo ', ';	
					}
				}
			}
        	?>
        </li>
        </ul>
      </div>
      <div style="clear:both"></div>
    </div>
    </div>
    <!-- /left -->
    <div id="main" class="med_9 mal_9 pe_12">				 	
      <div class="Box">
      	<div class="Box_Content">
        <h2 style=""> <?php echo "Hey, I'm".' '.ucfirst($user->username).'!'; ?><span class="Box_Head_Right new"  id="show_date_time"></span></h2>
        	<p style="margin-top: 14px;padding-bottom: 0;padding-left:0px;word-wrap: break-word;"><?php echo $profile->live.' - '; ?>
        				
								<?php echo translate("Member since"); ?> <?php echo date('F Y',$user->created); ?></p> 
								<?php
								if($this->dx_auth->get_user_id() == $user->id)
								{
								 ?> 
								<a href="<?php echo base_url().'users/edit';?>">Edit Profile</a>
								<?php } ?>
      		</div>
      		
      		</div>
      		<?php //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) ){
					 	if( ($this->dx_auth->is_logged_in()) && $this->uri->segment(3) != $this->dx_auth->get_user_id()){ ?> 
      		<div class="Box" style="display: none">
      <div class="Box_Head msgbg">
              <h2><?php echo translate("Reference for"); ?> <?php echo ucfirst($user->username);?></h2>
            </div>
          <div class="Box_Content">
            <?php echo form_open('users/vouch/'.$this->uri->segment(3)); ?>
            <p style="font-weight:normal; font-style:italic; font-size:16px;"> <?php echo translate("Please write a few sentences explaining why"); ?> &nbsp;<?php echo ucfirst($user->username);?> &nbsp;<?php echo translate("is a great person."); ?> </p>
            <p><?php echo translate("Enter your reference here and then click the Reference button."); ?> </p>
            <input type="hidden" name="userto" value="<?php echo $this->uri->segment(3); ?>">
            <input type="hidden" name="userby" value="<?php echo $this->dx_auth->get_user_id(); ?>">
            <p>
              <textarea id="recommend" name="message" cols="75"> </textarea>
            </p>
			  <span style="color:#FF0000"><?php echo form_error('message'); ?></span>
            <p>
              <button name="friends_recommend" class="btn blue gotomsg" id="button_recom" type="submit"><span><span><?php echo translate("Reference"); ?></span></span></button>
            </p>
            <?php echo form_close();?> 
												</div>
      </div>
						<?php } ?>
      <!--List-->
      <div class="Box">
      <div class="Box_Head msgbg">
            <h2><?php echo translate("My Listing"); ?></h2>
          </div>
        <div class="Box_Content" style="padding:10px 0px 10px 0px;">
          
          <div id="user_result_list" width="100%">
           
   <?php
	  if($lists->num_rows() > 0)
	  {
		 foreach($lists->result() as $list)
			{
				?>

								<ul class="even med_12 pe_12 mal_12 padding-none" id="room_<?php echo $list->id; ?>">
									
										<li class="place_image med_2 mal_3 pe_4 padd-zero thumb-size21"><a class="thumbnail med_12 mal_12 pe_12" href="<?php echo base_url().'rooms/'. $list->id; ?>"><img style="width:100px;height:70px;" title="Test room" src="<?php echo getListImage($list->id); ?>" alt="Test room"><span class="med_12 pe_12"><img  style="width:120px;height:100px;"  title="Test room" src="<?php echo getListImage($list->id); ?>" alt="Test room"></span></a> </li>
										<li class="main med_8 mal_8 pe_8 thumb-size12"><div class="first-line title"><a class="profile" href="<?php echo base_url().'rooms/'. $list->id; ?>"><?php echo $list->title;?></a></div>
												<div class="address-list"><?php echo $list->address; ?></div></li>
							
									</ul>

						<!--		<tr class="even" id="room_<?php echo $list->id; ?>">
										<td class="place_image imgwidth"><a class="thumbnail" href="<?php echo base_url().'rooms/'. $list->id; ?>"><img width="75" height="50" title="Test room" src="<?php echo getListImage($list->id); ?>" alt="Test room"><span><img width="100" height="100" title="Test room" src="<?php echo getListImage($list->id); ?>" alt="Test room"></span></a> </td>
										<td class="main"><div class="first-line title"><a href="<?php echo base_url().'rooms/'. $list->id; ?>"><?php echo $list->title;?></a></div>
												<div><?php echo $list->address; ?></div></td>
									</tr>-->

									<?php }	} else { echo translate("There is no List"); } ?>
           
          </div>
        </div>
      </div>
      <!--List-->
      <!--Recommendation-->
        <div class="Box">
        <div class="Box_Head msgbg">
        	<h2><?php echo translate("References"); ?></h2>
        </div>
								
        <div class="Box_Content">
            
			<table style="width:100%;" class="quotes" id="vouch_recom_tab">
					<tbody>
					<?php 
					$i = 0;
					if($recommends->num_rows() > 0)
					{
						foreach($recommends->result() as $row)
						{
							$date1 = new DateTime(date('Y-m-d H:i:s', $row->created));
							$date2 = new DateTime(date('Y-m-d H:i:s'));
							$interval = $date1->diff($date2);
							$days = 15 - $interval->days;
							if($days <= 0)
							{
							if($this->db->where('id',$row->userby)->get('users')->num_rows()!=0)
							{
					?>
						<tr>
								<td width="13%" style="vertical-align: top;">
                                <div class="review_prof_img" style="text-align: center;">
                                <a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>">
                                	<img width="76" height="76" title="<?php echo get_user_by_id($row->userby)->username; ?>" src="<?php echo $this->Gallery->profilepic($row->userby, 1);  ?>" alt="<?php echo get_user_by_id($row->userby)->username; ?>"></a>
                                	<a target="blank" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a>
                                </div>
										 </td>
								<td valign="top" width="80%">
                                <div class="review_right_content">
														<?php echo $row->message;?>
                                                        <span class="review_right_arrow"></span>
										</div>
                                        </td>
						</tr>
 <?php }
							$i++;
							}
						}
if($i == 0)
{
	echo '<p>'.translate("There is no Reference").'</p>';
}
 } else {  echo '<p>'.translate("There is no Reference").'</p>'; } ?>
              </tbody>
            </table>
          </div>
        </div>
         <!--Recommendation-->
        <!-- Review -->
            <div class="Box">
        <div class="Box_Head msgbg">
        	<h2><?php echo translate("Reviews About You"); ?></h2>
        </div>
								
        <div class="Box_Content">
            
			<table style="width:100%;" class="quotes" id="vouch_recom_tab">
					<tbody>
					<?php 
					if($reviews->num_rows() > 0)
					{
						foreach($reviews->result() as $row)
						{
							if($this->db->where('id',$row->userby)->get('users')->num_rows()!=0)
							{
					?>
						<tr>
								<td width="13%" style="vertical-align: top;padding-bottom: 10px;">
                                <div class="review_prof_img">
                                <a href="<?php echo site_url('users/profile').'/'.$row->userby; ?>">
                                	<img width="76" height="76" title="<?php echo get_user_by_id($row->userby)->username; ?>" src="<?php echo $this->Gallery->profilepic($row->userby, 1);  ?>" alt="<?php echo get_user_by_id($row->userby)->username; ?>"></a>
                                	<a target="blank" href="<?php echo site_url('users/profile').'/'.$row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a>
                                </div>
										 </td>
								<td valign="top" width:"80%">
                                <div class="review_right_content">
														<?php echo $row->review;?>
                                                        <span class="review_right_arrow"></span><br><br>
                                                       <span class="review_date" style="color:#AAAAAA;"><?php echo date('F Y',$row->created);?></span>
										</div>
                                        </td>							
						</tr>
						
 <?php }
						} } else {  echo '<p>'.translate("There is no Review").'</p>'; } ?>
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
      <!--Review--> 
    <!-- /main -->
    <div class="clear"></div>
  </div>
  <!-- /dashboard -->
</div><!-- /command_center -->
</div>

</div>