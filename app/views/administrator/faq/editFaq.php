<script type="text/javascript" src="<?php echo base_url()?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple"
	});
</script>


    <div id="Edit_Faq">
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Edit FAQ'); ?></h1>
	 </div>
		
 <?php
	  	//Content of a group
		if(isset($faqs) and $faqs->num_rows()>0)
		{
			$faq = $faqs->row();
	  ?>
   <form method="post" action="<?php echo admin_url('faq/editfaq')?>/<?php echo $faq->id;  ?>"  id="static_page_form" name="static_page_form">	
<div class="col-xs-9 col-md-9 col-sm-9">
    <table class="table" cellpadding="2" cellspacing="0" width="100%">
		  <tr>
					<td><?php echo translate_admin('Question ?'); ?><span style="color: red;">*</span></td>
					<td>
					<input class="forminput" type="text" name="question" value="<?php echo $faq->question; ?>" id="question">
					</td>
				</tr>
		  
	   <tr>
				<td>
				<?php echo translate_admin('Answer ?'); ?><span style="color: red;">*</span></td>
				<td class="clsNoborder">
			<textarea id="faq_content" name="faq_content" rows="15" cols="80" style="width: 100%"><?php echo $faq->faq_content;?></textarea>
			<?php echo form_error('faq_content');?>
   </td>
		 </tr>
	  
   <tr>
			<td></td>
			<td>
		 <input type="hidden" name="faq_operation" value="edit" />
		 <input type="hidden" name="id"  value="<?php echo $faq->id; ?>"/>
   <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>"  name="editfaq"/>
			</td>
		</tr>  
		
  </table>
</form>
	  <?php
	  }
	  ?>
	</div>    
