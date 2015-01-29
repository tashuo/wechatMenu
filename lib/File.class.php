<?php
    Class File{
    	private static $_handle;
    	private static $_path;
    	private static $_content;
    	private static $_mode;

    	private static function _open(){
    		self::$_handle = fopen(self::$_path, self::$_mode);
    	}

    	public static function read($path){
    		return file_get_contents($path);
    	}

    	public static function write($path, $content, $mode = 'w+'){
    		self::$_path = $path;
    		self::$_mode = $mode;

    		self::_open();
    		if(fwrite(self::$_handle, $content) === FALSE){
    			exit('Cannot write file: '.$path);
    		}else{
    			return true;
    		}
    	}
    }