<?php

class user {

	public $login = false;
	public $mail, $id, $status;
	public $registertext = "";
	public $logintext = "";

	public function __construct() {
		if (isset($_SESSION['login'])) {
			$this->checkLogin();
		} else {
			if (isset($_POST['register'])) {
				$this->register();
			} elseif (isset($_POST['login'])) {
				$this->login();
			}
		}
	}
	
	private function checkLogin() {
		$cred = explode(";",base64_decode(strrev($_SESSION['login'])));
		$cred[1] = strrev($cred[1]);
		$a = mysql_query("SELECT * FROM users WHERE mail = '".$cred[0]."' AND pass = '".$cred[1]."' AND verified = 1");
		if (mysql_num_rows($a) > 0) {
			$b = mysql_fetch_array($a);
			$this->login = true;
			$this->mail = $cred[0];
			$this->id = $b['id'];
			$this->status = $b['status'];
		} else {
			session_destroy();
		}
	}
	
	private function register() {
		$name = htmlentities(trim($_POST['name']), ENT_QUOTES);
		$mail = htmlentities(trim($_POST['mail']), ENT_QUOTES);
		$pass = $_POST['pass'];
		$cpass = $_POST['cpass'];
		if ($name != "" && $mail != "" && $pass != "" && $cpass != "" && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
			$error = false;
			$a = mysql_query("SELECT id FROM users WHERE mail = '".$mail."'");
			if (mysql_num_rows($a) > 0) {
				$this->registertext .= "<error>E-mail already used.</error>";
				$error = true;
			}
			if ($pass != $cpass) {
				$this->registertext .= "<error>Passwords don't match.</error>";
				$error = true;
			}
			if (!$error) {
				$a = mysql_query("INSERT INTO users (`name`, `mail`, `pass`) VALUES ('".$name."', '".$mail."', '".md5($pass)."')");
				$this->registertext = $a == true ? "<success>Your account has been created, please verify your email.</success>" : "<error>There was a problem creating your account</error>";
				if ($a) {
					mail($mail, "Verification mail for PhotoStash", "Hello ".$name."\n\nYour account has been successfully created. Please verify your email:\nhttp://".$_SERVER['HTTP_HOST']."/verify.php?hash=".strrev(base64_encode($mail.":".strrev(md5($pass)))));
				}
			}
		}
	}
	
	private function login() {
		$mail = htmlentities(trim($_POST['mail']), ENT_QUOTES);
		$pass = trim($_POST['pass']);
		if ($mail != "" && $pass != "") {
			$error = false;
			$a = mysql_query("SELECT * FROM users WHERE mail = '".$mail."' AND pass = '".md5($pass)."'");
			if (mysql_num_rows($a) > 0) {
				$b = mysql_fetch_array($a);
				if ($b['verified'] == 1) {
					$this->login = true;
					$this->mail = $b['mail'];
					$this->id = $b['id'];
					$this->status = $b['status'];
					$_SESSION['login'] = strrev(base64_encode($mail.";".strrev(md5($pass))));
				} else {
					$this->logintext = "<error>User not verified. Please check your email.</error>";
				}
			} else {
				$this->logintext = "<error>Email and password don't match.</error>";
			}
		}
	}
	
	public function changeMail() {
		$mail = htmlentities(trim($_POST['mail']), ENT_QUOTES);
		$newmail = htmlentities(trim($_POST['newmail']), ENT_QUOTES);
		$pass = $_POST['pass'];
		
		$a = mysql_query("SELECT * FROM users WHERE mail = '".$mail."' AND pass = '".md5($pass)."'");
		
		if (mysql_num_rows($a) > 0) {
			$b = mysql_fetch_array($a);
			$a = mysql_query("UPDATE users SET mail = '".$newmail."' WHERE id = ".$b['id']);
			$_SESSION['login'] = strrev(base64_encode($newmail.";".strrev(md5($pass))));
		}
	}
	
	public function userDropdown() {
		$owners = "<select>";
		$e = mysql_query("SELECT * FROM users");
		while ($f = mysql_fetch_array($e)) {
			$owners .= '<option value="'.$f['id'].'">'.$f['mail'].'</option>';
		}
		$owners .= "</select>";
		return $owners;
	}
	
}

?>