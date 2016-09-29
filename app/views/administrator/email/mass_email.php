<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.validate.min.js"></script>
<script type="text/javascript">
		function startCallback() {
		var flag = 0;
		if($('#is_this').val() == 0)
		{
		 if($('#subject').val() != "" && $('#comment').val() != "")
			{
			 flag = 1;
			}
		}
		else
		{
		 if($('#email_to').val() != "" && $('#subject1').val() != "" && $('#comment1').val() != "")
			{
			  flag = 1;
			}
		}
		
		if(flag == 1)
		{
		//$("#message").html('<img src="<?php //echo base_url().'images/loading.gif' ?>">');
		// make something useful before submit (onStart)
		return true;
		}
		else
		{
		//alert("please fill the all fields.");
		return false;
		}
	}

	function completeCallback(response)
	{
if(response.length > 50 )
{
window.location.href = "<?php echo base_url().'administrator';?>";
}
else
{
	if($('#is_this').val() == 0)
		{
	$('#message').show();
	 $("#message").html(response);
		$("#message").delay(1800).fadeOut('slow');
		}
		else
		{
			$('#message1').show();
	 $("#message1").html(response);
		$("#message1").delay(1800).fadeOut('slow');
		}
}
	
	}

</script>

<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Mass E-Mail Campaigns'); ?></h1>
	 </div>
		
<div class="col-xs-9 col-md-9 col-sm-9">
<form action="<?php echo admin_url('email/mass_email'); ?>" method="post" id="form" enctype="multipart/form-data" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	
<table class="table" cellpadding="2" cellspacing="0">
	
<tr valign="top">
			<td><?php echo translate_admin('Email To'); ?><span style="color: red;">*</span></td>
			<td> 
			 <input type="radio" id="is_private" checked="checked" name="is_private" onclick="javacript:showhide(this.value);" value="0"> <?php echo translate_admin('All Users'); ?> &nbsp;
			 <input type="radio" id="" name="is_private" onclick="javacript:showhide(this.value);" value="1"> <?php echo translate_admin('Particular Users'); ?>
			 </td>
</tr>

<tr>
			<td><?php echo translate_admin('Subject'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="55" name="subject" id="subject" value=""> </td>
</tr>		

<tr>
			<td><?php echo translate_admin('Message'); ?><span style="color: red;">*</span></td>
<td>
<textarea name="message" id="comment" style="width:400px; height:100px" rows="10" cols="60" class="text_area required"></textarea>
</td>
</tr>

<tr>
		<td></td>
		<td>
		<div class="clearfix">
		<input type="submit" name="submit" value="<?php echo translate_admin('Submit'); ?>" style="width:90px;" />
		<input type="hidden" name="is_this" id="is_this" value="0" />
		</span>
		<p><div id="message"></div></p>
		</div>
		</td>
</tr>

</table>

</form>

<form action="<?php echo admin_url('email/mass_email'); ?>" method="post" id="form1" style="display: none" enctype="multipart/form-data" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	

<table class="table" cellpadding="2" cellspacing="0">
	
<tr valign="top">
			<td><?php echo translate_admin('Email To'); ?><span style="color: red;">*</span></td>
			<td> 
			 <input type="radio" name="is_private" onclick="javacript:showhide(this.value);" value="0"> <?php echo translate_admin('All Users'); ?> &nbsp;
			 <input type="radio" checked="checked" id="is_private1" name="is_private" onclick="javacript:showhide(this.value);" value="1"> <?php echo translate_admin('Particular Users'); ?>
				
				<div id="emails_private">
				<br />
					<p><?php echo translate_admin('Enter the email address separated by commas'); ?></p>
					<textarea name="emails" id="emails" style="width:300px; height:100px" rows="10" cols="60" class="text_area"> </textarea>
				</div>
			 </td>
</tr>

<tr>
			<td><?php echo translate_admin('Subject'); ?><span style="color: red;">*</span></td>
			<td> <input type="text" size="55" name="subject1" id="subject" value=""> </td>
</tr>		

<tr>
			<td><?php echo translate_admin('Message'); ?><span style="color: red;">*</span></td>
<td>
<textarea name="message1" id="comment1" style="width:400px; height:100px" rows="10" cols="60" class="text_area"></textarea>
</td>
</tr>

<tr>
		<td></td>
		<td>
		<div class="clearfix">
		<input class="clsSubmitBt1" type="submit" name="submit" value="<?php echo translate_admin('Submit'); ?>" style="width:90px;" />
		<input type="hidden" name="is_this" id="is_this" value="0" />
		<p><div id="message1"></div></p>
		</div>
		</td>
</tr>

</table>

</form>

</div>

<script language="Javascript">

jQuery("#is_private").attr('checked', 'checked');

showhide($('input[name=is_private]:checked', '#form').val());

function showhide(id)
{
	if(id == 0)
	{

	jQuery("#is_private").attr('checked', 'checked');
	
	$('#form').show();	
	$('#form1').hide();
	$('#is_this').val(0);
	
	$("#form").validate({
     rules: {
                subject: {required: true},
                comment: {required: true}
            },
     messages: {
                           subject: {required: "Please enter the subject."},
                           comment: {required: "Please enter the comment."}
               }

});
	
	}
	else
	{ 
	$('#form').hide();	
	$('#form1').show();
	
	jQuery("#is_private1").attr('checked', 'checked');
	
	$('#is_this').val(1);

function validateEmail(field) {
    var regex=/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i;
    return (regex.test(field)) ? true : false;
}
	$.validator.addMethod("multiemail", function(value, element)
{
    var result = value.split(",");
    for(var i = 0;i < result.length;i++)
    if(!validateEmail(result[i]) || result.length > 5) 
    return false;               
    return true;                                                       

},'One or more email addresses are invalid');

$("#form1").validate({
     rules: {
                emails: { required: true, multiemail: true },
                subject1: {required: true},
                message1: {required: true}
            },
     messages: {
                    emails: {
                                required: "Please enter the required field.",
                                multiemail: "Please enter the valid email id's."
                           },
                           subject1: {required: "Please enter the subject."},
                           message1: {required: "Please enter the comment."}
               }

});
	}

}
</script>
<style>
#main span #message1 p {
    background: url("<?php echo css_url();?>/images/succes_icon.png") no-repeat scroll 5px 5px #C2E6B9;
    border-radius: 15px;
    font-weight: bold;
    height: 16px;
    line-height: 16px;
    padding: 5px 10px 5px 25px;
}
	</style>