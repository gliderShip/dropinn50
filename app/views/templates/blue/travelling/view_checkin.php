<link rel="stylesheet" href="<?php echo  css_url();?>/dashboard.css" type="text/css">

<div id="View_Checkin" class="Box">
<div class="Box_Head1">
<h2 class="chck_head_box"> <?php echo translate("Checkin"); ?> </h2>
</div>
<div class="Box_Content">
<form id="checkin" name="checkin-trips" action="<?php echo site_url('travelling/checkin'); ?>" method="post">
<p> <?php echo translate("Are you sure to checkin?"); ?> </p>
<p> 
<input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>" >

<button name="checkin" type="submit" class="btn_dash"><span><span><?php echo translate("Checkin"); ?></span></span></button>
<!--<button name="checkin" id="button1" type="submit" class="button1"><span><span><?php echo translate("Checkin"); ?></span></span></button>-->

</p>
</form>
</div>
</div>
<script>
/*$("#button1").click(function(){
       // alert('success');
        var count = 0;
        count++;
     //   alert(count);
        if(count>1)
      	
          $('#button1').prop('disabled', true);
});*/                                                
    
 /*$('#checkin').on('submit', function () {
    $('#button1').prop('disabled', true);
});*/

 var count = 0; 
$("#button1").click(function(){

     count++;
  
     if(count>1)
         $('#button1').prop('disabled', true);
});


	
</script>



