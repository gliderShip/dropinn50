<div class="container-fluid top-sp body-color">
	<div class="container">
<h2><?php echo translate_admin("Payment Canceled!"); ?></h2><br>
<?php if(isset($message))
{
	?>
	<p><?php echo $message; ?></p>
	<?php
}
else{
?>
<p><?php echo translate_admin("The order was canceled."); ?></p>
<?php } ?>
<?php echo anchor('/administrator',translate_admin("Click here to return to the home page")); ?>
</div>
</div>