<?php
$_SESSION["appid"]    = JConfig::item('login.qq.key'); 

//申请到的appkey
$_SESSION["appkey"]   = JConfig::item('login.qq.secret'); 

//QQ登录成功后跳转的地址,请确保地址真实可用，否则会导致登录失败。
$_SESSION["callback"] = JJ_DOMAIN."/login/Qclogin"; 

$_SESSION["scope"] = "get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo";
?>