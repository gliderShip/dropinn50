<style>
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	.res_table td:nth-of-type(1):before { content: "S. No"; }
	.res_table td:nth-of-type(2):before { content: "Site Name" ; }
	.res_table td:nth-of-type(3):before { content: "Link"; }
}
</style>


<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.validate.js"></script>
    <div id="View_Pages">
    <?php if($message!="") { ?>
    <div class="message"><div class="success"><?php echo $message; ?></div></div>
	<?php } ?>	
      
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
          <h1 class="page-header1"><?php echo translate_admin("Manage Join us on"); ?></h1>
		</div>
      
	<form action="<?php echo admin_url('joinus/updateJoinus') ?>" name="managepage" method="post" id="data_form">
  <div class="col-xs-9 col-md-9 col-sm-9">
  <table class="table1 res_table" cellpadding="2" cellspacing="0">
		 								<thead>
			<th><?php echo translate_admin('S.No'); ?></th>
			<th><?php echo translate_admin('Site Name'); ?></th>
			<th><?php echo translate_admin('Link'); ?> <?php echo translate_admin("Examplelink"); ?></th>
			</thead>			
			<tr><td>1</td><td>
				<?php echo translate_admin("Twitter"); ?></td>
			 <td>
				<input class="joinus_box" type="text" id="twitter" name="twitter" value="<?php echo $twitter ?>">
			</td>
		 </tr>
		 	<tr><td>2</td>
				<td><?php echo translate_admin("Facebook"); ?></td>
			<td>
				<input class="joinus_box" type="text" id="facebook" name="facebook" value="<?php echo $facebook ?>">
			</td>
		 </tr>
			<tr><td>3</td>
			<td><?php echo translate_admin("Google"); ?></td>
			<td>
				<input class="joinus_box" type="text" id="google" name="google" value="<?php echo $google ?>">
				</td>
			</tr>
			<tr>
			<td>4</td>
				<td><?php echo translate_admin("YouTube"); ?></td>
				<td><input class="joinus_box" type="text" id="youtube" name="youtube" value="<?php echo $youtube ?>">
				</td>
			</tr>
		</table>
		<div class="col-md-9 col-xs-9 col-sm-9">
		<table class="table">
			<tr>
				<td>&#160;</td>
		<td><input type="submit" name="submit" value="<?php echo translate_admin("Submit"); ?>"></center></td></tr>
		</table>
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