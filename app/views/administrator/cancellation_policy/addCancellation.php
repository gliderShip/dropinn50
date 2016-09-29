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
	  
	 	<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
<h1 class="page-header1"><?php echo translate_admin('Add Cancellation Policy'); ?></h1></div>
			<form method="post" action="<?php echo admin_url('cancellation/addCancellation')?>">
   <div class="col-xs-9 col-md-9 col-sm-9">
   <table class="table" cellpadding="2" cellspacing="0">
			
		  		<tr>
					<td><?php echo translate_admin('Cancellation Policy Name'); ?><span style="color: red;">*</span></td>
					<td>
					<input class="" type="text" name="name" value="<?php echo set_value('name');?>">
					<p><?php echo form_error('name'); ?></p>
					</td>
				</tr>				
   <tr>
				<td><?php echo translate_admin('Cancellation Policy Title'); ?><span style="color: red;">*</span></td>
				<td>
					<input class="" type="text" name="cancellation_title" value="<?php echo set_value('cancellation_title');?>">
				 <p><?php echo form_error('cancellation_title'); ?></p>
				</td>
			</tr>
		
	  <tr>
				<td><?php echo translate_admin('Cancellation Policy Content'); ?><span style="color: red;">*</span></td>
				<td class="clsNoborder">
				<textarea id="elm1" name="cancellation_content" rows="15" cols="80" style="width: 80%"><?php echo set_value('cancellation_content');?></textarea>
				<p><?php echo form_error('cancellation_content');?></p>
				</td>
			</tr>
			
			<tr>
				<td><b><?php echo translate_admin('Cleaning Fee'); ?></b><span style="color: red;"></span></td>
				
			</tr>
			
			<tr>
				<td><?php echo translate_admin('After Chckin Is Refundable'); ?>?<span style="color: red;"></span></td>
				<td class="clsNoborder">
				<select name="cleaning_refund">
					<option value="1" <?php if(set_value('cleaning_refund') == 1) echo 'selected';?>>Yes</option>
					<option value="0" <?php if(set_value('cleaning_refund') == 0) echo 'selected';?>>No</option>
				</select>
				</td>
			</tr>
	
			<tr>
				<td class="clsName"><b><?php echo translate_admin('Security Fee'); ?></b><span style="color: red;"></span></td>
				
			</tr>
			
			<tr>
				<td class="clsName"><?php echo translate_admin('After Checkin Is Refundable'); ?>?<span class="clsRed"></span></td>
				<td class="clsNoborder">
				<select name="security_refund" onchange="javascript:show('security',this.value);">
					<option value="1" <?php if(set_value('security_refund') == 1) echo 'selected';?>>Yes</option>
					<option value="0" <?php if(set_value('security_refund') == 0) echo 'selected';?>>No</option>
				</select>
				</td>
			</tr>
			
			
			<tr>
				<td class="clsName"><b><?php echo translate_admin('Additional Guest Fee'); ?></b><span class="clsRed"></span></td>
				
			</tr>
			
			<tr>
				<td class="clsName"><?php echo translate_admin('After Checkin Is Refundable'); ?>?<span class="clsRed"></span></td>
				<td class="clsNoborder">
				<select name="additional_refund" onchange="javascript:show('additional',this.value);">
					<option value="1" <?php if(set_value('additional_refund') == 1) echo 'selected';?>>Yes</option>
					<option value="0" <?php if(set_value('additional_refund') == 0) echo 'selected';?>>No</option>
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
					<option value="1" <?php if(set_value('list_days_prior_status') == 1) echo 'selected'; ?>>Yes</option>
					<option value="0" <?php if(set_value('list_days_prior_status') == 0) echo 'selected'; ?>>No</option>
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
						<option value="<?php echo $i;?>" <?php if(set_value('list_days_prior_days') == $i) echo 'selected'; ?>><?php echo $i;?></option>	
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
				<input type="text" name="list_days_prior_percentage" value="<?php echo set_value('list_days_prior_percentage');?>">									
				<p><?php echo form_error('list_days_prior_percentage');?></p>
				</td>
			</tr>
			
			<tr>
				<td class="clsName"><?php echo translate_admin('Before Checkin'); ?><span class="clsRed"></span></td>
			
				<td class="clsName"><?php echo translate_admin('Is Refundable'); ?>?
					<br/>
				<select name="list_refund_before" onchange="javascript:show('list_before',this.value);">
					<option value="1" <?php if(set_value('list_refund_before') == 1) echo 'selected';?>>Yes</option>
					<option value="0" <?php if(set_value('list_refund_before') == 0) echo 'selected';?>>No</option>
				</select>
				</td>
			</tr>
			<tr class="list_before">
				<td></td>
				<td class="clsNoborder">
				Days<br>
				
					<select name="list_before_days">
						<?php
						for($i=0; $i<31;$i++)
						{
							?>
						<option value="<?php echo $i;?>" <?php if(set_value('list_before_days') == $i) echo 'selected'; ?>><?php echo $i;?></option>	
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
				<input type="text" name="list_before_percentage" value="<?php echo set_value('list_before_percentage');?>">									
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
						<option value="<?php echo $i;?>"><?php echo $i;?></option>	
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
					<option value="1" <?php if(set_value('list_refund_after') == 1) echo 'selected';?>>Yes</option>
					<option value="0" <?php if(set_value('list_refund_after') == 0) echo 'selected';?>>No</option>
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
						<option value="<?php echo $i;?>"><?php echo $i;?></option>	
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
					<input type="text" name="list_after_percentage" value="<?php echo set_value('list_after_percentage');?>">										
				<p><?php echo form_error('list_after_percentage');?></p>
				</td>
			</tr>
			<tr>
				<td class="clsName"><?php echo translate_admin('Is displaying as standard'); ?>?<span class="clsRed"></span></td>
				<td class="clsName">
				<select name="is_standard">
					<option value="1">Yes</option>
					<option value="0">No</option>
				</select>
				</td>
			</tr>
    <tr>
				<td></td>
				<td>
    <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>"  name="addCancellation"/>
     <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Cancel'); ?>"  name="cancel"/></td>
	  	</tr>  
        
	  </table>
	</form>
    </div>

<script language="Javascript">
//jQuery("#is_footer").val('<?php //echo $cancellation->is_footer; ?>');
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
if(set_value('list_days_prior_status') == 0)
{
	?>
	$('.list_before_days_prior').hide();
	<?php
}
?>

<?php
if(set_value('list_refund_before') == 0)
{
	?>
	$('.list_before').hide();
	<?php
}
?>

<?php
if(set_value('list_refund_after') == 0)
{
	?>
	$('.list_after').hide();
	<?php
}
?>
</script>