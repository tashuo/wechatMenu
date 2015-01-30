<?php
    if(isset($_POST['appid']) && isset($_POST['appsecret'])){
    	session_start();
    	require_once '../../lib/Token.class.php';
    	if(tokenHandle::backupMenu($_POST['appid'], $_POST['appsecret'])){
    		$_SESSION['appid'] = $_POST['appid'];
    		$_SESSION['appsecret'] = $_POST['appsecret'];

    		echo json_encode(array('code' => 1));
    	}else{
    		echo json_encode(array('code' => 0));
    	}
    }