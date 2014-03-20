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

echo $photos->deleteMulti();


?>