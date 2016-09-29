<div id="Edit_Location">
<div class="clsTitle">
	<style>
.table p {
    color: #FF0000;
}
</style>
<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
<h1 class="page-header1"><?php echo translate_admin('Add Users'); ?></h1></div>

<form action="<?php echo admin_url('members/add/'); ?>" method="post" name="user_edit">
<div class="col-xs-9 col-md-9 col-sm-9">
<table class="table" cellpadding="2" cellspacing="0">
<tr>
<td><?php echo translate_admin("First name"); ?><span style="color:#FF0000">*</span></td>
<td>
<input class="name_input" style="margin:0 10px 0 0;" id="user_first_name" name="Fname" size="30" type="text" value="<?php echo set_value('Fname'); ?>" />
<?php echo form_error('Fname'); ?>
</td>
</tr>
<tr>
<td><?php echo translate_admin("Last name"); ?><span style="color:#FF0000">*</span></td>
<td>
<input class="name_input" style="margin:0 10px 0 0;" id="user_last_name" name="Lname" size="30" type="text" value="<?php echo set_value('Lname'); ?>" />
<?php echo form_error('Lname'); ?>
</td>
</tr>
<tr>
<td>
<?php echo translate_admin("User name"); ?><span style="color:#FF0000">*</span></td>
<td>
<input class="private_lock" id="user_email" name="username" size="30" type="text" value="<?php echo set_value('username'); ?>"/>
<?php echo form_error('username'); ?>
</td>
</tr>

<tr>
<td>
<?php echo translate_admin("Email Address"); ?><span style="color:#FF0000">*</span></td>
<td>
<input class="private_lock" id="user_email" name="email" size="30" type="text" value="<?php echo set_value('email'); ?>"/>
<?php echo form_error('email'); ?>
</td>
</tr>

<tr>
<td>
<?php echo translate_admin("Password"); ?><span style="color:#FF0000">*</span></td>
<td>
<input class="private_lock" id="user_email" name="pwd" size="30" type="password" value="" style="width: 280px"/>
<?php echo form_error('pwd'); ?>
</td>
</tr>

<tr>
<td>
<?php echo translate_admin("Confirm Password"); ?><span style="color:#FF0000">*</span></td>
<td>
<input class="private_lock" id="user_email" name="cpwd" size="30" type="password" value="" style="width: 280px"/>
<?php echo form_error('cpwd'); ?>
</td>
</tr>
<tr>
<td>
<p>* Required fields</p>																																													               
</td>
</tr>
<tr>
	<td>&#160;</td>
	<td>
<div class="clearfix">
<input type="submit" class="can-but" name="commit" value="<?php echo translate_admin("Submit"); ?>">
<input type="submit" class="can-but" name="cancel" value="<?php echo translate_admin("Cancel"); ?>">
</div>
</td></tr>
</table>
</form>		
</div>					