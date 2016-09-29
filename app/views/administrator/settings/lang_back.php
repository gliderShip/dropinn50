<script type="text/javascript">
		function startCallback() {
		$("#message").html('<img src="<?php echo base_url().'images/loading.gif' ?>">');
		// make something useful before submit (onStart)
		return true;
	}

	function completeCallback(response) {
	 $('#message').show();
		$("#message").html(response);
		$("#message").delay(1000).fadeOut('slow');
		location.reload(true);
	}
</script>

<div id="Lang_Back">
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 <h1 class="page-header1"><?php echo translate_admin('Back-end Language Settings'); ?></h1>
	 </div>
		
<form action="<?php echo admin_url('settings/lang_back'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">	
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
	
<tr>
			<td><?php echo translate_admin('Select Language Translator'); ?><span style="color: red;">*</span></td>
			<td>
			 <select id="language_translator" name="language_translator" onChange="javascript:showhide(this.value);">
			   <option value="1"> <?php echo translate_admin('Core Langauge Translator'); ?> </option>
      <option value="2"> <?php echo translate_admin('Google Langauge Translator'); ?> </option>
			</select>
			</td>
</tr>		

<?php
		if($language_translator == 1)
		{
		$showC = 'table-row';
		$showG = 'none';
		}
		else
		{
		$showC = 'none';
		$showG = 'table-row';
		}
?>

<tr id="core" style="display:<?php echo $showC; ?>">
			<td><?php echo translate_admin('Select Default Language'); ?><span style="color: red;">*</span></td>
			<td>
				
				<?php $default_lang = $this->db->where('code','BACKEND_LANGUAGE')->get('settings')->row()->string_value; ?>
			 <select id="core_lang" name="core_lang">
			   <?php
			   $languages = $this->Common_model->getTableData( 'language',array('id <='=>6))->result();
			    foreach($languages as $language) { ?>
			   <option value="<?php echo $language->code; ?>" <?php if($language->code == $default_lang) echo 'selected="selected"' ?>> <?php echo $language->name; ?> </option>
			<?php } ?>
			</select>
			</td>
</tr>

<!--<tr id="google" style="display:<?php echo $showG; ?>">
			<td class="clsName"><?php echo translate_admin('Select Default Language'); ?><span class="clsRed">*</span></td>
			<td> 
			<select id="google_lang" name="google_lang">
			<?php foreach($languages as $language) { ?>
			   <option value="<?php echo $language->code; ?>"> <?php echo $language->name; ?> </option>
			<?php } ?>
			</select>
			</td>
</tr>	-->

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

<?php echo form_close(); ?>

</div>


<script language="Javascript">
jQuery("#language_translator").val('<?php echo $language_translator; ?>');
//jQuery("#core_lang").val('<?php echo $core_lang; ?>');
jQuery("#google_lang").val('<?php echo $google_lang; ?>');

function showhide(id)
{
	if(id == 1)
	{
	document.getElementById("core").style.display             = "table-row";
	//document.getElementById("google").style.display           = "none";
	}
	else
	{ 
	document.getElementById("core").style.display             = "none";
	//document.getElementById("google").style.display           = "table-row";	
	}

}
</script>