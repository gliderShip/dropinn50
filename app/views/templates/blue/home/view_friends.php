<style>
	body {
background: url("https://a2.muscache.com/airbnb/static/wishlist/blue_cloud_bg-b47c42cb6f74f3c876451a1eeb58f7ee.jpg") repeat-x scroll center 82px #F3F9FD;
}
</style>
<script>
	FB.init({ 
       appId:'<?php echo $fb_app_id; ?>', 
       frictionlessRequests: true
     });
     FB.getLoginStatus(function(response) {
  if (response.status === 'connected') {
  	 	
  		$("#first_div").hide();
    FB.api('/me/friends', function(response) {
        if(response.data) {
        	
        	var count = 0;
        	$.each(response.data,function(index,friend) {
        	count++;
        	});
        	var i =0;
        	var id = new Array();
        	var name = new Array();
            $.each(response.data,function(index,friend) {
            	id[i] = friend.id;
            	name[i] = friend.name;
            	
            	i++;
            });
           
                $.ajax({
  type: "POST",
  url: '<?php echo base_url()."home/fun_friends_fb_id";?>',
  data: { fb_id: id, fb_name: name, friends_count: count, match_count: i },
   success: function(data)
        {
        	$.ajax({
  type: "POST",
  url: '<?php echo base_url()."users/check_user";?>',
   success: function(data1)
        {
          if(data1 == '')
          {
          	login();
          }
          else
          {
          	$('#div').html(data);
          }
        }
}); 
        }
});
        //  });
        } else {
            alert("Error!");
        }
    });
  } else if (response.status === 'not_authorized') {
    // the user is logged in to Facebook, 
    // but has not authenticated your app
  } else {
    // the user isn't logged in to Facebook.
    	$("#first_div").show();
  }
 });
     function facebook()
     {
     	 FB.login(function(response) {
    if (response.authResponse) {
    	$("#first_div").hide();
    
     	 FB.api('/me/friends', function(response) {
        if(response.data) {
        	var count = 0;
        	$.each(response.data,function(index,friend) {
        	count++;
        	});
        	var i =0;
        	var id = new Array();
        	var name = new Array();
            $.each(response.data,function(index,friend) {
            	id[i] = friend.id;
            	name[i] = friend.name;
            	
            	i++;
            });
            
                $.ajax({
  type: "POST",
  url: '<?php echo base_url()."home/fun_friends_fb_id";?>',
   data: { fb_id: id, fb_name: name, friends_count: count, match_count: i },
   success: function(data)
        {
        	$.ajax({
  type: "POST",
  url: '<?php echo base_url()."users/check_user";?>',
   success: function(data1)
        {
          if(data1 == '')
          {
          	login();
          }
          else
          {
          	$('#div').html(data);
          }
        }
}); 
                	            
        }
});
        } else {
            alert("Error!");
        }
    });
     }
    });
     }
</script>
<!--<style>
body {
	background: url("https://a2.muscache.com/airbnb/static/wishlist/blue_cloud_bg-b47c42cb6f74f3c876451a1eeb58f7ee.jpg") repeat-x scroll center 82px #F3F9FD;
}
</style>-->

<script type="text/javascript">
FB.init({ 
       appId:'<?php echo $fb_app_id; ?>', 
       frictionlessRequests: true
     });

     function login()
     {
     	
            FB.login(function(response) {
    if (response.authResponse) {
        FB.api("/me", function(me){
            if (me.id) {
            	
            	var id = me.id; 
            	var email = me.email;
            	var first_name = me.first_name;
            	var last_name = me.last_name;
            	var live ='';
            	 if (me.hometown!= null)
        {
        	var live = me.hometown.name;
        }
            	
            	var picture = 'https://graph.facebook.com/'+id+'/picture?type=square';
            	var username = me.username;
            	
            	$.ajax({
            		cache: false,
  type: "POST",
  dataType : 'text',
  url: '<?php echo base_url()."facebook/success?";?>'+new Date().getTime(),
  data: { id: id, email: email, Fname: first_name, Lname: last_name, live: live, src: picture, username: username },
   success: function(data)
        { 
        	if(data)
        	{
		window.location.href = '<?php echo base_url()."home/friends";?>';
			}  
               },
        error: function (req, text, error) {
    		    alert(error);
    		}
});
            	  }
        });
    }
}, {scope: 'email'});
}
</script>

<style>
	.padd-left{
		padding-left:0px !important;
	}
	.padd-right{
		padding-right:0px !important;
	}
</style>

<?php
echo '<div id="first_div" class="container" style="display:block;">';
echo '<div class="landette container pe_12">';
echo '<div class="pe_12 frient_text"><p>'.translate("See what your friends are saving to their Wish Lists on").' '.$this->dx_auth->get_site_title().'!</p></div>';
echo "<div class='fb-conntect pe_12 frient_fb'>";
echo '<button class="btn fb-blue facebook_me" id="facebook" class="facebook" onclick="facebook()">
	<span class="icon-container">
		<i class="icon icon-facebook"></i>
	</span>
<span class="fb-img">Connect with facebook</span></button></div></div>';
echo '<img id="loading" class="box-img" src="'.base_url().'css/templates/blue/images/landette-bottom.png" />';
echo '<div class="frds-mar">';
echo '<div class="friends-feed"><div class="whole" style="margin-bottom:20px;"><img class="firstimg" src="'.base_url().'images/FB_banner.png" style="width:100%;" />';
echo '<div class="friends-feed_sub_icon_new"><div class="sub_icon_img_new"><img src="'.base_url().'images/no_avatar-xlarge.png" width="60" height="62" ><div class="address">Raja<br/><a>New South Wales, Australia</a></div></div></div>';
echo '<div class="friends-feed_rate_card_first"><img src="'.base_url().'css/templates/blue/images/dollar_bg.png"  /><p class="doller_symbol">$</p><p class="price">135</p><p class="pernight_doller">Per Night</p></div></div> </div>';

//echo '<div class="landette-sub">';
echo '<div class="friends-feed_sub med_6 pe_12 mal_6 "><img src="'.base_url().'images/banner4.jpg"  />';
echo '<div class="friends-feed_sub_icon"><div class="sub_icon_img"><img src="'.base_url().'images/no_avatar-xlarge.png" width="60" height="62" ><div class="address">Murali<br/><a>Victoria,<br> Tasmania</a></div></div></div>';
echo '<div class="friends-feed_rate_card"><img src="'.base_url().'css/templates/blue/images/dollar_bg.png" /><p class="doller_symbol">$</p><p class="price">135</p><p class="pernight_doller">Per Night</p></div>';
echo '</div>';

//echo '<div class="landette-sub">';
echo '<div class="friends-feed_sub med_6 pe_12 mal_6 " ><img src="'.base_url().'images/banner3.png"  />';
echo '<div class="friends-feed_sub_icon"><div class="sub_icon_img"><img src="'.base_url().'images/no_avatar-xlarge.png" width="60" height="62" ><div class="address">Arunkumar.G<br/><a>Tamilnadu, India</a></div></div></div>';
echo '<div class="friends-feed_rate_card"><img src="'.base_url().'css/templates/blue/images/dollar_bg.png" /><p class="doller_symbol">$</p><p class="price">135</p><p class="pernight_doller">Per Night</p> </div>';
echo '</div>';



echo '<div class="friends-feed1 med_12 mal_12 pe_12"><div class="whole"><img src="'.base_url().'images/friends-feed-map.jpg" width="100%" />';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';

echo '<div class="div" id="div">';
echo '<img id="loading" style="margin-top:100px;" src="'.base_url().'images/page2_spinner.gif" />';
 echo '</div>';
echo '</div>';
echo '</div>';
?>
