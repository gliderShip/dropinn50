<?php
	session_start();
	
 error_reporting(0);
	
	if($_SESSION['baseurl'] == '')
		$url	= '../../';
	else
		$url	= $_SESSION['baseurl']; 
?>

<!DOCTYPE html>
<html lang="en">

    <head>

    

        <!-- CSS -->
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=PT+Sans:400,700'>
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Oleo+Script:400,700'>
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" type="text/css" href="css/common.css" />
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>

    <body>

        <div class="header">
            <div class="container">
                <div class="row">
                    <div class="logo span4" style="margin-top: 8px">
                    	
                    		<a class="blog" href="http://www.cogzidel.com/" > <img src="assets/img/logo.png" alt=""></a>
                     
                    </div>
                    <div class="links span8">
                       <!-- <a class="home" href="http://www.cogzidel.com/" rel="tooltip" data-placement="bottom" data-original-title="Home"></a> -->
                        <a class="blog" href="http://www.cogzidel.com/" rel="tooltip" data-placement="bottom" data-original-title="Help"></a>
                    </div>
                </div>
            </div>
        </div>

   

        <!-- Javascript -->
        <script src="assets/js/jquery-1.8.2.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/scripts.js"></script>


<div id="selAtnio">
<div id="header" class="clearfix">
             
 <div class="Main_block">
    
                      
                      <div id="selMenu_new">
                           <div class="clsGrayCenter1 clearfix">
                              <div id="selMenuLeft">
                                <h3>Installation<span class="red"><strong> Step (4 Of 4)</strong></span></h3>
                              </div>
                         
                        </div>
                      </div>
                      <div id="main">
			
                        <div class="block_tab">
                         
                                            <div class="clsDbServer clearfix">
											<h2>Installation is Completed Successfully.</h2>
											<br />
                                             <p>Congratulations!! You have successfully installed DropInn script on your server!</p>
											 <p>Please choose appropriate action:</p>
											 <p>Good Luck!</p>
                                              </div>
                                   
                          
                          </div>
						   <div class="clsSubmit">
			 <p><input type="button" class="clsBluebtn" name="home"  value="Site Home" onClick="window.location='<?php echo $url; ?>'"  style="cursor:pointer"/><input type="button" name="home" class="clsBluebtn"  value="Site Admin" onClick="window.location='<?php echo $url; ?>administrator'"  style="cursor:pointer"/></p>
			 </div>
			 </div>
			 
                 
            
                    
    
    </div>
  </div>

  <div id="Footer">
 <style>
  #Footer a:hover{
  	color:#fff;
	text-decoration:underline;
	}
	</style>

</div>
</body>
</html>