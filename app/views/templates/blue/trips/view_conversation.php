<link href="<?php echo css_url().'/reservation.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo  css_url();?>/dashboard.css" type="text/css">

<script>
$(document).ready(function()
{
	$('.button1').click(function()
	{
		var message = $('#comment').val();
		 if(/\b(\w)+\@(\w)+\.(\w)+\b/g.test(message))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
            else if(message.match('@') || message.match('hotmail') || message.match('gmail') || message.match('yahoo'))
				{
					alert('Sorry! Email or Phone number is not allowed');return false;
				}
         	if(/\+?[0-9() -]{7,18}/.test(message))
            {
            	alert('Sorry! Email or Phone number is not allowed');return false;
            }
	})
})
</script>

<div class="container_bg container clearfix" id="Conversation_Blk" >
	<div class="Convers_left clsFloatLeft med_9 mal_9 pe_12 padding-zero">
    	<div class="Box">
      		
        	<div class="Box_Content">
            	<div class="clsConever_Bred">
                	<ul class="msg_box_clrfx">
                    	<li class="inb_top"><a href="<?php echo site_url('message/inbox'); ?>"><?php echo translate("Inbox"); ?></a></li>
                        <li><h3 class="mar_top"><?php echo translate("Conversation with"); ?> <?php echo ucfirst($conv_userData->username); ?>.</h3></li>
                    </ul>
                    <div style="clear:both"></div>
                </div>
                <div class="clsConver_Porfile_Blk clearfix">
                	<div class="clsConver_Pro_Img clsFloatLeft">
                    	<a href="javascript:void(0);"><img height="68" width="68" src="<?php echo $this->Gallery->profilepic($conv_userData->id, 2); ?>" alt="Profile" /></a>
                    </div>
                    <div class="clsConverPro_Name_Des clsFloatLeft">
                    	<h3 class="con_mar"><a href="<?php echo site_url('users/profile').'/'.$conv_userData->id; ?>"><?php echo ucfirst($conv_userData->username); ?></a></h3>
                        <p><span><?php echo translate("Member since"); ?></span> <?php echo get_user_times($conv_userData->created, get_user_timezone()); ?></p>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div id="selconver_pane_inner">
                	<ul class="clearfix">
                    	<li class="clearfix">
                        	<div class="clsConSamll_Pro_Img clsFloatLeft med_2 mal_2 pe_4">
                            	<p><a href="<?php echo site_url('users/profile').'/'.$conv_userData->id; ?>"><img height="50" width="50" alt="" src="<?php echo $this->Gallery->profilepic($this->dx_auth->get_user_id(),2); ?>" /></a></p>
                                <p><?php echo translate("You"); ?></p>
                            </div>
																												<?php echo form_open('trips/conversation/'.$conversation_id); ?>
                            <div class="clsType_Conver clsFloatLeft med_12 mal_12 pe_12">
                            	<textarea name="comment" id="comment"></textarea>
																													<input type="hidden" name="list_id" value="<?php echo $list_id; ?>" />
																													<input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>" />
																													<input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>" />
																													<input type="hidden" name="userto" value="<?php echo $conv_userData->id; ?>" />
																													<input type="hidden" name="userby" value="<?php echo $this->dx_auth->get_user_id(); ?>" />
                                <br />
																																<?php if(form_error('comment')) { ?>
																																<?php echo form_error('comment'); ?>
																																<?php } ?>
                                <p>
                                <button name="submit" class="btn_dash" type="submit"><span><span><?php echo translate("Send Message"); ?></span></span></button>
                                </p>
                                <span class="clsTypeCon_LArrow"></span>
                            </div>
																												<?php echo form_close(); ?>
                            <div style="clear:both"></div>
                        </li>
																								<?php foreach($messages->result() as $message) { 
																								$list_staus = $this->Common_model->getTableData('list',array('id'=>$message->list_id));
																								if($message->userby == $this->dx_auth->get_user_id()) { ?>
																								
                        <li class="clearfix">
                        	<div class="clsConSamll_Pro_Img clsFloatLeft med_2 mal_2 pe_4">
                            	<p><a href="<?php echo site_url('users/profile').'/'.$message->userby; ?>"><img height="50" width="50" alt="" src="<?php echo $this->Gallery->profilepic($message->userby,2); ?>" /></a></p>
                                <p><?php echo translate("You"); ?></p>
                            </div>
                            <div class="TypeConver_Ans clsFloatLeft med_10 mal_10 pe_8">
							<?php if($message->message_type != 3) { ?>
                            	<div class="TypeConver_Head">
                                 <p>
								<?php 
								if($list_staus->num_rows() == 0)
								echo 'List Deletion';
								else
								echo $message->name; 
								?> <?php 
								if($list_staus->num_rows() != 0)
								echo translate("about").' "'.anchor('rooms/'.$message->list_id, get_list_by_id($message->list_id)->title).'"'; ?>
								</p>
																																	<?php if($message->message_type != 6) { ?>
                                  <!--<p><label><?php echo get_user_times($message->checkin, get_user_timezone()).' - '.get_user_times($message->checkout, get_user_timezone()); ?></label><label><?php echo $message->no_quest.' '. translate("guest"); ?></label></p>-->
																																		<?php } ?>
                                  <span class="clsTypeConver_Arow1"></span>
                                </div>
																													<?php } ?>
																													
																													<?php if($message->message_type == 3) { ?>
                                <div class="TypeConver_Inner_SLft">
                                	<p> <?php echo $message->message; ?> </p>
																																	<span class="clsTypeCon_SLArrow"></span>
                                </div>
																														<?php } else { ?>
                                <div class="TypeConver_Inner">
                                	<p> <?php echo $message->message; ?> </p>
                                </div>
																														<?php } ?>
																														
                            </div>
                        </li>
																								
																								<?php } elseif($message->userto == $this->dx_auth->get_user_id()) { ?>
																								
                        <li class="clearfix TypeAns_Right">
                        	<div class="TypeConver_Ans clsFloatLeft med_10 mal_10 pe_8">
																									<?php if($message->message_type != 3) { ?>
                            	<div class="TypeConver_Head">
								<p>
								<?php 
								if($list_staus->num_rows() == 0)
								echo 'List Deletion';
								else
								echo $message->name; 
								?> <?php 
								if($list_staus->num_rows() != 0)
								echo translate("about").' "'.anchor('rooms/'.$message->list_id, get_list_by_id($message->list_id)->title).'"'; ?>
																																	</p>
																																	<!--<?php if($message->message_type != 6) { ?>
																																	<p><label><?php echo get_user_times($message->checkin, get_user_timezone()).' - '.get_user_times($message->checkout, get_user_timezone()); ?></label><label><?php echo $message->no_quest.' '.translate("guest"); ?></label></p>
																																	<?php } ?>-->
																																	<span class="clsTypeConver_Arow1"></span>
                                </div>
																													<?php } ?>
																													
																													<?php if($message->message_type == 3) { ?>
                                <div class="TypeConver_Inner_SRgt">
                                	<p> <?php echo $message->message; ?> </p>
																																	<span class="clsTypeCon_SRArrow"></span>
                                </div>
																														<?php } else { ?>
                                <div class="TypeConver_Inner">
                                	<p> <?php echo $message->message; ?> </p>
                                </div>
																														<?php } ?>
																														
                            </div>
                        	<div class="clsConSamll_Pro_Img clsFloatLeft med_2 mal_2 pe_4">
                            	<p><a href="<?php echo site_url('users/profile').'/'.$message->userby; ?>"><img height="50" width="50" alt="" src="<?php echo $this->Gallery->profilepic($message->userby,2); ?>" /></a></p>
                                <p class="user_name_con"><?php echo ucfirst(get_user_by_id($message->userby)->username); ?></p>
                            </div>
                        </li>
																								 
																								<?php } } ?>
                    </ul>
                    <div style="clear:both"></div>
                </div>
                 
        	</div>
            
        </div>
	</div>
	<div class="Convers_Rgt clsFloatRight med_3 mal_3 pe_12 pad_lef">
    	<div class="Box" id="quick_links">
      <div class="Box_Head">
        <h2><?php echo translate("Quick Links");?></h2>
      </div>
      <div class="Box_Content">
        <ul>
          <li><a href=<?php echo base_url().'listings'; ?>> <?php echo translate("View/Edit Listings"); ?></a></li>
          <li><a href="<?php echo site_url('listings/my_reservation'); ?>"><?php echo translate("Reservations"); ?></a></li>
        </ul>
      </div>
      <div style="clear:both"></div>
    </div>
	</div>
     <div style="clear:both"></div>
</div>
