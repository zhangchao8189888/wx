<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="baidu_union_verify" content="6fca44a60fe0672e68191a884b1cbe73" />
<link rel="shortcut icon" href=""/>
<title><?php echo Yii::app()->name.'-'.$this->pageTitle?></title>

<?php
/* @var $this JController */
$cs=Yii::app()->clientScript
	->registerCssFile(FF_STATIC_BASE_URL.'/css/bootstrap.min.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/css/bootstrap-responsive.min.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/css/fullcalendar.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/css/matrix-style.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/css/matrix-media.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/font-awesome/css/font-awesome.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/css/jquery.gritter.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/css/animate.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/css/uniform.css')
    //->registerCssFile(FF_STATIC_BASE_URL.'/css/select2.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/css/sohu.focus.pinge.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/css/ff-common.css')
    ->registerCssFile(FF_STATIC_BASE_URL.'/css/jquery-ui.min.css')
?>
<script>
    var GLOBAL_CF = {
        BASE_URL : '<?php echo FF_STATIC_BASE_URL."/common-js/";?>',
        DOMAIN : '<?php echo FF_DOMAIN;?>'
    };
    var GLOBAL_DATA = {
        E_TYPE_LIST : <?php echo json_encode(FConfig::item('config.employ_type'));?>,
        E_TYPE_VAL_LIST : <?php echo json_encode(FConfig::item('config.employ_type_val'));?>
    }
    var FIREFLY = {};
    </script>