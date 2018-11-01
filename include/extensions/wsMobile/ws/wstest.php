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
	
	require("ws-demo.php");
	$engine = WS_SDK::getInstance ($cpid,$cppsw);
	echo("<meta charset='utf-8'>");
	//echo $test->sendSmsAsNormal(phone, content, spnumber, 0);//1,手机号、2，内容、3，流水号
	switch ($method) {
		case 'sendsmsnormal':
			echo $engine->sendSmsAsNormal($target, $content, '317000001', 0);//1,手机号、2，内容、3，流水号、4，通道号（默认为0，预留扩展用）
			break;
		case 'sendsmsxml':
			$smspack="<?xml version='1.0' encoding='utf-8' ?><sms><msg><phone>".$target."</phone><content>".$content."</content><spnumber>1111111111</spnumber><chid>0</chid></msg></sms>";
			echo $engine->sendSmsAsXml($smspack);//1,手机号、2，内容、3，流水号、4，通道号（默认为0，预留扩展用）
			break;
		case 'getsmsstatus':
			echo htmlspecialchars($engine->getSmsStates());
			break;
		case 'getamount':
			echo $engine->getAmount(0);
			break;
		case 'getmolist':
			echo htmlspecialchars($engine->getmolist(0));
			break;
		case 'sendmms':
			if($_FILES['file']['error']==0){
				if (file_exists("upload/" . $_FILES["file"]["name"]))
			      {
			      echo $_FILES["file"]["name"] . " already exists. ";
			      }
			    else
			      {
			      move_uploaded_file($_FILES["file"]["tmp_name"],
			      "../upload/" . $_FILES["file"]["name"]);
			      
			      }
				$binary = file_get_contents("../upload/". $_FILES["file"]["name"]);
				$base64 = base64_encode($binary);
				$target=$_REQUEST['target'];
				if(!$target){
					echo("<meta charset='utf-8'>请输入目标手机号<a href='/'>返回</a>");
					exit;
				}
			}else{
				echo("<meta charset='utf-8'>上传文件失败<a href='/'>返回</a>");
				exit;
			}
			$mmspack = "<?xml version='1.0' encoding='utf-8' ?><mms><phone>".$target."</phone><spnumber>1111111111</spnumber><subject>这是一个测试</subject><chid></chid>"."<pages><page dur='50'><img type='jpeg'>".$base64."</img><text>华录亿动是中国领先的移动信息云服务引擎</text></page></pages>"
                   ."</mms>";
			echo $engine->sendMms($mmspack);
			break;
		default:
			# code...
			break;
	}
?>