<?php
//$test = HTTP_SDK::getInstance($username,$password);
//$test->getAmount();
class HTTP_SDK {
	private $_rpcClient = null;
	private $cpId = "";
	private $cpPwd = "";
	private $server = "";
	/**
	 *
	 * @var SMS_SDK
	 */
	private static $_self = null;
	public static function getInstance($cpId, $cpPwd, $server = "http://hl.my2my.cn") {
		if (null == self::$_self) {
			self::$_self = new HTTP_SDK ( $cpId, $cpPwd, $server );
		}

		return self::$_self;
	}
	private function __construct($cpId, $cpPwd, $server) {
		$this->cpId = $cpId;
		$this->cpPwd = $cpPwd;
		$this->server = $server;
	}
	public function pushMt($phone,$spnumber,$content,$extend) {
		$content=iconv("utf-8","gbk",$content);//这里需要转换成gbk
		$url = $this->server ."/sms/push_mt.jsp?cpid={$this->cpId}&cppwd={$this->cpPwd}&phone={$phone}&spnumber={$spnumber}&msgcont={$content}&extend={$extend}";
		return $this->request($url);
	}
	
	public function getAmount()
	{
		$url = $this->server ."/user/qamount.jsp?cpid={$this->cpId}&pwd={$this->cpPwd}";
		return $this->request($url);
	}

	private function request($url)
	{
		return file_get_contents($url);
	}
}


