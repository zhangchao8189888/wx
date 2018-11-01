<?php
// php在调用webservice时，报告如下类似错误：
// ( ! ) Fatal error: Class 'SoapClient' not found in
// E:/WebSrv/CI/system/libraries/WebService.php on line 17
// 解决方法：
// 打开php.ini，找到php_soap.dll ， 把前面的分号去掉
// ;extension=php_soap.dll
// 前提是，已经安装了 php_soap.dll

// $test = WS_SDK::getInstance ( "", "" );
// echo $test->sendSmsAsNormal(phone, content, spnumber, 0);//1,手机号、2，内容、3，流水号
class WS_SDK {
	private $_rpcClient = null;
	private $cpId = "";
	private $cpPwd = "";
	private $wsdl = "";
	private $client = null;
	/**
	 *
	 * @var SMS_SDK
	 */
	private static $_self = null;
	public static function getInstance($cpId, $cpPwd, $wsdl = "http://hl.my2my.cn/newesms/services/esmsservice?wsdl") {
		if (null == self::$_self) {
			self::$_self = new WS_SDK ( $cpId, $cpPwd, $wsdl );
		}
		return self::$_self;
	}
	private function __construct($cpId, $cpPwd, $wsdl) {
		$this->cpId = $cpId;
		$this->cpPwd = $cpPwd;
		$this->wsdl = $wsdl;
		$this->client = new SoapClient ( $wsdl );
	}
	public function sendSmsAsXml($smsPack) {
		$params = array (
				"in0" => $smsPack,
				"in1" => $this->cpId,
				"in2" => $this->cpPwd 
		);
		$p = $this->client->__call ( 'sendSmsAsXml', array (
				"parameters" => $params 
		) );
		return $p->out;
	}
	public function sendLongSmsAsXml($smsPack) {
		$params = array (
				"in0" => $smsPack,
				"in1" => $this->cpId,
				"in2" => $this->cpPwd 
		);
		$p = $this->client->__call ( 'sendLongSmsAsXml', array (
				"parameters" => $params 
		) );
		return $p->out;
	}
	public function sendSmsAsNormal($phone, $content, $spnumber, $chid) {
		$params = array (
				"in0" => $phone,
				"in1" => $content,
				"in2" => $spnumber,
				"in3" => $chid,
				"in4" => $this->cpId,
				"in5" => $this->cpPwd 
		);
		$p = $this->client->__call ( 'sendSmsAsNormal', array (
				"parameters" => $params 
		) );

		return $p->out;
	}
	public function sendLongSmsAsNormal($phone, $content, $spnumber, $chid) {
		$params = array (
				"in0" => $phone,
				"in1" => $content,
				"in2" => $spnumber,
				"in3" => $chid,
				"in4" => $this->cpId,
				"in5" => $this->cpPwd 
		);
		$p = $this->client->__call ( 'sendLongSmsAsNormal', array (
				"parameters" => $params 
		) );
		return $p->out;
	}
	public function getSmsStates() {
		$params = array (
				"in0" => $this->cpId,
				"in1" => $this->cpPwd 
		);
		$p = $this->client->__call ( 'getSmsStates', array (
				"parameters" => $params 
		) );
		return $p->out;
	}
	public function sendMms($smsPack) {
		$params = array (
				"in0" => $smsPack,
				"in1" => $this->cpId,
				"in2" => $this->cpPwd 
		);
		$p = $this->client->__call ( 'sendMms', array (
				"parameters" => $params 
		) );
		return $p->out;
	}
	public function getMmsStates() {
		$params = array (
				"in0" => $this->cpId,
				"in1" => $this->cpPwd 
		);
		$p = $this->client->__soapCall ( 'getMmsStates', array (
				"parameters" => $params 
		) );
		return $p->out;
	}
	public function getMoList($nextId) {
		$params = array (
				"in0" => $nextId,
				"in1" => $this->cpId,
				"in2" => $this->cpPwd 
		);
		$p = $this->client->__call ( 'getMoList', array (
				"parameters" => $params 
		) );
		return $p->out;
	}
	public function getAmount($chid) {
		$params = array (
				"in0" => $this->cpId,
				"in1" => $this->cpPwd,
				"in2" => $chid 
		);
		// var_dump($this->client->__getFunctions());
		// $p = $this->client->getAmount($this->cpId, $this->cpPwd,$chid);
		$p = $this->client->__soapCall ( 'getAmount', array (
				"parameters" => $params 
		) );
		return $p->out;
	}
}

