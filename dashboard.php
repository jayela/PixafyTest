<?php

session_start();

include("blocks/db.php");

include("class-user.php");
include("class-photos.php");
	
$user = new user();
$photos = new photos();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php if(!$user->login) {echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';} ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PhotoStash - <?=$user->mail?></title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="js/jquery.MultiFile.pack.js"></script>
<script type="text/javascript" src="js/dashboard.js"></script>
</head>

<body>
<div class="blackscreen">
	<img class="inner" />
    <div class="panel">
    	<div class="delete" id="deleteButton" imageId="">DELETE</div>
        <div class="reorder">
        	<form enctype="multipart/form-data" method="post" id="reorderformm" action="reorder.php" target="_self">
            	<input type="text" name="position" id="positionInput" style="text-align:center;" /><input name="id" type="hidden" value="" id="reorderId" />
                <input type="submit" value="REORDER" name="reorder" />
            </form>
        </div>
    </div>
    <div class="close">X</div>
</div>
<div class="maincontainer">
	<div class="header">
    	<div class="logo"></div>
        <div class="userinfo"><?php if ($user->status == 1) {echo '<a href="admin.php">ADMIN</a> | ';} ?><a href="#" id="changeMail">CHANGE EMAIL</a> | <a href="logout.php">LOGOUT</a><br />
        	<div class="emailChange">
            <form action="changemail.php" name="changemail" enctype="multipart/form-data" method="post" target="_self" id="changeformm">
            	<input type="text" name="mail" id="mail" label="CURRENT EMAIL" labelColor="#AAA" />
            	<input type="text" name="newmail" id="newmail" label="NEW EMAIL" labelColor="#AAA" />
            	<input type="password" name="pass" id="pass" label="PASSWORD" labelColor="#AAA" />
                <input name="change" type="submit" value="CHANGE" />
            </form>
            </div>
            <br /><span class="mail"><?=$user->mail?></span></div>
    </div>
	<?php
        $a = mysql_query("SELECT * FROM photos WHERE user = ".$user->id." ORDER BY `order` ASC");
		$q = mysql_num_rows($a);
    ?>
    <div class="mainbody">
    	<div class="toolbar">
        	<div class="left">You have total of <?=$q?> photos uploaded</div><div class="right"><img class="deleteMulti" src="img/delete.png" /> <img class="markMulti" src="img/mark_off.png" /> <img class="upload" src="img/upload.png" /></div>
        </div>
        <div class="uploadform"><form enctype="multipart/form-data" method="post" id="uploadformm" action="upload.php" target="_self"><input type="file" name="image[]" class="multi" accept="gif|jpg|png|jpeg"/><br /><input name="upload" type="submit" value="UPLOAD" style="width:282px;" /></form></div>
    	<div class="photocontainer">
			<?php
				while ($b = mysql_fetch_array($a)) {
					$photos->output($b);
				}
            ?>
        </div>
    </div>
</div>

</body>
</html>