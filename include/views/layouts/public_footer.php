<div class="row-fluid">
  <div id="footer" class="span12"> Copyright © 2013 zhongqijiye.com Inc. All Rights Reserved. 哗啦科技 版权所有 </div>
</div>

<?php
/* @var $this JController */
$cs=Yii::app()->clientScript
	#->registerScriptFile(FF_STATIC_BASE_URL.'/js/excanvas.min.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/jquery.min.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/jquery.ui.custom.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/bootstrap.min.js')
	#->registerScriptFile(FF_STATIC_BASE_URL.'/js/jquery.flot.min.js')
	#->registerScriptFile(FF_STATIC_BASE_URL.'/js/jquery.flot.resize.min.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/jquery.peity.min.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/fullcalendar.min.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/matrix.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/matrix.calendar.js')
	#->registerScriptFile(FF_STATIC_BASE_URL.'/js/matrix.dashboard.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/jquery.gritter.min.js')
	#->registerScriptFile(FF_STATIC_BASE_URL.'/js/matrix.interface.js')
	#->registerScriptFile(FF_STATIC_BASE_URL.'/js/matrix.chat.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/jquery.validate.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/matrix.form_validation.js')
	#->registerScriptFile(FF_STATIC_BASE_URL.'/js/jquery.wizard.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/jquery.uniform.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/matrix.popover.js')
	//->registerScriptFile(FF_STATIC_BASE_URL.'/js/select2.min.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/jquery.dataTables.min.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/matrix.tables.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/datepicker/WdatePicker.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/js/layer/layer.js')
	->registerScriptFile(FF_STATIC_BASE_URL.'/common-js/zq.main.js');
	#->registerScriptFile(FF_STATIC_BASE_URL.'/upload/js/snippets/jquery.sohu.login.js');
?>
<script type="text/javascript">
$(function() {
		setInterval("GetTime()", 1);
	});
function GetTime() {
	var mon, day, now, hour, min, ampm, time, str, tz, end, beg, sec;
	/*
	mon = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug",
			"Sep", "Oct", "Nov", "Dec");
	*/
	mon = new Array("1", "2", "3", "4", "5", "6", "7", "8","9", "10", "11", "12");
	/*
	day = new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	*/
	day = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
	now = new Date();
	hour = now.getHours();
	min = now.getMinutes();
	sec = now.getSeconds();
	if (hour < 10) {
		hour = "0" + hour;
	}
	if (min < 10) {
		min = "0" + min;
	}
	if (sec < 10) {
		sec = "0" + sec;
	}
	$("#Timer").html(
			"<nobr>" + now.getFullYear() + "年" + mon[now.getMonth()] + "月"+ now.getDate() + "日，" + day[now.getDay()] + "，" + hour+ ":" + min + ":" + sec + "</nobr>");
    $('#Timer').addClass('animated bounceInRight');
	
}
function logout() {
    var ru = '<?php echo FF_DOMAIN?>';
    $.ajax({
        url : '/login/logout/',
        type : 'post',
        success : function() {
          location.href = "<?php echo FConfig::item('admin.jumpurl.url');?>"+ru;
        }
    });
}
</script>