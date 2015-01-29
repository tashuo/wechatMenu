<?php
    if(isset($_POST['appid']) && isset($_POST['appsecret'])){
    	require_once '../../lib/Token.class.php';
    	if(tokenHandle::backupMenu($_POST['appid'], $_POST['appsecret'])){
    		echo json_encode(array('code' => 1));
    	}else{
    		echo json_encode(array('code' => 0));
    	}
    }