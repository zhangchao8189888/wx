<?php
	$cpid=$_REQUEST['cpid'];
	if(!$cpid){
		echo("<meta charset='utf-8'>请输入用户名<a href='/'>返回</a>");
		exit;
	}
	$cppsw=$_REQUEST['cppsw'];
	if(!$cppsw){
		echo("<meta charset='utf-8'>请输入密码<a href='/'>返回</a>");
		exit;
	}
	$method=$_REQUEST['method'];
	if($method=='sendsmsnormal'||$method=='sendsmsxml'){
		$content=$_REQUEST['content'];
		if(!$content){
			echo("<meta charset='utf-8'>请输入发送内容<a href='/'>返回</a>");
			exit;
		}
		$target=$_REQUEST['target'];
		if(!$target){
			echo("<meta charset='utf-8'>请输入目标手机号<a href='/'>返回</a>");
			exit;
		}
	}
	
	require("HTTP_SDK.php");
	$engine = HTTP_SDK::getInstance($cpid,$cppsw);
	echo("<meta charset='utf-8'>");
	//echo $test->sendSmsAsNormal(phone, content, spnumber, 0);//1,手机号、2，内容、3，流水号
	switch ($method) {
		case 'sendsmsnormal':
			echo $engine->pushMt($target,'1111111111', $content,  0);//1,手机号、2，内容、3，流水号、4，通道号（默认为0，预留扩展用）
			break;
		case 'getamount':
			echo $engine->getAmount(0);
			break;
		default:
			# code...
			break;
	}
?>