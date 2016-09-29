<div class="container-fluid top-sp body-color">
	<div class="container">
<h2><?php echo translate_admin("Payment Success!"); ?></h2>

<?php

if(isset($status))
{
if($status == 'braintree')
{
	?>
	<p> <?php echo translate_admin("Your payment was received using Braintree."); ?></p>
	<?php
}
else {
	?>
	<p> <?php echo translate_admin("Your payment was received using Paypal."); ?></p>
	<?php
}
}
else
	{
		?>
		<p> <?php echo translate_admin("Your payment was received using Paypal."); ?></p>
		<?php
	}
?>

<?php echo anchor('/administrator',translate_admin("Click here to return to the home page")); ?>
</div>
</div>
