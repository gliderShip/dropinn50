<ul class="subnav">

<li <?php if($this->uri->segment(1) == 'account' && $this->uri->segment(3) != 'future') echo 'class="active"'; ?>>
<a href="<?php echo base_url(); ?>account/transaction"><?php echo translate("Completed Transaction"); ?></a>
</li>

<li <?php if($this->uri->segment(3) == 'future') echo 'class="active"'; ?>>
<a href="<?php echo base_url();?>account/transaction/future"><?php echo translate("Future Transaction"); ?></a>
</li>

</ul>
