    <div id="Edit_Managemetas">



<div class="container-fluid top-sp body-color">
	<div class="container">
	<div class="col-xs-9 col-md-9 col-sm-9">
	 	<h1 class="page-header1"><?php   echo translate_admin('Edit Metas'); ?></h1>
        </div>
			<form method="post" action="<?php echo admin_url('managemetas/editmetas')?>/<?php echo $this->uri->segment(4,0);  ?>">
	<div class="col-xs-9 col-md-9 col-sm-9">
   <table class="table" cellpadding="2" cellspacing="0">
				<tr>
				
						<td><?php echo translate_admin('Url'); ?><span style="color: red;">*</span></td>
						<td>
						<input type="text" id="city_name" name="url" maxlength="100"  value="<?php echo $metas->url; ?>" readonly>
						<span style="color: red;"><?php echo form_error('url'); ?></span>
						</td>
				</tr>
		
				<tr>
						<td><?php echo translate_admin(' Name'); ?><span style="color: red;">*</span></td>
						<td><input type="text" id="city_name" name="name" maxlength="50"  value="<?php echo $metas->name; ?>" readonly>
						<span style="color: red;"><?php echo form_error('name'); ?></span>
						</td>
				</tr>
				
				<tr>
						<td><?php echo translate_admin('Title'); ?><span style="color: red;">*</span></td>
						<td><input type="text" id="city_name" name="title" maxlength="50"  value="<?php echo $metas->title; ?>" >
						<span style="color: red;"><?php echo form_error('title'); ?></span>
						</td>
				</tr>
			
				<tr>
						<td><?php echo translate_admin('Description'); ?><span style="color: red;">*</span></td>
						<td><input type="text" id="city_name" name="description" maxlength="100"  value="<?php echo $metas->meta_description; ?>">
						<span style="color: red;"><?php echo form_error('description'); ?></span>
						</td>
				</tr>
				
				<tr>
						<td><?php echo translate_admin('Keyword'); ?><span style="color: red;">*</span></td>
						<td><input type="text" id="city_name" name="keyword" maxlength="50" value="<?php echo $metas->meta_keyword; ?>">
						<span style="color: red;"><?php echo form_error('keyword'); ?></span>
						</td>
				</tr>
	
				<tr>
						<td></td>
						<td><input type="hidden" name="page_operation" value="edit" />
						<input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Update'); ?>"  name="Update"/></td>
				</tr> 

        
	  </table>
	</form>

    </div>
 