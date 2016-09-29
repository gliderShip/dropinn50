<?php
session_start();
error_reporting(0);
require_once("../app/config/config.php");
include "db.php";

if(	$config['hostname'] != '' &&
		$config['db_username'] != '' && 
		$config['db'] != '')
	{
$link = @osc_db_connect(trim($config['hostname']), trim($config['db_username']), trim($config['db_password']));

if (!$link) 
{
   $error = 'Could not connect to the host specified. Error: ' . mysql_error();
}
else
{
	//Connected successfully
	$db_selected = @osc_db_select_db(trim($config['db']));
	
	if (!$db_selected) 
	{
	   $error	= $error . '<BR>Can\'t use the database specified. Error: ' . mysql_error();
	}
	
	//mysql_close($link);
}
//echo $error;exit;
$sql = " SHOW TABLES FROM ".trim($config['db']);

$result = osc_db_query($sql);

if (!$result) {
    echo "DB Error, could not list tables\n";
    echo 'MySQL Error: ' . mysql_error();
    exit;
}

$numtable = osc_db_num_rows($result);

mysql_free_result($result);

if($numtable > 0){
	header("Location: ../");
}

}
$compat_register_globals = true;

if (function_exists('ini_get') && (PHP_VERSION < 4.3) && ((int)ini_get('register_globals') == 0)) {
	$compat_register_globals = 'false';
}


?>

<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <title>DropInn Installation</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- CSS -->
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=PT+Sans:400,700'>
        <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Oleo+Script:400,700'>
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
        <link href="css/common.css" rel="stylesheet" type="text/css" />

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

    </body>

</html>






<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="css/iefix.css" />
	<![endif]-->
<title>DropInn - Step 1</title>
<link href="css/common.css" rel="stylesheet" type="text/css" /> 
</head>


<body>
<div id="selAtnio">


  <div class="Main_block" style="margin-top: 17px;">

                      
                      <div id="selMenu_new">
                           <div class="clsGrayCenter1 clearfix">
                              <div id="selMenuLeft">
                              	   
                                <h3>Installation<span class="red"><strong> Step (1 Of 4)</strong></span></h3>
                              </div>                     
                        </div>
                      </div>
                     
					  <div id="main">
                         
																								
								<div class="">
							<?php

							if (function_exists('ini_get')) {
							
							$config_file_permission=substr(decoct( fileperms("../app/config/config.php") ), 3);
							
							$install_config_file_permission=substr(decoct( fileperms("config.php") ), 3);
							
							if($_SERVER['HTTP_HOST']=="localhost")
							{
							$config_file_permission=777;
							$install_config_file_permission=777;
							}
							if($config_file_permission!=777)
							{
							echo '<div id="File_alerts">
							<ul class="alerts info">
							<li class="info">
							<div>
							<span style="font-size:13px;">
							Please Change The file Permission <strong style="color:#000">777</strong> for following file /app/config/config.php to continue the Installation
							</span> 
							</div>
							</li>
							</ul>
							</div>';
							}
							if($install_config_file_permission!=777)
							{
							echo '<div id="File_alerts">
							<ul class="alerts info">
							<li class="info">
							<div>
							<span style="font-size:13px;">
							Please Change The file Permission <strong style="color:#000">777</strong> for following file /install/config.php to continue the Installation
							</span> 
							</div>
							</li>
							</ul>
							</div>';
							}
		?>

<form name="settings" method="post" action="">
			
			         <table border="0" width="100%" cellspacing="0" cellpadding="2" class="tabel_block">
                 <tr class="tabel_block_tittle">
                  <td colspan="3"><b>PHP Extensions</b></td>
                </tr>
                <tr>
                  <td><li>MySQL</li></td>
                  <td align="right"><img src="images/<?php echo (extension_loaded('mysql') ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
                </tr>
                <tr>
                  <td><li>GD</li></td>
                  <td align="right"><img src="images/<?php echo (extension_loaded('gd') ? 'tick.gif' : 'cross.gif'); ?>" border="0" width="16" height="16"></td>
                </tr>
              </table>
             </br>
             
     
              <?php
  }

						
							if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
   $os = "Windows";
} else if(substr(PHP_OS, 0, 3) === 'Lin') {
   $os = "Linux";
}
else {
	
	$os = "Windows/Linux";
}


?>
       		       <table border="0" width="100%" cellspacing="0" cellpadding="2" class="tabel_block">
                 <tr class="tabel_block_tittle">
                  <td colspan="3"><b>System Requirements - Check the following requirements before installation:</b></td>
                </tr>
                <tr>
                  <td><li>Deducted OS</li></td>
                  <td align="right"></td>
                   <td align="right"><?php echo $os;?></td>
                </tr>
                  <tr>
                  <td><li>ioncube Loader version</li></td>
                  <td align="right"></td>
                   <td align="right">5.4 and above</td>
                </tr>
                <tr>
                  <td><li>Apache version</li></td>
                  <td align="right"></td>
                  <td align="right">2.2.4 and above</td>
                </tr>
                <tr>
                  <td><li>PHP version</li></td>
                  <td align="right"></td>
                  <td align="right">5.2.1 and above</td>
                </tr>
                <tr>
                  <td><li>MySQL version</li></td>
                  <td align="right"></td>
                  <td align="right">5.0.33 and above</td>
                </tr>
              </table>
             </br>
				              <table border="0" width="100%" cellspacing="0" cellpadding="2" class="tabel_block">
                 <tr class="tabel_block_tittle">
                  <td colspan="3"><b>Information you will need for installation:</b></td>
                </tr>
                <tr>
                  <td><li>MySQL Host Name (usually 'localhost')</li></td>

                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
                <tr>
                  <td><li>MySQL Username</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
                <tr>
                  <td><li>MySQL Password</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
                <tr>
                  <td><li>MySQL Database Name</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
																 <tr>
                  <td><li>Facebook App Id</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
																 <tr>
                  <td><li>Facebook App Key</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
                
                												<tr>
                  <td><li>Twitter App Id</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
																 <tr>
                  <td><li>Twitter App Key</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
						 <tr>
                  <td><li>Cloudinary cloud name</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
                											 <tr>
                  <td><li>Cloudinary API key</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
                											 <tr>
                  <td><li>Cloudinary API secret</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>
																 <tr>
                  <td><li>Google Map Key</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>	
                							<tr>
                  <td><li>Google API key</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>	
																<tr>
                  <td><li>Ioncube Encoder</li></td>
                  <td align="right"></td>
                  <td align="right"></td>
                </tr>																
															</table>
														</br>
														 <table border="0" width="100%" cellspacing="0" cellpadding="2" class="tabel_block">
                <tr class="tabel_block_tittle">
                  <td colspan="3"><b>PHP Settings</b></td>
                </tr>
                <tr>
                  <td><li>file_uploads</li></td>
                  <td align="right"><?php echo (((int)ini_get('file_uploads') == 0) ? 'Off' : 'On'); ?></td>
                  <td align="right"><img src="images/<?php echo (((int)ini_get('file_uploads') == 0) ? 'cross.gif' : 'tick.gif'); ?>" border="0" width="16" height="16"></td>
                </tr>
                <tr>
                  <td><li>session.auto_start</li></td>
                  <td align="right"><?php echo (((int)ini_get('session.auto_start') == 0) ? 'Off' : 'On'); ?></td>
                  <td align="right"><img src="images/<?php echo (((int)ini_get('session.auto_start') == 0) ? 'cross.gif' : 'tick.gif'); ?>" border="0" width="16" height="16"></td>
                </tr>
                <tr>
                  <td><li>session.use_trans_sid</li></td>
                  <td align="right"><?php echo (((int)ini_get('session.use_trans_sid') == 0) ? 'Off' : 'On'); ?></td>
                  <td align="right"><img src="images/<?php echo (((int)ini_get('session.use_trans_sid') == 0) ? 'cross.gif' : 'tick.gif'); ?>" border="0" width="16" height="16"></td>
                </tr>
              </table>
              <br />
              
            </div>
																								
																								
			 <div class="clsSubmit">
			 <p>
<?php if($error=="")
{ ?>
                    <input type="button" class="clsBluebtn" name="submit"   value="Continue" onClick="window.location='install.php'" style="cursor:pointer"/></p>
	<?php } else {	?>
<input type="submit" class="clsBluebtn" name="submit"   value="Continue"  style="cursor:pointer"/></p>
<?php }?>
	 </div>
                      </div>

</form>         
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


