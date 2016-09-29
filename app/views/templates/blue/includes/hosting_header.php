	<ul class="subnav" id="submenu">
	
			<?php
			if($this->uri->segment(1) == 'listings' && $this->uri->segment(2) == '' || $this->uri->segment(2) == 'index')  echo '<li class="active">'; else echo '<li>'; 
			
				echo anchor('listings', translate("Manage Listings")); 
				
				echo '</li>';
		 ?>

		<?php
			if($this->uri->segment(1) == 'listings' && $this->uri->segment(2) == 'my_reservation') echo '<li class="active">'; else echo '<li>';
			 
				echo anchor('listings/my_reservation', translate("My Reservations")); 

    echo '</li>';
		 ?>

		<?php	if($this->uri->segment(1) == 'listings'  && $this->uri->segment(2) == 'policies') echo '<li class="active clsBorder_No">'; else echo '<li class="clsBorder_No">'; 
		
			echo anchor('listings/policies', translate("Policies")); 
			
			echo '</li>';
		?>
	</ul>