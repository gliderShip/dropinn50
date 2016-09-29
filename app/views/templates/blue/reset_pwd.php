<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.validate.js" type="text/javascript"></script>-->
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script>
$(document).ready(function()
{
// validate signup form on keyup and submit
$("#reset_pwd").validate({
    rules: {
        new_pwd: {
            required: true,
            minlength: 8,
            maxlength: 20
        },
        confirm_pwd: {
            required: true,
            minlength: 8,
             maxlength: 20,
            equalTo: "#new_pwd"
        }
    },
    messages: {
        new_pwd: {
            required: "Please provide a password",
            minlength: "Your password must be at least 8 characters long",
            maxlength: "Your password must be at within 20 characters long"
        },
        confirm_pwd: {
            required: "Please provide a password",
            minlength: "Your password must be at least 8 characters long",
            maxlength: "Your password must be at within 20 characters long",
            equalTo: "Please enter the same password as above"
        }
    },
    submitHandler: function(form) {
            form.submit();
        }
});
});
</script>
<html>
	<head>
		<title>mDropinn Reset Password</title>
	</head>
<form name="reset_pwd" id="reset_pwd" method="post" action="<?php echo base_url().'mobile/user/reset_pwd/'.$id;?>">
<center>
<h3>mDropinn Reset Password</h3>
<table style="margin:0 10px 0 0;">

	</tr>
	<tr>
		<td>
			New Password :
		</td>
		<td>
			<input type='password' name='new_pwd' id='new_pwd'>
		</td>
	</tr>
	<tr>
		<td>
			Confirm Password :
		</td>
		<td>
			<input type='password' name='confirm_pwd' id='confirm_pwd'>
		</td>
	</tr>
</table>
<button type='submit' name="submit" value="submit">Submit</button>
</center>
</form>
</html>
  <style>
  label{
  	 color: #FF0000;
    float: right;
    margin: 10px 0 0 10px;
    position: absolute;
  }
  header {
    background-color: #B31122;
    color: #FFFFFF;
    display: block;
    height: 40px;
    left: 0;
    margin-bottom: 30px;
    position: fixed;
    text-align: center;
    top: 0;
    width: 100%;
    z-index: 9999;
}
input {
    background-color: #ECECEC;
    border: 0 none;
    border-radius: 4px;
    color: #666666;
    font-size: 18px;
    font-weight: 500;
    letter-spacing: 0.02em;
    outline: 0 none;
    padding: 12px 14px;
}
input {
    margin-bottom: 10px;
    width: 312px;
    font-family: inherit;
    font-size: 100%;
}
button {
    border: 0 none;
    border-radius: 22px;
    cursor: pointer;
    font-size: 18px;
    font-weight: 500;
    letter-spacing: 0.04em;
    outline: 0 none;
    padding: 12px 20px;
    margin: 10px 0;
    width: 126px;
}
body {
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    font-weight: 400;
     text-align: center;
}
.login-page h2 {
    font-weight: 300;
    margin: 60px auto 25px;
}
.login-page {
    color: #666666;
    text-align: center;
}
.login-page {
    color: #666666;
    left: 0;
    margin-top: -140px;
    position: absolute;
    text-align: center;
    top: 50%;
    width: 100%;
}
  	</style>