<?php
    if(isset($_POST)){
    	session_start();
    	require_once '../../lib/Token.class.php';
    	$backup_menu = file_get_contents('../../menus/'.$_SESSION['backup_filename']);
    	$len = strlen($backup_menu);
    	$str = substr($backup_menu, 8, $len-9);
    	echo tokenHandle::createMenu($_SESSION['appid'], $_SESSION['appsecret'], $str);
    }