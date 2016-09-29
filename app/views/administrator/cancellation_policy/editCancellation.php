<script type="text/javascript" src="<?php echo base_url()?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		elements : "elm1",
		 plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

		theme_advanced_toolbar_align : "left",
		theme_advanced_toolbar_location : "top",
		skin : "o2k7",
        skin_variant : "silver",
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
	 theme_advanced_statusbar_location : "bottom",
	 theme_advanced_resizing : true

	});
</script>

    <div class="Edit_Page">
      <?php
		//Show Flash Message
		if($msg = $this->session->flashdata('flash_message'))
		{
			echo $msg;
		}		
	  ?>
	  <?php
	  	//Content of a group
		if(isset($cancellations) and $cancellations->num_rows()>0)
		{
			$cancellation = $cancellations->row();
	  ?>
	 	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 		<h1 class="page-header1"><?php echo translate_admin('Edit Cancellation Policy'); ?></h1></div>
			<form method="post" id="editCancellation"  action="<?php echo admin_url('cancellation/editCancellation')?>/<?php echo $cancellation->id;  ?>">
   <div class="col-xs-9 col-md-9 col-sm-9">
   <table class="table" cellpadding="2" cellspacing="0">
			
		  		<tr>
					<td class="clsName"><?php echo translate_admin('Cancellation Policy Name'); ?><span style="color: red;">*</span></td>
					<td>
					<input class="" type="text" name="name" value="<?php echo $cancellation->name; ?>">
					<p><?php echo form_error('name'); ?></p>
					</td>
				</tr>				
   <tr>
				<td class="clsName"><?php echo translate_admin('Cancellation Policy Title'); ?><span style="color: red;">*</span></td>
				<td>
					<input class="" type="text" name="cancellation_title" value="<?php echo $cancellation->cancellation_title; ?>">
				<p><?php echo form_error('cancellation_title'); ?></p>
				</td>
			</tr>
		
	  <tr>
				<td class="clsName"><?php echo translate_admin('Cancellation Policy Content'); ?><span style="color: red;">*</span></td>
				<td class="clsNoborder">
				<textarea id="elm1" name="cancellation_content" rows="15" cols="80" style="width: 80%"><?php echo $cancellation->cancellation_content;?></textarea>
				<p><?php echo form_error('cancellation_content');?></p>
				</td>
			</tr>
			
			<tr>
				<td class="clsName"><b><?php echo translate_admin('Cleaning Fee'); ?></b><span class="clsRed"></span></td>
				
			</tr>
		
			<tr class="cleaning">
				<td class="clsName"><?php echo translate_admin('After Checkin Is Refundable'); ?>?<span class="clsRed"></span></td>
				<td class="clsNoborder">
				<select name="cleaning_refund">
					<option value="1" <?php if($cancellation->cleaning_status == 1) echo 'selected'; ?>>Yes</option>
					<option value="0" <?php if($cancellation->cleaning_status == 0) echo 'selected'; ?>>No</option>
				</select>
				</td>
			</tr>
		
			<tr>
				<td class="clsName"><b><?php echo translate_admin('Security Fee'); ?></b><span class="clsRed"></span></td>
				
			</tr>
			
			<tr class="security">
				<td class="clsName"><?php echo translate_admin('After Checkin Is Refundable'); ?>?<span class="clsRed"></span></td>
				<td class="clsNoborder">
				<select name="security_refund">
					<option value="1" <?php if($cancellation->security_status == 1) echo 'selected'; ?>>Yes</option>
					<option value="0" <?php if($cancellation->security_status == 0) echo 'selected'; ?>>No</option>
				</select>
				</td>
			</tr>
			
			<tr>
				<td class="clsName"><b><?php echo translate_admin('Additional Guest Fee'); ?></b><span class="clsRed"></span></td>
				
			</tr>
			
			<tr class="additional">
				<td class="clsName"><?php echo translate_admin('After Checkin Is Refundable'); ?>?<span class="clsRed"></span></td>
				<td class="clsNoborder">
				<select name="additional_refund">
					<option value="1" <?php if($cancellation->additional_status == 1) echo 'selected'; ?>>Yes</option>
					<option value="0" <?php if($cancellation->additional_status == 0) echo 'selected'; ?>>No</option>
				</select>
				</td>
			</tr>
			<tr>
				<td class="clsName"><b><?php echo translate_admin('List Fee'); ?></b><span class="clsRed"></span></td>
				
			</tr>
			
			<tr>
				<td class="clsName"><?php echo translate_admin('Days Prior'); ?><span class="clsRed"></span></td>
		
				<td class="clsName"><?php echo translate_admin('Is Refundable'); ?>?
				<br/>
				<select name="list_days_prior_status" onchange="javascript:show('list_before_days_prior',this.value);">
					<option value="1" <?php if($cancellation->list_days_prior_status == 1) echo 'selected'; ?>>Yes</option>
					<option value="0" <?php if($cancellation->list_days_prior_status == 0) echo 'selected'; ?>>No</option>
				</select>
				</td>
			</tr>
			<tr class="list_before_days_prior">
				<td class="clsName"></td>
				<td class="clsNoborder">
				Days<br>
				
					<select name="list_days_prior_days">
						<?php
						for($i=1; $i<31;$i++)
						{
							?>
						<option value="<?php echo $i;?>" <?php if($cancellation->list_days_prior_days == $i) echo 'selected'; ?>><?php echo $i;?></option>	
							<?php
						} 
						?>
					</select>
									
				</td>
			</tr>
			<tr class="list_before_days_prior">
				<td>
				</td>
				<td>
				Percentage<span class="clsRed">*</span><br>
				<input type="text" name="list_days_prior_percentage" value="<?php echo $cancellation->list_days_prior_percentage;?>">									
				<p><?php echo form_error('list_days_prior_percentage');?></p>
				</td>
			</tr>
			
			<tr>
				<td class="clsName"><?php echo translate_admin('Before Checkin'); ?><span class="clsRed"></span></td>
		
				<td class="clsName"><?php echo translate_admin('Is Refundable'); ?>?
				<br/>
				<select name="list_refund_before" onchange="javascript:show('list_before',this.value);">
					<option value="1" <?php if($cancellation->list_before_status == 1) echo 'selected'; ?>>Yes</option>
					<option value="0" <?php if($cancellation->list_before_status == 0) echo 'selected'; ?>>No</option>
				</select>
				</td>
			</tr>
			<tr class="list_before">
				<td class="clsName"></td>
				<td class="clsNoborder">
				Days<br>
				
					<select name="list_before_days">
						<?php
						for($i=0; $i<31;$i++)
						{
							?>
						<option value="<?php echo $i;?>" <?php if($cancellation->list_before_days == $i) echo 'selected'; ?>><?php echo $i;?></option>	
							<?php
						} 
						?>
					</select>
					<p><?php echo form_error('list_before_days');?></p>				
				</td>
			</tr>
			<tr class="list_before">
				<td>
				</td>
				<td>
				Percentage<span class="clsRed">*</span><br>
				<input type="text" name="list_before_percentage" value="<?php echo $cancellation->list_before_percentage;?>">									
				<p><?php echo form_error('list_before_percentage');?></p>
				</td>
			</tr>
			<tr class="list_before">
				<td class="clsName"></td>
				<td class="clsNoborder">
				Non-refundable Days<br>
				
					<select name="list_non_refund_days">
						<?php
						for($i=1; $i<31;$i++)
						{
							?>
						<option value="<?php echo $i;?>" <?php if($cancellation->list_non_refundable_nights == $i) echo 'selected'; ?>><?php echo $i;?></option>	
							<?php
						} 
						?>
					</select>
									
				</td>
			</tr>
			<tr>
				<td class="clsName"><?php echo translate_admin('After Checkin'); ?><span class="clsRed"></span></td>
				<td class="clsName"><?php echo translate_admin('Is Refundable'); ?>?
				<br/>
				<select name="list_refund_after" onchange="javascript:show('list_after',this.value);">
					<option value="1" <?php if($cancellation->list_after_status == 1) echo 'selected'; ?>>Yes</option>
					<option value="0" <?php if($cancellation->list_after_status == 0) echo 'selected'; ?>>No</option>
				</select>
				</td>
			</tr>
			<tr class="list_after">
				<td></td>
				<td class="clsNoborder">
				Days<br>
				
					<select name="list_after_days">
						<?php
						for($i=1; $i<31;$i++)
						{
							?>
						<option value="<?php echo $i;?>" <?php if($cancellation->list_after_days == $i) echo 'selected'; ?>><?php echo $i;?></option>	
							<?php
						} 
						?>
					</select>
									
				</td>
			</tr>
			<tr class="list_after">
				<td>
				</td>
				<td>
				Percentage<span class="clsRed"></span><br>
					<input type="text" name="list_after_percentage" value="<?php echo $cancellation->list_after_percentage;?>">										
				<p><?php echo form_error('list_after_percentage');?></p>
				</td>
			</tr>
			<tr>
				<td class="clsName"><?php echo translate_admin('Is displaying as standard'); ?>?<span class="clsRed"></span></td>
				<td class="clsName">
				<select name="is_standard">
					<option value="1" <?php if($cancellation->is_standard == 1) echo 'selected';?>>Yes</option>
					<option value="0" <?php if($cancellation->is_standard == 0) echo 'selected';?>>No</option>
				</select>
				</td>
			</tr>	  
    <tr>
				<td></td>
				<td>
		  <input type="hidden" name="cancellation_operation" value="edit" />
		  <input type="hidden" name="id"  value="<?php echo $cancellation->id; ?>"/>
		  <input type="hidden" name="already" id="already" value="<?php echo $check_cancellation;?>">
    <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" id="submit_edit"  name="editCancellation"/>
     <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Cancel'); ?>"  name="cancel"/></td>
	  	</tr>  
        
	  </table>
	</form>
	  <?php
	  }
	  ?>
    </div>

<script language="Javascript">
$(document).ready(function() {
	
$( "#submit_edit" ).click(function( event ) {
   		if($('#already').val() != '0')
   		{
   		var status = confirm('This Cancellation Policy already used by some lists. Do you want to update it? It will be affect the previous reservation requests also.');
   		if(status == true)
   		{
   		}
   		else
   		{
   		event.preventDefault();
   		window.location.href = '<?php echo base_url()."administrator/cancellation/viewCancellation";?>';	
   		}
		}
		
});
});
function show(fee,value)
{
	    if(value == 0)
		{
			$('.'+fee).hide();
		}
		else
		{
			$('.'+fee).show();
		}	
}

<?php
if($cancellation->list_days_prior_status == 0)
{
	?>
	$('.list_before_days_prior').hide();
	<?php
}
?>

<?php
if($cancellation->list_before_status == 0)
{
	?>
	$('.list_before').hide();
	<?php
}
?>

<?php
if($cancellation->list_after_status == 0)
{
	?>
	$('.list_after').hide();
	<?php
}
?>
</script>