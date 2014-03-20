<?php

class photos {
	
	public function changeOwner() {
		foreach($_POST as $k => $p) {
			if (substr($k,0,5) == "image") {
				$id = substr($k,5);
				mysql_query("UPDATE photos SET user = ".$p." WHERE id = ".$id);
			}
		}
	}
	
	public function delete($id) {
		$a = mysql_query("SELECT * FROM photos WHERE id = ".$id);
		$b = mysql_fetch_array($a);
		
		if (file_exists($b['img'])) {
			unlink($b['img']);
			$a = mysql_query("DELETE FROM photos WHERE id = ".$id);
			return "success";
		}
		return "fail";
	}
	
	public function deleteMulti() {
		$done = "";
		$ids = explode("x", htmlentities($_POST['id'], ENT_QUOTES));
		
		foreach ($ids as $id) {
			if ($this->delete($id) == "success") {
				$done .= "x".$id;
			}
		}
		
		$this->updateOrder();
		
		return substr($done, 1);
	}
	
	public function updateOrder() {
		$a = mysql_query("SELECT * FROM photos ORDER BY `order` ASC");
		$i = 1;
		while ($b = mysql_fetch_array($a)) {
			mysql_query("UPDATE photos SET `order` = ".$i." WHERE id = ".$b['id']);
			$i++;
		}
	}
	
	public function reOrder() {
		$id = intval($_POST['id']);
		$num = intval($_POST['position']);
		
		$a = mysql_query("SELECT * FROM photos WHERE id <> ".$id." ORDER BY `order` ASC");
		$i = 1;
		$done = false;
		while ($b = mysql_fetch_array($a)) {
			if ($i == $num) {
				mysql_query("UPDATE photos SET `order` = ".$i." WHERE id = ".$id);
				$i++;
				$done = true;
			}
			mysql_query("UPDATE photos SET `order` = ".$i." WHERE id = ".$b['id']);
			$i++;
		}
		if (!$done) {
			mysql_query("UPDATE photos SET `order` = ".$num." WHERE id = ".$id);
		}
	}
	
	public function upload($user) {
		$col = array('image', '', 'upl');
		$l = count($_FILES[$col[0]]["name"]);
		$a = mysql_query("SELECT * FROM photos ORDER BY `order` DESC LIMIT 1");
		$lastrow = mysql_fetch_array($a);
		$lastnum = $lastrow['order']+1;
		
		
		for ($i = 0; $i < $l; $i++) {
			if ($_FILES[$col[0]]["error"][$i] > 0 && (($_FILES["file"]["type"] == "image/gif")
													|| ($_FILES["file"]["type"] == "image/jpeg")
													|| ($_FILES["file"]["type"] == "image/jpg")
													|| ($_FILES["file"]["type"] == "image/pjpeg")
													|| ($_FILES["file"]["type"] == "image/x-png")
													|| ($_FILES["file"]["type"] == "image/png"))) {
			} else {
				$r=rand(1000,9999);
				while (file_exists($col[2]."/".$r.$_FILES[$col[0]]["name"][$i])) {
					$r=rand(1000,9999);
				}
				if ($this->checkimg4virus($_FILES[$col[0]]["tmp_name"][$i])) {
					echo "Infected image";
				} else {
					if (!move_uploaded_file($_FILES[$col[0]]["tmp_name"][$i], $col[2]."/".$r.$_FILES[$col[0]]["name"][$i])) {
						echo "Error while uploading";
					} else {
						$a = mysql_query("INSERT INTO photos (`img`, `user`, `order`) VALUES ('".$col[2]."/".$r.$_FILES[$col[0]]["name"][$i]."', ".$user.", ".$lastnum.")");
						$lastnum++;
					}
				}
			}
		}
	}
	
	private function checkimg4virus($img) {
		$a = file_get_contents($img);
		return strpos($a, "<?php");
	}
	
	public function filterPhotos($rawfilter) {
		$rf = explode(",", $rawfilter);
		$filters = "";
		foreach ($rf as $f) {
			$a = mysql_query("SELECT * FROM users WHERE mail = '".$f."'");
			if (mysql_num_rows($a) > 0) {
				$b = mysql_fetch_array($a);
				$filters .= " OR user = ".$b['id'];
			}
		}
		return " WHERE".substr($filters, 3);
	}
	
	public function output($b) {
		echo '<img src="'.$b['img'].'" orderNum="'.$b['order'].'" imageId="'.$b['id'].'">';
	}
	
	public function outputAdmin($b, $d, $owners) {
		echo '<div class="adminImg"><img src="'.$b['img'].'" imageId="'.$b['id'].'"><br /><span class="owner" ownerId="'.$b['user'].'">'.$d['mail'].'</span><br /><span class="changeowner">Change to: '.str_replace('<select>', '<select name="image'.$b['id'].'">', str_replace('value="'.$b['user'].'"', 'value="'.$b['user'].'" selected="selected"', $owners)).'</span></div>';
	}
		
}

?>