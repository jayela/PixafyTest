<?php

session_start();

include("blocks/db.php");

include("class-user.php");
	
$user = new user();

if (!$user->login) {
	die();
}

include("class-photos.php");

$photos = new photos();

$photos->reOrder();



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta HTTP-EQUIV="REFRESH" content="0; url=dashboard.php">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Uploading</title>
</head>

<body>
</body>
</html>