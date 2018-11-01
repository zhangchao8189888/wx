<?php
session_start();
include_once( 'config.php');
//include_once( 'saetv2.ex.class.php');
//$o = new SaeTOAuthV2( WB_AKEY , WB_SKEY );
$code_url = "https://api.weibo.com/oauth2/authorize?client_id=".WB_AKEY."&forcelogin=true&redirect_uri=".WB_CALLBACK_URL;
header("Location:$code_url");
?>