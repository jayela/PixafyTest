<?php

session_start();

include("blocks/db.php");
	
include("class-user.php");
	
$user = new user();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if($user->login) {echo '<meta HTTP-EQUIV="REFRESH" content="0; url=dashboard.php">';} ?>
<title>PhotoStash</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="js/main.js"></script>
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600|Roboto+Condensed:300,700' rel='stylesheet' type='text/css'>
</head>

<body>

<div class="maincontainer">
	<div class="forms">
    	<div class="registerform">
        	<title>REGISTER</title>
        	<form action="<?=$_SERVER['PHP_SELF']?>" name="registerr" enctype="multipart/form-data" method="post" target="_self" id="registerformm">
            	<input type="text" name="name" id="name" label="FULL NAME" labelColor="#AAA" />
            	<input type="text" name="mail" id="mail" label="EMAIL" labelColor="#AAA" />
            	<input type="password" name="pass" id="pass" label="PASSWORD" labelColor="#AAA" />
            	<input type="password" name="cpass" id="cpass" label="CONFIRM PASSWORD" labelColor="#AAA" />
                <input name="register" type="submit" value="REGISTER" />
            </form>
            <?=$user->registertext?>
        </div><div class="loginform">
        	<title>LOGIN</title>
        	<form action="<?=$_SERVER['PHP_SELF']?>" name="loginn" enctype="multipart/form-data" method="post" target="_self" id="loginformm">
            	<input type="text" name="mail" id="lmail" label="EMAIL" labelColor="#AAA" />
            	<input type="password" name="pass" id="lpass" label="PASSWORD" labelColor="#AAA" />
                <input name="login" type="submit" value="LOGIN" />
            </form>
            <?=$user->logintext?>
        </div>
    </div>
</div>

</body>
</html>