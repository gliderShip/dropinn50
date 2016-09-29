<html>
	<head>
		
	</head>
		<body>
		    <link href="<?php echo css_url().'/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
				 <!-- End of stylesheet inclusion -->
				  <?php $this->load->view(THEME_FOLDER.'/includes/dash_header'); ?>
				
				    <?php $this->load->view(THEME_FOLDER.'/includes/account_header'); ?>
		   <form name="setting" method="post" action="<?php echo base_url().'users/cancel_account'; ?>" >
		    <div id="dashboard_container" class="clearfix">
		    	<div class="Box med_12 mal_12 pe_12 padding-zero" id="hide_div">
		    		<div class="Box_Head msgbg">
		    			<h2>
		    				Cancel My Account
		    			</h2>
		    		</div>
		    		<div style="padding:15px 0px;">
		    			<input type="submit" id="cancel_account" class="btn_dash finish setting" name="cancel_account" value="Cancel account"/>
		    		</div>
		    	</div>
				     	
					       <div id="cancel_account1" class="Box med_12 mal_12 pe_12 ">	
						      <h2 class="setting_head">Tell us why you're leaving</h2>
										
									<div class="row-space-2 med_12 mal_12 pe_12" style="padding:0px 15px">
								             <label for="reason_safety_concerns" class="med_12 mal_12 pe_12 padding-zero"><br>
								                     <input name="reason" type="radio" value="User have safety concerns" />
								                      I have safety concerns.<br />
								               </label>
								               <label for="reason_privacy_concerns" class="med_12 mal_12 pe_12 padding-zero"><br>
								                     <input  name="reason"  type="radio" value="User have privacy concerns" />
								                     I have privacy concerns.<br />
								                </label>
								                <label for="reason_not_useful" class="med_12 mal_12 pe_12 padding-zero"><br>
								                     <input name="reason" type="radio" value="User don't find it useful" />
								                     I don't find it useful.<br />
								                </label>
								               <label for="reason_dont_understand_how_to_use" class="med_12 mal_12 pe_12 padding-zero"><br>
								                      <input  name="reason" type="radio" value="User don't understand how to use it" />
								                      I don't understand how to use it.<br />
								               </label>
								               <label for="reason_temporary" class="med_12 mal_12 pe_12 padding-zero"><br>
								                     <input name="reason"  type="radio" value="It's temporary; User will be back"  />
								                     It's temporary; I'll be back.<br />
								                      </label>
								               <label for="reason_other" class="med_12 mal_12 pe_12 padding-zero"><br>
								                      <input name="reason"  type="radio" value="Other" />
								                      Other
								                      </label>           
								     </div>
								     <div class="row-space-2 med_12 mal_12 pe_12 " style="padding:15px 15px 20px">
												   <label for="details" class="med_12 mal_12 pe_12 padding-zero">
												   	   <br>Care to tell us more?<br><br>
												   </label>
												   <textarea class="input-block med_5 mal_5 pe_12 padding-zero" id="details" cols="50" rows="7" name="details" value=""></textarea>
								      </div>
							          <div class="row-space-2" style="padding:0px 15px;">
											              <label style="margin-bottom:8px;">
											                 Can we contact you for more details?
											              </label><br />
											              <label class="label-inline" for="reason_yes">
											                 <input name="contact_ok" type="radio" value="Yes" /> Yes
											             </label>
											             <label class="label-inline" for="reason_no">
											                 <input  name="contact_ok" type="radio" value="No" /> No
											             </label>
								      </div>
								      <h2 class="setting_head" style="padding:5px 15px">
								              What's going to happen
								      </h2>
								       <ul class="row-space-4" style="padding:0px 15px;margin-left:15px;">
									   <li>
									   	   Your profile and any listings will disappear.
									   </li>
									   <li>
									              We'll miss you terribly.
									   </li><br />
								       </ul>
								       <!--   <button type="submit" class="btn btn-primary">Cancel my account</button>-->
								     <!--  <button onclick="location.href='<?php echo base_url();?>users/logout'">Cancel my account</button> -->
								        <!-- <button onClick="return redirect('<?php echo base_url();?>users/cancel_account');">Cancel my account</button>
								        -->
								         <button id="cancel_my_account"   type="submit"  class="cancel_account set_but btn_dash finish" >Cancel my account</button>
								       
								          <button class="set_but btn_dash finish" type="button" id="cancel-cancellation-button">Don't cancel account</button>
				</div>	
        </form>
                  
        </div> 
         
     </body>
	
</html>

<script src="<?php echo base_url(); ?>js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript">
	 // $("#cancel_account1").hide();
	 jQuery("#cancel_account").click(function()
        {
         // alert('success');
          $("#hide_div").hide();
          $("#cancel_account1").show();
          return false;
         }); 
       jQuery("#cancel_account1").hide(); 
        
    jQuery("#cancel-cancellation-button").click(function()
      {
       $("#hide_div").show();
       $("#cancel_account1").hide();
      });  
      
        /*   jQuery('#cancel_my_account').click(function() {
			  	
		          var Reason 	= encodeURIComponent($('input[name=reason]:checked').val());
		          var Contact 	= encodeURIComponent($('input[name=contact_ok]:checked').val());
		          var Details	= encodeURIComponent($.trim($("#details").val()));
		          
	             var userid =?php echo $this->dx_auth->get_user_id(); ?>;

		  	   		jQuery.ajax({
						 url: "?php echo base_url()?>users/cancel_account",    
					     type: "POST",
					     data:"user_id="+userid+"&reason="+Reason+"&details="+$('#details').val()+"&contact_ok="+Contact,
						 success: function(Result)  {
						 		alert("Your account has been deleted successfully"); 
			  	              //  window.location.reload();
			  	          }
	  	        });  
		          
            	
          });
       */
    /*   jQuery("#cancel_my_account").click(function()
      {
       alert('Do not cancel unless the admin refund your amount');
      });*/  
      
</script>
<style type="text/css">
#cancel_account1
{
   /*float: left;
    width:1500px;
   position: relative;
  */
}
#description
{
  margin-top:-550px;
  float: right;
  width: 500px;	
}
.setting_head
{
	padding:20px 10px 5px;
	font-size:18px;
}
ul.row-space-4 li{
	list-style:disc !important;
}

/*button,#cancel_account
{
	padding:6px 10px 10px !important;
	box-shadow:none;
	margin:5px 10px;
	color:#FFFFFF !important;
	background-color:#0199EB;
	border-radius:6px;
} 
button:hover, #cancel_account:hover
{
	background:#0199EC;
	opacity:0.84;
}*/
.cancel_div
{
/*margin: 15px;*/
border: 1px solid #ABA9A9;
}
.cancel_div1
{
padding:15px;
border-bottom: 1px solid #ABA9A9;
background:#f9f9f9;
}
.set_but{
	margin-bottom:10px;
}
</style>



