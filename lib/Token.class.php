<?php    #获取token，存储到数据库，token有效期为7200s
    Class tokenHandle{
    	const URL_GET_TOKEN = 'https://api.weixin.qq.com/cgi-bin/token';
            const URL_GET_MENU = 'https://api.weixin.qq.com/cgi-bin/menu/get';
            const URL_CREATE_MENU = 'https://api.weixin.qq.com/cgi-bin/menu/create';
    	const TOKEN_LIMIT_TIME = 7200;

    	private static $grant_type = 'client_credential';
            private static $_appID;
            private static $_appsecret;

            public $host;

    	private static function _getAccesstoken(){
                        require_once('function.php');
    		//如果没有超时则使用数据库中的token
    		if($token = self::_getTokenFromFile()){
    			return $token;
    		}

    		$params = array('grant_type' => self::$grant_type,
    				    'appid' => self::$_appID,
    				    'secret' => self::$_appsecret,
    				    );
    		$url = self::URL_GET_TOKEN.'?'.http_build_query($params);
    		$data = json_decode(self::curl_get($url), true);
    		$token = $data['access_token'];

                        //将新获取的token存储到文件
                        // $this->_writeTokenToFile('token/'.$this->host, $token.' '.time());
    	
    		return $token;
    	}

    	//从文件中获取token，判断是否已过期
    	private static function _getTokenFromFile(){
    		return false;
                        if(file_exists('token/'.$this->host)){
                            require_once('File.class.php');
                            $tokenData = File::read('token/'.$this->host);

                            $token = substr($tokenData, 0, strpos(' ', $tokenData));
                            $oldTime = substr($tokenData, strpos(' ', $tokenData)+1);
                            if(time() - $oldTime < self::TOKEN_LIMIT_TIME){
                                return $token;
                            }
                        }
    	}

            private function _writeTokenToFile($path, $data){
                        require_once('File.class.php');
                        File::write($path, $data);
            }

            private static function getMenu(){
                        $token = self::_getAccesstoken();
                        $url = self::URL_GET_MENU.'?access_token='.$token;
                        $menu = self::curl_get($url);
                        return $menu;
            }

            public static function backupMenu($appid, $appsecret){
            	self::$_appID = $appid;
                        self::$_appsecret = $appsecret;

                        $menu = self::getMenu();

                        require_once('File.class.php');
                        $filename = date('YmdHis', time()).'.txt';
                        $path = '../../menus/'.$filename;
                        $_SESSION['backup_filename'] = $filename;
                        return File::write($path, $menu);
            }

            public static function createMenu($appid, $appsecret, $menu){
            	self::$_appID = $appid;
                        self::$_appsecret = $appsecret;

                        $url = self::URL_CREATE_MENU.'?access_token='.self::_getAccesstoken();
                        return self::curl_post($url, $menu);
            }


            /**
             * cURL GET方式 获取远程链接内容 捕捉http重定向
             * @param string $url   URL地址
             * @param int $timeout 超时时间
             * @return mixed
             */
            public static function curl_get($url = '', $timeout = 3) {
                $ch = curl_init();
                $param = array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => 0,
                    CURLOPT_TIMEOUT => $timeout,
                    CURLOPT_ENCODING => 'gzip,deflate',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_MAXREDIRS => 5,
                );
                curl_setopt_array($ch, $param);
                $rs = curl_exec($ch);
                return $rs;
            }
            
            /**
             * cURL POST方式 获取远程链接内容
             * @param string $url
             * @param array $data
             * @param int $timeout
             * @return mixed
             */
            public static function curl_post($url = '', $data = array(), $timeout = 3) {
            
                $ch = curl_init();
                $param = array(
                    CURLOPT_URL => $url,
                    CURLOPT_HEADER => 0,
                    CURLOPT_TIMEOUT => $timeout,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $data,
                    CURLOPT_RETURNTRANSFER => true,
                );
            
                curl_setopt_array($ch, $param);
                $rs = curl_exec($ch);
                return $rs;
            }

    }