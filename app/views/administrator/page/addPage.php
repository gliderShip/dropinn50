<script type="text/javascript" src="<?php echo base_url()?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		
								editor_deselector : "mceNoEditor",
        plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

        // Theme options
        theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true
		
	});
</script>

    <div class="View_AddPage">

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
    	<h1 class="page-header1"><?php echo translate_admin('Add Page'); ?></h1></div>
			<form method="post" action="<?php echo admin_url('page/addPage')?>">
	<div class="col-xs-9 col-md-9 col-sm-9">
	  <table class="table" cellpadding="2" cellspacing="0">
      
		  <tr>
		     <td><?php echo translate_admin('Page Name'); ?><span style="color: red;">*</span></td>
		     <td class="clsMailID"><input type="text" name="page_name" value="<?php echo set_value('page_name'); ?>"><?php echo form_error('page_name'); ?></td>
		  </tr>
				
    <tr>
		     <td><?php echo translate_admin('Page Title'); ?><span style="color: red;">*</span></td>
		     <td><input type="text" name="page_title" value="<?php echo set_value('page_title'); ?>"> <?php echo form_error('page_title'); ?> </td>
		  </tr>
				
    <tr>
		     <td><?php echo translate_admin('Page URL'); ?><span style="color: red;">*</span></td>
		     <td><input type="text" name="page_url" value="<?php echo set_value('page_url'); ?>"><?php echo form_error('page_url'); ?></td>
		  </tr>
				
				<tr>
		     <td><?php echo translate_admin('Is link in footer').'?'; ?></td>
		     <td>
							<select name="is_footer" id="is_footer" >
							<option value="0" <?php if(set_value('is_footer') == '0') echo 'selected'; ?>> No </option>
							<option value="1" <?php if(set_value('is_footer') == '1') echo 'selected'; ?>> Yes </option>
							</select> 
							</td>
		  </tr>
		  <?php if(set_value('is_under') != '' && set_value('is_footer') == '1')
		  { ?>
		  	<tr id='is_under_tr'>
		<?php  }
         else{
		  ?>
		   <tr id='is_under_tr' style="display: none">
		   	<?php } ?>
		     <td><?php echo translate_admin('Is link under the').'?'; ?></td>
		     <td>
							<select name="is_under" id="is_under" >
							<option value="discover" <?php if(set_value('is_under') == 'discover') echo 'selected'; ?>> Discover </option>
							<option value="company" <?php if(set_value('is_under') == 'company') echo 'selected'; ?>> Company </option>
							</select> 
							</td>
		  </tr>
				
   
				
	     <tr>
	     	<tr>
				<td><?php echo translate_admin('Page Content'); ?><span style="color: red;">*</span></td>
				<td class="clsNoborder">
				<textarea id="elm1" name="page_content" rows="15" cols="80" style="width: 80%"><?php set_value('page_content');?></textarea>
				<?php echo form_error('page_content');?>
				</td>
			</tr>
						<td></td>    
						<td>
						<input type="hidden" name="page_operation" value="add"  />
						<input class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" name="addPage" type="submit">
						</td>
						</tr>
						
         </table>
								 </form>
    </div>
<script language="Javascript">
$(document).ready(function () {
$('#is_footer').change(function() {
	var footer = $("#is_footer option:selected").val();
       if(footer == '0')
       {
       	$('#is_under_tr').hide();
       }
       else
       {
        $('#is_under_tr').show();
       }
     }); });
</script>
