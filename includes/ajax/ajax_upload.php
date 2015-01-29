<?php
    if(isset($_FILES['excel'])){
    	session_start();
    	require_once '../../lib/reader.php';
    	if(move_uploaded_file($_FILES['excel']['tmp_name'], '../../excel/'.$_FILES['excel']['name'])){

    		$excel = new Spreadsheet_Excel_Reader();
    		$excel->setOutputEncoding('Utf-8');
    		$excel->read('../../excel/'.$_FILES['excel']['name']);
	
    		$first_menu = array();
    		$second_menu = array();
	
    		//获取一级菜单
    		if(is_null($excel->sheets[0]['cells'][3][1])){
    			exit(json_encode(array('code'=>-1)));
    		}
    		
    		$first_menu['3'] = $excel->sheets[0]['cells'][3][1];
    		for($i = 4;$i <= $excel->sheets[0]['numRows'];$i ++){
    			if(!isset($excel->sheets[0]['cells'][$i][1])){
    				continue;
    			}else{
    				if(count($first_menu) == 3){
    					exit(json_encode(array('code'=>-1)));
    				}
	
    				$first_menu[$i] = $excel->sheets[0]['cells'][$i][1];
    			}
    		}
	
	
    		//获取二级菜单
    		$rows = array_keys($first_menu);
    		$count = count($rows);
    		for($i = 0;$i < $count;$i ++){
    			if(isset($excel->sheets[0]['cells'][$rows[$i]][2]) && !empty($excel->sheets[0]['cells'][$rows[$i]][2])){
    				$second_menu[] = $excel->sheets[0]['cells'][$rows[$i]][2];
    				continue;
    			}else{
    				//最后一个菜单，此处要做判断，如果第三个菜单也有二级菜单此处不做判断会出错
    				if($count - $i == 1){
    					$til_row = $excel->sheets[0]['numRows'];
    				}else{
    					$til_row = $rows[$i+1];
    				}
	
    				$tmp = array();
    				for($j = $rows[$i];$j < $til_row;$j ++){
    					if(!isset($excel->sheets[0]['cells'][$j][3]) || !isset($excel->sheets[0]['cells'][$j][4])){
    						// exit('second menu column cannot be empty');
    						exit(json_encode(array('code'=>-1)));
    					}
	
    					$tmp[] = array($excel->sheets[0]['cells'][$j][3] => $excel->sheets[0]['cells'][$j][4]);
    				}
	
    				$second_menu[] = $tmp;
    			}
    		}
    		
    		$json_menu = '{"button": [';
	
    		$arr = array_combine(array_values($first_menu), array_values($second_menu));
    		foreach ($arr as $key => $value) {
    			if(count($value) == 1){
    				$tmp_str = '{"type": "view", "name": "'.$key.'", "url": "'.$value.'"},';
    			}else{
    				$tmp_str = '{"name": "'.$key.'", "sub_button": [';
    				foreach ($value as $k => $v) {
    					foreach($v as $name => $url){
    						$tmp_str .= '{"type": "view", "name": "'.$name.'", "url": "'.$url.'"},';
    					}
    				}
    				$tmp_str = substr($tmp_str, 0, -1);
    				$tmp_str .= ']},';
    			}
	
    			$json_menu .= $tmp_str;
    		}

    		$json_menu = substr($json_menu, 0, -1);
    		$json_menu .= ']}';

    		//判断是否是json格式
    		if(is_null(json_decode($json_menu))){
    			exit(json_encode(array('code'=>-1)));
    		}

		$_SESSION['new_menu'] = $json_menu;

    		echo json_encode(array('code' => 1));
    	}else{
    		echo json_encode(array('code' => 0));
    	}
    	
    }