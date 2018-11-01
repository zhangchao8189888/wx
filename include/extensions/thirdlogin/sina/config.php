<?php
header('Content-Type: text/html; charset=UTF-8');

define( "WB_AKEY",JConfig::item('login.wb.key') );
define( "WB_SKEY",JConfig::item('login.wb.secret') );
define( "WB_CALLBACK_URL" , JJ_DOMAIN."/login/wclogin" );
?>