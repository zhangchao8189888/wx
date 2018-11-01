<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 15-3-26
 * Time: 上午9:54
 */


/**
 * 与项目相关的静态类
 *
 */
class FHelper
{
    public static function link($route, $params = array(), $ampersand = '&')
    {
        if (!empty($params) && is_array($params)) {
            $params = http_build_query($params, null, $ampersand);
        } else {
            $params = false;
        }

        return Yii::app()->getBaseUrl() . '/' . trim($route, '/') . '/' . (($params === false) ? '' : '?' . $params);
    }

    /**
     * 将CActiveRecord转换为数组
     *
     * @param array|CActiveRecord $list
     * @return array
     */
    public static function toArray($list)
    {
        if ($list instanceof CActiveRecord) {
            return $list->attributes;
        }

        $data = array();
        if (is_array($list)) {
            foreach ($list as $item) {
                $data[] = $item->attributes;
            }
            return $data;
        }

        return $list;
    }

    /**
     * 从 CActiveRecord::findAll()返回结果中，取出某列
     *
     * @param array $model
     * @param string $key
     * @return array
     */
    public static function listColumn($model, $key)
    {
        $res = array();
        foreach ($model as $item) {
            $res[] = $item[$key];
        }

        return $res;
    }

    /**
     * 创建缓存键
     *
     * @param string $className 类名
     * @param string $function 方法名
     * @param array $options
     * @return string
     */
    public static function createCacheKey($className, $function, $options = array())
    {
        array_push($options, $className, $function);
        return Yii::app()->getId() . '.' . implode('.', $options);
    }

    // 说明：获取 _SERVER['REQUEST_URI'] 值的通用解决方案
    function requestUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        return $uri;
    }

    /**
     * CSS,JS域名
     */
    public function assetUrl($asset, $isVersion = false)
    {
        $asset = self::_staticUrl($asset);
        if(strstr($asset, '.css')) {
            return '<link href="'.$asset.'" rel="stylesheet" type="text/css"/>';
        } else {
            return '<script src="'.$asset.'" type="text/javascript"></script>';
        }
    }

    /**
     * 随机生成静态域名
     */
    private function _staticUrl($as)
    {
        if(strpos($as,'jpg') !== false && strpos($as,'logo') === false && $_SERVER['SERVER_ADDR'] == '127.0.0.1'){
            return "http://pic.jiaju.com/".$as;
        }
        $crc = intval(substr(crc32($as), -1));
        $crc = ($crc > 5) ? ($crc-5) : ($crc==0?'':$crc);
        return "http://static{$crc}.jiaju.com/jiaju/com/".ltrim($as, '/');
    }


    /**
     * 检测日期格式是否合法且为有交效的日期
     * @param $date
     * @param $delimiter 日期必需有分隔符且不能为#号
     */
    static function FilterDate($date,$delimiter = '-'){
        if(!$date) return false;

        $pre = '#^\d{4}'.$delimiter.'\d{2}'.$delimiter.'\d{2}$#';
        if(!preg_match($pre,$date)) return false;
        $date = explode($delimiter,$date);
        if(!checkdate($date[1],$date[2],$date[0])) return false;
        return true;
    }

    /**
     * 检查是否为空，为空返回true
     * @param $str
     * @param $checkEmpty 是否检查空值如0等
     */
    static function FilterEmpty($str , $checkEmpty = true){
        $flag = (bool) preg_match('/^\s*$/',$str);
        !$flag && $checkEmpty && $flag = empty($str);
        return $flag;
    }

    /**
     * 检查是否符合指定正则要求,不符合返回false
     * @param $str
     * @param $re
     */
    static function FilterRegex($str,$re){
        return @preg_match($re,$str);
    }

    /**
     *
     *   手机号检测
     */
    static function FilterPhoneRegex($str){
        $relate = new Shop_Relate_Relate;
        $return_arr = array();
        if($str){
            $return_arr = $relate->filter_reciever($str, 'sms');
        }
        return $return_arr['valid_num'] > 0;
    }

    /**
     * 手机号格式检测
     * @param string $val
     * @return bool true检测成功，false失败
     */
    static function FilterPhone($val){
        return self::FilterRegex($val,'/^1\d{10}$/');
    }

    /**
     * 邮箱验证
     * @param $email
     * @return bool
     */
    static function check_email($email) {
        $pattern_test = "/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        return  preg_match($pattern_test,$email);
    }
    /**
     * 验证字符串是否为合法日期
     * @param string $str
     * @return 合法返回true，不合法返回false
     */
    static function is_valid_date($str)
    {
        return !(strtotime($str)===false);
    }

    /**
     * 判断参数中是否有此数据，存在为true
     * @param string $key
     * @return bool
     */
    static function RequestIsset($key){
        return isset($_REQUEST[$key]);
    }

    /**
     * 检查长度是否超出限制，超出返回true
     * @param string $str
     * @param int $length
     * @return bool
     */
    static function lengthLimit($str, $length){
        return Fn::CountStrChar($str) > (int) $length;
    }

    /**
     * 封装JSON数据返回
     * @param array $content 传给JS的数据
     * @param string $key    error配置文件中的key
     * @param string $msg    key对应的msg信息
     * @return string
     */
    public static function json($content = NULL, $key = '100002', $msg = array())
    {
        $default = array('status'=>$key);
        // get msg
        $tmp_msg = Yii::t('msg',$key);
        if($tmp_msg && $msg && is_array($msg))
        {
            $tmp_msg = str_replace(self::packDync(array_keys($msg)), array_values($msg), $tmp_msg);
        }

        !empty($tmp_msg) && $default['msg'] = $tmp_msg;
        !empty($content) && $default['content'] = $content;
        return json_encode($default);
    }

    /**
     * 封装成功的JSON数据返回(参数说明见jsonError)
     * @param array $content
     * @param string $msg
     * @param string $key
     * @return string
     */
    public static function jsonSucc($content = NULL, $msg = array(),$key = 'global_ok')
    {
        return self::jsonError($content, $key = 'global_ok', $msg);
    }

    /**
     * 字符串转换成数组
     * @param string $str
     * @param 分隔符 $separator
     * @return array
     */
    public static function strToArray($str, $separator = ','){
        return explode($separator, $str);
    }

    /**
     * 根据参数封装成要替换的动态参数
     * @param array $dync
     */
    public static function packDync($dync){
        $ret = array();
        $dync = (array) $dync;

        if(!empty($dync)){
            foreach ($dync as $val){
                $ret[] = '{'.$val.'}';
            }
        }
        return $ret;
    }

    /**
     * 转换为字节
     * @param int $size
     * @return int
     */
    public static function sizeToByte($size){
        $unit = strtolower(substr($size, -1));
        $size = substr($size, 0,-1);

        if(in_array($unit, array('m','k'))){
            switch ($unit) {
                case 'm':
                    $size *= 1024;
                case 'k':
                    $size *= 1024;
                default:
                    break;
            }
        }

        return $size;
    }

    /**
     * 生成随机数字
     * @param int $length
     * @return int
     */
    public static function generate_code($length = 6) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }
    public static function auth_code($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $ckey_length = 4;
        //note 随机密钥长度 取值 0-32;
        //note 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        //note 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        //note 当此值为 0 时，则不产生随机密钥

        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++)
        {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++)
        {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++)
        {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE')
        {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
            {
                return substr($result, 26);
            }
            else
            {
                return '';
            }
        }
        else
        {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }
    public  static  function AssignTabMonth($date,$step){
        $date= date("Y-m-d",strtotime($step." months",strtotime($date)));//得到处理后的日期（得到前后月份的日期）
        $u_date = strtotime($date);
        $days=date("t",$u_date);// 得到结果月份的天数

        //月份第一天的日期
        $first_date=date("Y-m",$u_date).'-01';
        for($i=0;$i<$days;$i++){
            $for_day=date("Y-m-d",strtotime($first_date)+($i*3600*24));
        }
        $time = array ();
        $time["first"]  =    $first_date;
        $time["last"]   =      $for_day;
        return $time;
    }
    public static function fileUpload($path) {

        $errorMsg = "";
        $returnArray = array();
        $fileArray = explode(".",$_FILES ['file'] ['name']);
        $fullFilePath = $path . $fileArray [0] . "." . $fileArray [1];
        if (count ( $fileArray ) < 2) {
            $returnArray['val'] = false;
            $returnArray['errorMsg'] = '文件名格式 不正确';
            return $returnArray;
        }
        if ($_FILES ['file'] ['error'] != 0) {
            $error = $_FILES ['file'] ['error'];
            switch ($error) {
                case 1 :
                    $errorMsg = '1,上传的文件超过了php.ini中  upload_max_filesize选项限制的值.';
                    break;
                case 2 :
                    $errorMsg = '2,上传文件的大小超过了HTML表单中MAX_FILE_SIZE  选项指定的大小';
                    break;
                case 3 :
                    $errorMsg = '3,文件只有部分被上传';
                    break;
                case 4 :
                    $errorMsg = '4,文件没有被上传';
                    break;
                case 6 :
                    $errorMsg = '找不到临文件夹';
                    break;
                case 7 :
                    $errorMsg = '文件写入失败';
                    break;
            }
        }
        if ($errorMsg != "") {
            $returnArray['val'] = false;
            $returnArray['errorMsg'] = $errorMsg;
            return $returnArray;
        }
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path,0777);

        }
        if (! move_uploaded_file ( $_FILES ['file'] ['tmp_name'], $fullFilePath )) { // 上传文件
            $returnArray['val'] = false;
            $returnArray['errorMsg'] = "文件导入失败";
            return $returnArray;
        } else {

            $returnArray['val'] = true;
            $returnArray['errorMsg'] = "文件导入成功";
            $returnArray['file_path'] = $fullFilePath;
            return $returnArray;
        }

    }
}