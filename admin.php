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
<?php if(!$user->login) {echo '<meta HTTP-EQUIV="REFRESH" content="0; url=index.php">';} elseif ($user->status != 1) {echo '<meta HTTP-EQUIV="REFRESH" content="0; url=dashboard.php">';} ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PhotoStash - AdminPanel</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
<script type="text/javascript">
$(function() {
	$('#showChange').click(function(e) {
        $('.changeowner').show();
    });
	
	$('#applyChange').click(function(e) {
        $('#changeownerform').submit();
    });
});
</script>
</head>

<body>

<div class="maincontainer">
	<div class="header">
    	<div class="logo"></div>
        <div class="userinfo"><a href="dashboard.php">DASHBOARD</a> | <a href="logout.php">LOGOUT</a><br /><br /><span class="mail"><?=$user->mail?></span></div>
    </div>
	<?php
		$rawfilter = "";
		$filters = "";
		if (isset($_POST['filter'])) {
			$rawfilter = htmlentities(trim($_POST['filter']), ENT_QUOTES);
			$filters = $photos->filterPhotos($rawfilter);
		}
        $a = mysql_query("SELECT * FROM photos".$filters);
		$q = mysql_num_rows($a);
    ?>
    <div class="mainbody">
    	<div class="toolbar">
        	<div class="left">You are viewing total of <?=$q?> photos<img id="showChange" src="img/changeowner.png" /><img id="applyChange" src="img/applychanges.png" /></div><div class="right">
            	
            	<form id="filterform" action="<?=$_SERVER['PHP_SELF']?>" name="filter" enctype="multipart/form-data" method="post" target="_self">
                	FILTER <small>(separate mails by comma to filter for multiple users)</small>: <input type="text" name="filter" value="<?=$rawfilter?>" /> <input type="submit" name="filterbutton" value="FILTER" />
                </form>
            </div>
        </div>
    	<div class="photocontainer">
        <form id="changeownerform" action="changeowners.php" name="changeowner" enctype="multipart/form-data" method="post" target="_self">
			<?php
				$owners = $user->userDropdown();
				while ($b = mysql_fetch_array($a)) {
					$c = mysql_query("SELECT * FROM users WHERE id = ".$b['user']);
					$d = mysql_fetch_array($c);
					$photos->outputAdmin($b, $d, $owners);
				}
            ?>
        </form>
        </div>
    </div>
</div>

</body>
</html>