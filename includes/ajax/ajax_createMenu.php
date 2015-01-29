<?php
    if(isset($_POST['appid']) && isset($_POST['appsecret'])){
    	session_start();
    	require_once '../../lib/Token.class.php';

    	$ret = json_decode(tokenHandle::createMenu($_POST['appid'], $_POST['appsecret'], $_SESSION['new_menu']), true);
    	echo tokenHandle::createMenu($_POST['appid'], $_POST['appsecret'], $_SESSION['new_menu']);
    	// if($ret['errcode'] == 0 && $ret['errmsg'] == 'ok'){
    	// 	echo json_encode(array('code' => 1));
    	// }else{
    	// 	echo json_encode(array('code' => 0));
    	// }
    }