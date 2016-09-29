<script type="text/javascript" src="<?php echo base_url()?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>

<div id="Add_Faq">

<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Add FAQ'); ?></h1>
	 </div>
		
<form method="post" action="<?php echo admin_url('faq/addFaq')?>" id="faq_form" name="faq_form">	
	<div class="col-xs-9 col-md-9 col-sm-9">
			<table class="table" cellpadding="2" cellspacing="0" width="100%">
		<tr>
					<td><?php echo translate_admin('Question ?'); ?>&nbsp;<span style="color: red;">*</span></td>
					<td class="clsMailID">
												<input class="forminput" id="question" type="text" name="question" value="<?php echo set_value('question'); ?>">
		<?php echo form_error('question'); ?></td>
		</tr>
							
		<tr>
				<td> 
						<?php echo translate_admin('Answer'); ?>&nbsp;<span style="color: red;">*</span>
				</td>
				<td>
				<textarea id="faq_content" name="faq_content" rows="15" cols="150" style="width: 100%"></textarea>
			<?php echo form_error('faq_content'); ?>
			</td>
	</tr>
	
		<tr>
		<td></td>    
		<td>
		<input type="hidden" name="faq_operation" value="add"  />
		<input class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" name="addFaq" type="submit">
		</td>
		</tr>
		</table>
 </form>

</div>