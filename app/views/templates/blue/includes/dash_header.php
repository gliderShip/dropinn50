<div id="dashboard_page" class="container container_bg">
<ul id="nav" class="clearfix">
	<li id="dashboard" class="med_2 mal_2 pe_12 ">
	<?php
	 if($this->uri->segment(2) == 'dashboard')
	 echo anchor('home/dashboard/',translate("Dashboard"),array("class" => "Search_Active med_12 pe_12 mal_12")); 
		else
		echo anchor('home/dashboard/',translate("Dashboard"),array("class" => "med_12 pe_12 mal_12")); 
		?>
	</li>
	
		<li id="rooms" class="med_2 mal_2 pe_12 ">
	<?php
	 if($this->uri->segment(1) == 'message'  && $this->uri->segment(2) == 'inbox')
	 echo anchor('message/inbox',translate("Inbox"),array("class" => "Search_Active med_12 pe_12 mal_12")); 
		else
		echo anchor('message/inbox',translate("Inbox"),array("class" => "med_12 pe_12 mal_12")); 
		?>
	</li>
	
	<li id="rooms" class="med_2 mal_2 pe_12">
	<?php
	 if($this->uri->segment(1) == 'listings' || $this->uri->segment(2) == 'myReservation' || $this->uri->segment(2) == 'policies')
	 echo anchor('listings',translate("Your Listings"),array("class" => "Search_Active med_12 pe_12 mal_12")); 
		else
		echo anchor('listings',translate("Your Listings"),array("class" => "med_12 pe_12 mal_12")); 
		?>
	</li>
	
	<li id="rooms" class="med_2 mal_2 pe_12">
	<?php
	 if($this->uri->segment(1) == 'travelling')
	 echo anchor('travelling/your_trips',translate("Your Trips"),array("class" => "Search_Active med_12 pe_12 mal_12"));
		else
		echo anchor('travelling/your_trips',translate("Your Trips"),array("class" => "med_12 pe_12 mal_12"));
		 ?>
	</li>
	
 <li id="rooms" class="med_2 mal_2 pe_12">
	<?php
	 if($this->uri->segment(1) == 'account')
	 echo anchor('account',translate("Account"),array("class" => "Search_Active med_12 pe_12 mal_12"));
		else
		echo anchor('account',translate("Account"),array("class" => "med_12 pe_12 mal_12"));
		?>
	</li>
	
 <li id="rooms" class="clsBg_None med_2 mal_2 pe_12">
	<?php
	 if($this->uri->segment(1) == 'users')
	 echo anchor('users/edit',translate("Profile"),array("class" => "Search_Active med_12 pe_12 mal_12"));
		else
		echo anchor('users/edit',translate("Profile"),array("class" => "med_12 pe_12 mal_12"));
		?>
	</li>
	
</ul>