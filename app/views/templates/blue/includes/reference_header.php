<ul class="subnav">

<li <?php if($this->uri->segment(2) == 'references' && $this->uri->segment(3) == '') echo 'class="active"'; ?>>
<a href="<?php echo site_url('users/references'); ?>"><?php echo translate("Request References"); ?></a>
</li>

<li <?php if($this->uri->segment(3) == 'reference_about_you') echo 'class="active"'; ?>>
<?php
	echo anchor('users/references/reference_about_you',translate("References About You").'<span style="font-style: italic;">'.$about_you_count.'</span>',array("class" => "")); 
	?>
</li>

<li <?php if($this->uri->segment(3) == 'reference_by_you') echo 'class="active"'; ?>>
<?php
	echo anchor('users/references/reference_by_you',translate("Reference By You").'<span style="font-style: italic;">'.$by_you_count.'</span>',array("class" => "")); 
	?>
</li>

</ul>
