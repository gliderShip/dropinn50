<?php
	session_start();
	
 error_reporting(0);
	
	include "db.php";
			
	$baseURL	= 'http://' . $_SERVER['SERVER_NAME'] . str_replace('\\', '/', $_SERVER['PHP_SELF']);

	$length		= strlen($baseURL) - strlen('install/install.php');
	
	$length2	= strlen($_SERVER['PHP_SELF']) - strlen('install/install.php');
	
	$folder	= substr($_SERVER['PHP_SELF'], 0, $length2);
	
	//$folder = str_replace("/"," ",$folder);
	
	$baseURL	= substr($baseURL, 0, $length);

	$mysqlHost	= '';
	$mysqlUname	= '';
	$mysqlPass	= '';
	$mysqlDB	= '';
	
	if(	isset($_POST['submit']) && $_POST['submit'] == 'Submit' &&
		trim($_POST['base_url']) != '' &&
		trim($_POST['mysql_host']) != '' &&
		trim($_POST['mysql_uname']) != '' && 
		trim($_POST['mysql_db']) != '')
	{
		$error	= '';
		$link = @osc_db_connect(trim($_POST['mysql_host']), trim($_POST['mysql_uname']), trim($_POST['mysql_password']));
		if (!$link) 
		{
		   $error = 'Could not connect to the host specified. Error: ' . mysql_error();
		}
		else
		{
			//Connected successfully
			$db_selected = @osc_db_select_db(trim($_POST['mysql_db']));
			
			if (!$db_selected) 
			{
			   $error	= $error . '<BR>Can\'t use the database specified. Error: ' . mysql_error();
			}
			
			//mysql_close($link);
		}

		$baseURL	= trim($_POST['base_url']);
		$mysqlHost	= trim($_POST['mysql_host']);
		$mysqlUname	= trim($_POST['mysql_uname']);
		$mysqlPass	= trim($_POST['mysql_password']);
		$mysqlDB	= trim($_POST['mysql_db']);
		
		if($error == '')
		{			
			$basePath	= dirname(__FILE__);
			
			$db_error = false;
			$sql_file = $basePath . '/install.sql';
	
			osc_set_time_limit();

			osc_db_install($mysqlDB, $sql_file);

			/* Create the config file */
			$file1		= file_get_contents($basePath . '/temp/config1.cfg');
			$file2		= trim($_POST['base_url']);
			$file3		= file_get_contents($basePath . '/temp/config2.cfg');
			
			$file4 		= '$config[\'hostname\'] = "'. trim($_POST['mysql_host']) .'";
$config[\'db_username\'] = "'. trim($_POST['mysql_uname']) .'";
$config[\'db_password\'] = "'. trim($_POST['mysql_password']) .'";
$config[\'db\'] = "'. trim($_POST['mysql_db']) .'";';

			$file5		= file_get_contents($basePath . '/temp/config3.cfg');
            $file9		= $folder;
			$file8      = file_get_contents($basePath . '/temp/config7.cfg');

			$file6		= trim($_POST['folder']);
			$file7		= file_get_contents($basePath . '/temp/config4.cfg');
			
			$configFile	= $file1 . $file2 . $file3 . $file4 . $file5 . $file9 . $file8  . $file6 . $file7;
			
			$handle	= fopen('config.php', 'w+');
			if($handle)
			{
				fwrite($handle, $configFile);
				fclose($handle);
			}
			
			//Copy the config file
			if(file_exists($basePath . '../app/config/config.php'))
			{
				if(file_exists($basePath . '../app/config/config.php.bak'))
					@unlink($basePath . '../app/config/config.php.bak');
					
				@rename($basePath . '../app/config/config.php', $basePath . '../app/config/config.php.bak');
			}
				
			@chmod($basePath . '/../app/config/config.php', 0777);
			copy($basePath . '/config.php', $basePath . '/../app/config/config.php');
		
			
			$_SESSION['baseurl'] = trim($_POST['base_url']);
			$_SESSION['mysql_host'] = trim($_POST['mysql_host']);
			$_SESSION['mysql_uname'] = trim($_POST['mysql_uname']);
			$_SESSION['mysql_password'] = trim($_POST['mysql_password']);
			$_SESSION['mysql_db'] = trim($_POST['mysql_db']);
			
			rename($basePath . '/install.php', $basePath . '/install_old.php');
			header('Location: siteDetails.php');
		}
	}
	elseif(isset($_POST['submit']) && $_POST['submit'] == 'Submit')
	{
		$baseURL	= trim($_POST['base_url']);
		$mysqlHost	= trim($_POST['mysql_host']);
		$mysqlUname	= trim($_POST['mysql_uname']);
		$mysqlPass	= trim($_POST['mysql_password']);
		$mysqlDB	= trim($_POST['mysql_db']);
		
		$error = 'All the fields are required';
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

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>

    <body>


	  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
  
  <!-- jQuery Form Validation code -->
  <script>
  
  // When the browser is ready...
  $(function() {


    // Setup form validation on the #register-form element
    $("#installFrm").validate({
    
        // Specify the validation rules
        rules: {
            base_url: "required",
            mysql_host: "required",
             mysql_uname: "required",
           // mysql_password: "required",
             mysql_db: "required"
          
      
        },
        
        // Specify the validation error messages
        messages: {
            base_url: "Please enter your Base Url",
             mysql_host: "Please enter your Host Name",
              mysql_uname: "Please enter your User Name",
               mysql_password: "Please enter your Database Password",
                mysql_db: "Please enter your Database Name"
           
        },
        
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
  </script>
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

        <div class="register-container container">
            <div class="row">
                <div class="iphone span5">
                  <!--  <img src="assets/img/iphone.png" alt="">-->
                </div>
             
                <div class="register">
                	
 
                    <form action="" name="installFrm" id="installFrm" method="post">
                    	
                        <h2>Installation<span class="red"><strong> Step (2 Of 4)</strong></span></h2>
                        
                                       	     <?php
	// PHP5?
	if(!version_compare(phpversion(), '5.0', '>=')) {
		echo 'OOps!! <strong>Installation error:</strong> in order to run DROPinn you need PHP5. Your current PHP version is: ' . phpversion();
	} 
	else
	{
		if(isset($error))
		echo '<div id="error" class="error red">' . $error . '</div><BR>';
	?>


                        	  <p><label>Base URL: <span class="red">*</span></label></label></p>
											  <p><input type="text" name="base_url" class="clsTextLarge" value="<?php echo $baseURL; ?>"/><span></span></p>
											  
											  <p><label>Host Name: <span class="red">*</span></label></label></p>
											  <p><input type="text" name="mysql_host" class="clsTextLarge" value="<?php echo trim($_POST['mysql_host'])?>"/><span></span></p>
																						  
											  <p><label>Database User Name : <span class="red">*</span></label></label></p>
											  <p><input type="text" class="clsTextLarge" name="mysql_uname" value="<?php echo trim($_POST['mysql_uname']);?>"/><span></span></p>
											  
											  <p><label>Database Password : <span class="red">*</span></label></label></p>
											  <p><input type="text" name="mysql_password" class="clsTextLarge" value="<?php echo trim($_POST['mysql_password']); ?>" /><span></span></p>
											    
											  <p><label>Database Name: <span class="red">*</span></label></label></p>
											  <p><input type="text" name="mysql_db" class="clsTextLarge" value="<?php echo trim($_POST['mysql_db']); ?>"/><span></span></p>
                        <button type="submit" id="submit" name="submit" value="Submit"">Continue</button>
                    </form>
                    			 	<?php
    } // End of else
    ?>
                </div>
                
            </div>
        </div>

        <!-- Javascript -->
      
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/scripts.js"></script>

    </body>

</html>

