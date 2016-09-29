<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.validate.js"></script>
    
    
    
        <?php if($message!="") { ?>
    
    
	<?php } ?>	
    
    <div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
		<?php if($message) {?>
    <div class="message"><div class="success1"><?php echo $message; ?></div></div><?php  } ?>
        <h1 class="page-header1"><?php echo translate_admin("Site theme settings"); ?></h1>
		</div>
      
	<form action="<?php echo admin_url('theme_select/updatetheme_select') ?>" name="managepage" method="post" id="data_form">
  <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table" cellpadding="2" cellspacing="0">
		
			<tr>
				<td>
				<?php echo translate_admin("Select Site theme color"); ?></td>
				<td>
				<select name="color">
			 		<?php 
			 		foreach ($get_table->result() as $color_get) { ?>
					<option value="<?php echo $color_get->color;?>" <?php if($color_get->color == $color) echo 'selected'; ?>>
					<?php echo ucfirst($color_get->color);?>	
					</option>
			 	<?php }	?>
			 	</select>
				</td>
		   </tr>
		   <tr>
		   	<td></td>
		   	<td>
		   		<input type="submit" name="submit" value="<?php echo translate_admin("Submit"); ?>">
		   	</td>
		   </tr>
			
		</table>
	<!--<br><input type="submit" name="submit" value="<?php echo translate_admin("Submit"); ?>">-->
		</form>	
</div>

<script type="text/javascript">

jQuery(document).ready(function() {
    
jQuery.validator.addMethod("codecheck", function(value, element) {
        return this.optional(element) || /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value); 
    });    
jQuery("#data_form").validate({  
    
rules: {
		    twitter: { required: true,url: true },
            facebook:{ required: true,url: true },
            google:  { required: true,url: true },
            youtube: { required: true,url: true }
      },
messages: {     twitter: { required: "Twitter is required", },
                facebook:{ required: "Facebook is required",},
                google:  { required: "Google is required",  },
                youtube: { required: "YouTube is required", }
          }
    
});      
    
});
</script>