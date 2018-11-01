<?php
/**
 * 公共函数类
 *
 */
class Fn {
    /**
      * 判断当前环境php版本是否大于大于等于指定的一个版本
      * @param sreing $version default=5.0.0
      * @return boolean
      * @author weiwenchao <wenchaowei@sohu-inc.com>
      */
     public static function is_php($version = '5.5.0') {
         $php_version = explode( '-', phpversion());
         // =0表示版本为5.0.0  ＝1表示大于5.0.0  =-1表示小于5.0.0
         $is_pass = strnatcasecmp( $php_version[0], $version ) >= 0 ? true : false;
         return $is_pass;
     }

    /**
     * 用 mb_strimwidth 来截取字符，使中英尽量对齐。
     *
     * @param string $str
     * @param int $start
     * @param int $width
     * @param string $trimmarker
     * @return string
     */
    public static function wsubstr($str, $start, $width, $trimmarker = '...') {
        $_encoding = mb_detect_encoding($str, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
		$_encoding = $_encoding ? $_encoding : 'UTF-8';
        return mb_strimwidth($str, $start, $width, $trimmarker, $_encoding);
    }

    public static function url_login_ru($ru = null)
    {
        $refer = Yii::app() -> getRequest() ->urlReferrer ? Yii::app() -> getRequest() ->urlReferrer : JJ_DOMAIN.$ru; 
        $url = JJ_DOMAIN.'/login/callback?referer='.$refer;
        return rawurlencode($url);
    }

    public static function url_login_current()
    {
        $url = JJ_DOMAIN.'/login/callback?referer='.JJ_DOMAIN.Yii::app() -> getRequest() ->getUrl() ;
        return rawurlencode($url);
    }

    /**
     * 过滤浮点数，如果是整数返回整数
     *
     * @param string $str
     * @return string
     */
    public static function filterFloat($str) {
        if ($str == intval($str)) {
            return $str;
        }
        return sprintf("%0.2f", $str);
    }

    /**
     * 实现PHP内部函数 trim 处理多维数组。
     *
     * @param string|array &$data
     * @param string $charlist
     */
    public static function retrim($data, $charlist = null) {
        if (is_array($data)) {
            foreach ($data as $item) {
                $data = self::retrim($item);
            }
        } else {
            $data = trim($data, $charlist);
        }

        return $data;
    }
    
    /**
     * 检查字符串的字符数
     * @param string $var
     */
    static function CountStrChar($var, $encoding = 'utf-8'){
        return mb_strlen($var, $encoding);
    }
    
    /**
     * 判断并转换字符编码，需 mb_string 模块支持。
     *
     * @param mixed $str 数据
     * @param string $encoding 要转换的编码类型
     * @return mixed 转换过的数据
     */
    public static function encodingConvert($str, $encoding = 'UTF-8') {
        if (is_array($str)) {
            $arr = array();
            foreach ($str as $key => $val) {
                $arr[$key] = self::encodingConvert($val, $encoding);
            }
            return $arr;
        }
        $_encoding = mb_detect_encoding($str, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
        if ($_encoding == $encoding) {
            return $str;
        }
		try {
			$str = @mb_convert_encoding($str, $encoding, $_encoding);
		} catch(Exception $e) {
			//nothing todo
		}
		return $str;
    }

    /**
     * 获取IP地址，可能获取代理IP地址。
     *
     * @return string
     */
    public static function getIp() {
        static $ip = false;

        if (false != $ip) {
            return $ip;
        }

        $keys = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );

        foreach ($keys as $item) {
            if (!isset($_SERVER[$item])) {
                continue;
            }

            $curIp = $_SERVER[$item];
            $curIp = explode('.', $curIp);
            if (count($curIp) != 4) {
                break;
            }

            foreach ($curIp as & $sub) {
                if (($sub = intval($sub)) < 0 || $sub > 255) {
                    break 2;
                }
            }

            $curIpBin = $curIp[0] << 24 | $curIp[1] << 16 | $curIp[2] << 8 | $curIp[3];
            $masks = array(// hexadecimal ip  ip mask
                array(0x7F000001, 0xFFFF0000), // 127.0.*.*
                array(0x0A000000, 0xFFFF0000), // 10.0.*.*
                array(0xC0A80000, 0xFFFF0000) // 192.168.*.*
            );
            foreach ($masks as $ipMask) {
                if (($curIpBin & $ipMask[1]) === ($ipMask[0] & $ipMask[1])) {
                    break 2;
                }
            }

            return $ip = implode('.', $curIp);
        }

        return $ip = $_SERVER['REMOTE_ADDR'];
    }

    /**
     * 加密，解密方法。
     *
     * @param string $string
     * @param string $key
     * @param string $operation encode|decode
     * @return string
     */
    public static function crypt($string, $key, $operation = 'encode') {
        $keyLength = strlen($key);
        $string = (strtolower($operation) == 'decode') ? base64_decode($string) : substr(md5($string . $key), 0, 8) . $string;
        $stringLength = strlen($string);
        $rndkey = $box = array();
        $result = '';

        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $keyLength]);
            $box[$i] = $i;
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $stringLength; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        //var_dump($result);
        //echo mb_detect_encoding($result);exit();
         //mb_convert_encoding($str, "UTF-8", "GBK");
       // echo $result;
        if (strtolower($operation) == 'decode') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8) . $key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return base64_encode($result);
        }
    }

    /**
     * 通过CURL库进POST数据提交
     *
     * @param string $postUrl  url address
     * @param array $data  post data
     * @param int $timeout connect time out
     * @param bool $debug 打开 header 数据
     * @return string
     */
    public static function curlPost($postUrl, $data = array(), $timeout = 30, $debug = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $postUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, $debug);
        curl_setopt($ch, CURLINFO_HEADER_OUT, $debug);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data, 'pre_', '&'));

        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === false) {
            return $result;
        }

        return trim($result);
    }
    /**
    * 获取url返回值，curl方法
    */
   public static function curlGet($url, $timeout = 1)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $ret = curl_exec($ch);
        curl_close($ch);
        return $ret;
    }

    /**
     * 对 SQL like 值进行转义。
     *
     * @param string $keyword
     * @return string
     */
    public function escapeKeyword($keyword) {
        return strtr($keyword, array('%' => '\%', '_' => '\_'));
    }

    /**
     * 字符串转义
     *
     * @param string $string
     * @return string
     */
    public static function daddslashes($string) {
		return $string;
        static $magic = null;
        if ($magic === null) {
            $magic = (bool) get_magic_quotes_gpc();
        }

        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = self::daddslashes($val);
            }
        } else {
            $string = $magic ? $string : addslashes($string);
        }
        return $string;
    }

    /**
     * 输入过滤
     *
     * @param string $k
     * @param string $var	分别代表不同超全局变量
     * @return array
     * @deprecated {@see superVar()}
     */
    public static function getgpc($k, $var = 'R') {
        switch ($var) {
            case 'G':
                $var = &$_GET;
                break;
            case 'P':
                $var = &$_POST;
                break;
            case 'C':
                $var = &$_COOKIE;
                break;
            case 'R':
                $var = &$_REQUEST;
                break;
        }
        return isset($var[$k]) ? self::daddslashes($var[$k]) : null;
    }

    /**
     * 对外部来源超全局变量进行转义
     *
     * @param string $key
     * @param string $type
     * @param mixed $default
     * @return mixed
     */
    public static function superVar($key = null, $type = 'R', $default = null) {
        switch ($type) {
            case 'G':
                $var = $_GET;
                break;
            case 'P':
                $var = $_POST;
                break;
            case 'C':
                $var = $_COOKIE;
                break;
            default:
                $var = $_REQUEST;
                break;
        }

        if ($key === null) {
            //return self::daddslashes($var);
			return $var;
        }

        return isset($var[$key]) ? $var[$key] : $default;
    }

    /**
     * 用于显示 yiidebugtb调试
     *
     * @param bool $on
     * @param bool $return
     * @return bool
     */
    public static function debug($on = true, $return = false) {
        static $debug = false;
        if ($return) {
            return $debug;
        }

        $debug = $on;
    }

    /**
     * 处理数组生成sql
     *
     * @param array $arr
     * @param string $k
     * @return string
     */
    public static function createSqlIn($arr, $k) {
        $str = array();
        foreach ($arr as $v) {
            $str[] = $v[$k];
        }
        return implode(', ', $str);
    }

    /**
     * 格式化价格
     *
     * @param float $price
     * @param int $float 精确到小数点后$float位
     * @return array
     */
    public static function formatPrice($price, $float = 2, $is_abs = false) {
        if ($is_abs)
            $price = abs($price);
        return number_format($price, $float, '.', '');
    }

    /**
     * 把汉字转换为拼音
     *
     * @param string str
     * @param bool 是否保留非中文字符
     * @return array
     */
    public static function gbkToSpell($str, $literal = false) {
        static $spellTable = null;
        if ($spellTable === null) {
            require_once JJ_ROOT . '/include/config/spell.php';
        }

        $str = trim($str);
        $len = strlen($str);
        if (!$len) {
            return;
        }

        $all = $pre = '';
        for ($i = 0; $i < $len; $i++) {
            $ord_code = ord(substr($str, $i, 1));
            if ($ord_code > 0xa0) {
                $t = substr($str, $i, 3);
                $s = $spellTable[$t];
                $pre .= substr($s, 0, 1);
                $all .= $s;
                $i += 2;
            } elseif ($literal) {
                $pre .= $str[$i];
                $all .= $str[$i];
            }
        }
        unset($spellTable);
        return array($all, $pre);
    }

    /**
     * 打印测试数据
     * @param $object 需要打印的对象
     * @param $t 打印方式
     * @return array
     */
    public static function dump($object, $t = 0) {
        if ($t == 0) {
            print_r($object);
            exit();
        } elseif ($t == 1) {
            print_r(JHelper::toArray($object));
            exit();
        } elseif ($t == 2) {
            var_dump($object);
            exit();
        } elseif ($t == 3) {
            exit($object);
        }
    }

    /**
     * 产生分页链接
     *
     * @param string $format
     * @param int $page
     * @param bool $isReplace
     * @return string
     */
    public static function pagerLink($format, $page, $isReplace = false) {
        if ($isReplace) {
            return str_replace('{%d}', $page, $format);
        }
		//rhf 2011-10-31
		$search = array("'page=%d'", "'-%d'", "'/page/%d'");
		$replace = array("page={$page}", "-{$page}", "/page/{$page}");
		return preg_replace($search, $replace, $format);
        return sprintf($format, $page);
    }

    /**
     * 清除squid缓存
	 * @return void
     */
    public static function clearCache() {
        header('Expires: Thu, 01 Jan 1970 00:00:01 GMT');
        header('Cache-Control: no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
    }

    /**
     * 根据图片URL按规格生成大小图片
     * @param $url 原始图片URL（绝对地址）
	 * @param $dsc 新图片相对地址
	 * @param $size 新图片尺寸
	 * @param $mode 处理图片模式1,普通，2补白
     * @return void
     */
    public static function thumbnail($url, $dsc, $size='50x50', $mode=1) {
        require_once 'HTTP/Request.php';
        //如果目标图已经存在退出
        $req = new HTTP_Request(JHelper::vfsUrl($dsc));
        $req->sendRequest();
        $dscData = $req->getResponseBody();
        if ($req->_response->_code != 404 && $dscData) {
            return;
        }
        //判断原图是否存在
        $req = new HTTP_Request($url);
        $req->sendRequest();
        $data = $req->getResponseBody();
        if (!$data) {
            return;
        }
        $tmpSrc = tempnam($_SERVER['CACHE_DIR'], 'src');
        file_put_contents($tmpSrc, $data);
        $imk = new JImagick($tmpSrc);
        $imk->thumbnail($size, $mode);
        $tmpDsc = tempnam($_SERVER['CACHE_DIR'], 'dsc');
        $imk->save($tmpDsc);
        //写vfs
        $vfs = new JVfs();
        $vfs->rsyncWrite($dsc, $tmpDsc, true);
        @unlink($tmpSrc);
        @unlink($tmpDsc);
    }

    /**
     * 对字符进行显示处理
     * @param $str 需要处理的字符串
	 * @param $charest 是否需要指定字符
     * @return string
     */
	public static function encode($str='', $charest='') {
		if(trim($str) == '') {
			return ;
		}
		if($charset) {
			return htmlspecialchars($str, ENT_QUOTES, $charset);
		}
		return htmlspecialchars($str, ENT_QUOTES);
	}

    /**
     * 对数组中字符进行显示处理
     * @param $data 需要处理的数组
	 * @param $charest 是否需要指定字符
     * @return array
     */
	public static function encodeArray($data, $charset=''){
		$d = array();
		foreach($data as $key => $value){
			if(is_string($key))
				$key = self::encode($key, $charset);
			if(is_string($value))
				$value = self::encode($value, $charset);
			else if(is_array($value))
				$value = self::encodeArray($value);
			$d[$key] = $value;
		}
		return $d;
	}

    /**
     * 转意特殊字符,安全SQL查询
	 * @param $keyword string 需要处理的字符串
	 * @return string;
	 */
    public static function translateSqlStr($keyword) {
        if ($keyword) {
			return strtr($keyword, array('%' => '\%', '_' => '\_', '\\' => '\\\\', '<"' => '\<"', '">' => '\">'));
        }else
        {
            return '';
        }
    }

	/**
	 * 将url中的中文字符urlencode
	 * @param $string string 需要处理的字符串
	 * @return string;
	 */
	public static function urlencode($string) {
		if(empty($string))return '';
	    $string = trim($string);
	    $length = strlen($string);
		$output = '';
	    while ($n < $length) {
	        $t = ord($string[$n]);
	        if (224 <= $t && $t < 239) {
	        	$tmpStr = $string[$n].$string[$n+1].$string[$n+2];
	        	$output .= urlencode($tmpStr);
	            $tn = 3;
	            $n += 3;
	            $noc += 2;
	        } else {
	        	$tmpStr = $string[$n];
	        	$output .= $tmpStr;
	            $n++;
	        }
	        if ($noc >= $length) {
	            break;
	        }
	    }
		return trim($output);
	}
	
	/**
	 * 将url encode，把':'、'/'、'+'等原样返回...
	 * @param $string string 需要处理的字符串
	 * @return string;
	 */
	public static function uEncode($returnurl) 
	{
		$returnurl = urlencode($returnurl);
		$returnurl = str_replace(array('%3A', '%2F', '%2B','%3F','%3D'), array(':', '/', '+', '?', '='), $returnurl);
		return $returnurl;
	}

	/**
	 * 将数组转换成xml
	 * @param $data array 调用时只需传入此参数
	 * @param $key string 内部递归时使用
	 * @return xml|void;
	 */
	public static function array2xml($data, $key=''){
		if(!is_array($data)){
			return $data;
		} else {
			foreach($data as $k => $v){
				if(is_numeric($k)){
					$xml .= "<{$key}>".Fn::array2xml($v)."</{$key}>\n";
				}else if(is_array($v) && array_keys($v) === range(0, count($v)-1)){
					$xml .= Fn::array2xml($v, $k);
				}else{
					$xml .= "<{$k}>".Fn::array2xml($v, $k)."</{$k}>\n";
				}
			}
			return $xml ? $xml : '';
		}
	}

	/**
	 * 编辑器内容安全检查
	 * @param $html Strin 需要检查的html
	 * @param $uri String 过滤外部域名
	 * @return String
	 */
	public static function htmlPurifier($html,$uri=JJ_DOMAIN){
		$pf = new CHtmlPurifier();
		$pf->options = array('URI.Munge' => $uri, 'Attr.AllowedFrameTargets' => array('_top','_parent','_self','_blank'));
		$html = $pf->purify($html);
		$html = html_entity_decode($html);
		$search = array(
			"/font-family:\"(.+)\";/Ui",
			"/background-image:url\(\"(.+)\"\);/Ui"
		);
		$replace = array(
			"font-family:'$1';",
			"background-image:url('$1');"
		);
		return preg_replace($search, $replace, $html);
	}

    /*
     * 根据电子邮件，生成最多7个字符的名称，作为nickname返回
     * @param: email string
     * @return: nickname string (strlen(nickname) <= 7)
     */
    public static function buildNickname($email)
    {
        $email = strtolower(trim(strval($email)));
        list($name, $extra) = explode('@', $email);
        if (empty($name))
            return substr($email, 0, 15);

        return self::substring($name, 15);
    }

	/*
	* https
	*/
	public static function simpleRequest( $url , $post_data = array() ,$option=array())
    {/*{{{*/
		//使用http_build_query拼接post
        if ( '' == $url )
        {
            return false;
        }
        $url_ary = parse_url( $url );
        if ( !isset( $url_ary['host'] ) )
        {
            return false;
        }
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true);

		curl_setopt( $ch, CURLOPT_HEADER, ($option['CURLOPT_HEADER']===true) );
		if($option['referer']!='')
		{
			curl_setopt( $ch, CURLOPT_REFERER, $option['referer']);
		}
		if(!empty($post_data))
		{
			curl_setopt( $ch, CURLOPT_POST, true);
			curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query($post_data));
		}
        curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)' );

        $http_header = array();
        $http_header[] = 'Connection: Keep-Alive';
        $http_header[] = 'Pragma: no-cache';
        $http_header[] = 'Cache-Control: no-cache';
        $http_header[] = 'Accept: */*';
        if(isset($option['header']))
        {
        	foreach($option['header'] as $header)
        	{
        		$http_header[] = $header;
        	}
        }
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $http_header );

        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		if ( !isset($option['timeout']))
		{
			$option['timeout'] = 15;
		}

		curl_setopt( $ch, CURLOPT_TIMEOUT, $option['timeout'] );
        $result = curl_exec( $ch );
        curl_close( $ch );
        return $result;
    }/*}}}*/
    /*
     * 截取字符串长度，支持多字节
     *
     * 参数：
     * 
    */
    public static function substring($str,$size,$skiphtml=0) {
        $ret = '';
        if ($size<=0) {
            return;
        }
        $wrap=true;
        $html=false;
        if ($size<2) $size=2;
        $j=0;
        if ($skiphtml) {
            $str=trim(strip_tags($str));
        }
        $num=strlen($str);
        for($i=0;$i<$num;$i++) {
            if ($str[$i]=="<" && !$html) {
                $html=true;
                $ret.=$str[$i];
                continue;
            }
            if ($str[$i]==">" && $html) {
                $html=false;
                $ret.=$str[$i];
                continue;
            }
            if ($html) {
                $ret.=$str[$i];
                continue;
            }
            if ($str[$i]=="\r") {
                continue;
            }
            if (($str[$i]=="\n" || $str[$i]==" " || $str[$i]=="\t") && !$space) {
                $space=true;
                $ret.=$str[$i];
                $j++;
                continue;
            } else if ($str[$i]=="\n" || $str[$i]==" " || $str[$i]=="\t") {
                $ret.=$str[$i];
                continue;
            } else {
                $space=false;
            }
            //echo "$i  ".ord($str[$i])."  $str[$i]"."  <br>"; 
            if (ord($str[$i])>128) {
                if($i<$num-1){
                  $j++;
                  $ret.=$str[$i];
                  $i++;
                  $j++;
                  $ret.=$str[$i];
                }
            }else{
               $j++;
               $ret.=$str[$i];
            }
            if($j>=$size) break;
        }
        return $ret;
    }

    /*
    * 判断是否为手机访问web
    * date 2013 11 15
    * email：taozhang202948@sohu-inc.cn
    * return boolen
    */
    public static function is_mobile_request()  
    {  
      $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';  
      $mobile_browser = '0';  
      if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))  
        $mobile_browser++;  
      if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))  
        $mobile_browser++;  
      if(isset($_SERVER['HTTP_X_WAP_PROFILE']))  
        $mobile_browser++;  
      if(isset($_SERVER['HTTP_PROFILE']))  
        $mobile_browser++;  
      $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));  
      $mobile_agents = array(  
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',  
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',  
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',  
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',  
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',  
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',  
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',  
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',  
            'wapr','webc','winw','winw','xda','xda-' 
            );  
      if(in_array($mobile_ua, $mobile_agents))  
        $mobile_browser++;  
      if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)  
        $mobile_browser++;  
      // Pre-final check to reset everything if the user is on Windows  
      if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)  
        $mobile_browser=0;  
      // But WP7 is also Windows, with a slightly different characteristic  
      if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)  
        $mobile_browser++;  
      if($mobile_browser>0)  
        return true;  
      else
        return false;  
    }

    /*数据转换  待扩展~*/
    public static function exCHANge($num)
    {
         if ($num/10000 > 1) 
         {
             return floor($num/10000).'万';
         }else
         {

            return $num;
         }
    }

}
