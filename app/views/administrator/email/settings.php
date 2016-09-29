<html>
<head></head>

 <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
				
			echo $msg;
		}
	  ?>

<script type="text/javascript">	

		/*function startCallback() {
			$("#message").html('<img src="<?php// echo base_url().'images/loading.gif' ?>">');
		// make something useful before submit (onStart)
	 /*	return true;
	} 

	function completeCallback(response)
	{
		$('#message').show();
	 $("#message").html(response);
		$("#message").delay(1800).fadeOut('slow');    check
	}*/
	
	
	$(document).ready(function()
	   {     
		 <?php
      if($mailer_type == 1)
      {
      	   ?>
	    $('#text').hide();  
                 $('#text1').hide();
                 $('#text2').hide();
                //  $('#text3').hide();  
                 $('#text4').hide();
                 $('#text5').hide();
                 $('#text6').hide(); 
                 $('#text7').hide(); 
                 $('#text8').hide();
	 <?php
	  } else  if($mailer_type == 3)
	  {
        ?>
         //$('#text3').hide();  
                 $('#text4').hide();
                 $('#text5').hide();
                 $('#text6').hide(); 
                 $('#text7').hide(); 
                 $('#text8').hide();
        
        <?php 
	  } else  if($mailer_type == 5)
	  {
        ?>
                      $('#text').hide();  

                 $('#text1').hide();
                 $('#text2').hide();
                 
               
        
        <?php } ?>
	  
		
	    $('#mailer_type').change(function()
         { //alert($('#mailer_type').val());
             if($('#mailer_type').val() == 2 || $('#mailer_type').val() == 3 )      
            {     
                  $('#text').show();  
                 $('#text1').show();
                 $('#text2').show();
                //   $('#text3').hide();  
                 $('#text4').hide();
                 $('#text5').hide();
                 $('#text6').hide(); 
                 $('#text7').hide(); 
                 $('#text8').hide();
            	
            }     
            else if($('#mailer_type').val() == 5  )    
            {     
              $('#text').hide();  
                 $('#text1').hide();
                 $('#text2').hide();
              // $('#text3').show();  
                 $('#text4').show();
                 $('#text5').show(); 
                 $('#text6').show(); 
                 $('#text7').show(); 
                 $('#text8').show();
            }
            else
            {
            	 $('#text').hide();  
                 $('#text1').hide();
                 $('#text2').hide();
                 // $('#text3').hide();  
                 $('#text4').hide();
                 $('#text5').hide();
                 $('#text6').hide(); 
                 $('#text7').hide();
                 $('#text8').hide(); 
            	
            }
          
          })
      });	
</script>
<script src="<?php echo base_url(); ?>js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>js/jquery.validation.js" type="text/javascript"></script>	
<script type="text/javascript">
/*$( document ).ready(function() {
 	$("#mySelect").validate({
        rules: 
	        {
		         'smtp_port':
				 { 
				   required:true,
				  },
				  'smtp_user':
				  {
				  	required:true,
				  },
				  'smtp_pass':
				  {
				  	required:true,
				  },
			},
		 messages:
		       {
			       	'smtp_port':
			       	{
			       		 required:"Please enter your SMTP Port",
			        },	 
			       	'smtp_user':
			       	{
			       		required:"Please enter your SMTP Username",
			       	},	
			       	'smtp_pass':
			       	{
			       		required:"Please enter your SMTP Password", 
			       	},
			     },  	
			errorClass:'error_msg',
	    errorElement: 'div',
	    errorPlacement: function(error, element)
	    {
		error.appendTo(element.parent());
	    },
	   
	    submitHandler: function()
	    {
		document.mySelect.submit();
	    }
	    });
	  });  */		
	
</script>	  


<div id="Email_Setting">
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('E-Mail Settings'); ?></h1>
	 </div>
		
<form  id="mySelect" action="<?php echo admin_url('email/settings'); ?>" method="post" enctype="multipart/form-data" >
		
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
	
	<tr valign="top">
			<td><?php echo translate_admin('Email Mode'); ?></td>
			<td> 
			 <select name="mailer_mode" id="mailer_mode"  >
							<option value="html"> <?php echo translate_admin('HTML Mode');?> </option>
							<option value="text"> <?php echo translate_admin('Plain Text Mode');?> </option>
				</select> 
			 </td>
</tr>
	
	
<tr valign="top">
			<td><?php echo translate_admin('Mailer Type'); ?></td>
			<td> 
			 <select name="mailer_type" id="mailer_type">
							
					<option value="1"<?php echo set_select('mailer_type', '1',true); ?> <?php if($mailer_type ==1 ) echo 'selected'; ?>   > <?php echo translate_admin('PHP Mail Function');?> </option>
					<option value="3" <?php echo set_select('mailer_type', '3',true); ?> <?php if($mailer_type ==3 ) echo 'selected'; ?>  > <?php echo translate_admin("Google's SMTP Server");?> </option>
					<option value="5" <?php echo set_select('mailer_type', '5',true); ?> <?php if($mailer_type ==5 ) echo 'selected'; ?>  > <?php echo translate_admin("Domain SMTP Server");?> </option>

				</select> 
			 </td>
</tr>

 
<tr id="text">
			<td><?php echo translate_admin('SMTP Port'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="23" name="smtp_port" id="smtp_port"   value="<?php if(isset($smtp_port)) echo $smtp_port; ?>" ;><span class="clsRed"><?php echo form_error('smtp_port'); ?></span> </td>
</tr>		

<tr id="text1">
			<td><?php echo translate_admin('SMTP Username'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="23" name="smtp_user" id="smtp_user"  value="<?php if(isset($smtp_user)) echo $smtp_user; ?>" ;> <span class="clsRed"><?php echo form_error('smtp_user'); ?> </span></td>
</tr>	

<tr id="text2">
			<td><?php echo translate_admin('SMTP Password'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="23" name="smtp_pass" id="smtp_pass"   value="<?php if(isset($smtp_pass)) echo $smtp_pass; ?>";> <span class="clsRed"><?php echo form_error('smtp_pass'); ?> </span></td>
</tr> 
<!--<tr id="text3">
			<td class="clsName"><?php echo translate_admin('Domain Name'); ?><span style="color: red;">*</span></label></td>
			<td> <input type="text" size="23" name="smtp_domain" id="smtp_domain"   value="<?php if(isset($smtp_domain)) echo $smtp_domain; ?>";> <span class="clsRed"><?php echo form_error('smtp_domain'); ?> </span></td>
</tr>-->
<tr id="text4">
			<td class="clsName"><?php echo translate_admin('Incoming Mail Server'); ?><span style="color: red;">*</span></label></td>
			<td> <input type="text" size="23" name="smtp_income" id="smtp_income"   value="<?php if(isset($smtp_income)) echo $smtp_income; ?>";> <span class="clsRed"><?php echo form_error('smtp_income'); ?> </span></td>
</tr>
<tr id="text5">
			<td class="clsName"><?php echo translate_admin('Out Going Mail Server'); ?><span style="color: red;">*</span></label></td>
			<td> <input type="text" size="23" name="smtp_outgo" id="smtp_outgo"   value="<?php if(isset($smtp_outgo)) echo $smtp_outgo; ?>";> <span class="clsRed"><?php echo form_error('smtp_outgo'); ?> </span></td>
</tr>
<tr id="text6">
			<td class="clsName"><?php echo translate_admin('Mail User Name'); ?><span style="color: red;">*</span></label></td>
			<td> <input type="text" size="23" name="smtp_uname" id="smtp_uname"   value="<?php if(isset($smtp_uname)) echo $smtp_uname; ?>";> <span class="clsRed"><?php echo form_error('smtp_uname'); ?> </span></td>
</tr>
<tr id="text7">
			<td class="clsName"><?php echo translate_admin('MailP Password'); ?><span style="color: red;">*</span></label></td>
			<td> <input type="text" size="23" name="smtp_upass" id="smtp_upass"   value="<?php if(isset($smtp_upass)) echo $smtp_upass; ?>";> <span class="clsRed"><?php echo form_error('smtp_upass'); ?> </span></td>
</tr>
<tr id="text8">
			<td class="clsName"><?php echo translate_admin('SMTP Port'); ?><span style="color: red;">*</span></label></td>
			<td> <input type="text" size="23" name="smtp_do_port" id="smtp_do_port"   value="<?php if(isset($smtp_do_port)) echo $smtp_do_port; ?>";> <span class="clsRed"><?php echo form_error('smtp_do_port'); ?> </span></td>
</tr>  
					   
					   
						
						  <!--	<option value="1" <?php if($mailer_type ==1 ) echo 'selected'; ?> > <?php echo translate_admin('PHP Mail Function');?> </option>
							<option value="2" <?php if($mailer_type ==2) echo 'selected';?>> <?php echo translate_admin("ISP's SMTP server");?> </option>
							<option value="3" <?php if($mailer_type ==3) echo 'selected';?> > <?php echo translate_admin("Google's SMTP Server");?> </option>
				</select> 
			 </td>
</tr>


<tr id="text">
			<td class="clsName"><?php echo translate_admin('SMTP Port'); ?></td>
			<td> <input type="text" size="23" name="smtp_port" id="smtp_port"   value="<?php if(isset($smtp_port)) echo $smtp_port; ?>" ;><?php echo form_error('smtp_port'); ?> </td>
</tr>		

<tr id="text1">
			<td class="clsName"><?php echo translate_admin('SMTP Username'); ?></td>
			<td> <input type="text" size="23" name="smtp_user" id="smtp_user"  value="<?php if(isset($smtp_user)) echo $smtp_user; ?>" ;> <?php echo form_error('smtp_user'); ?> </td>
</tr>	

<tr id="text2">
			<td class="clsName"><?php echo translate_admin('SMTP Password'); ?></td>
			<td> <input type="text" size="23" name="smtp_pass" id="smtp_pass"   value="<?php if(isset($smtp_pass)) echo $smtp_pass; ?>";> <?php echo form_error('smtp_pass'); ?> </td>
</tr>!-->

<tr>
		<td></td>
		<td>
		<div class="clearfix">
		<input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
		<p><div id="message"></div></p>
		</div>
		</td>
</tr>

</table>

</form>
</div>

<script language="Javascript">
$("#mailer_type").val('<?php echo $mailer_type; ?>');

$("#mailer_mode").val('<?php echo $mailer_mode; ?>');


</script>
</html>