<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>

<div class="form">
        <p class="note">Fields with <span class="required">*</span> are required.</p>
        <div>
            <font color="red">*</font>邮箱：
        </div>
        <div>
            <input type="text" id="email" name="email"/>
        </div>
        <div>
            <font color="red">*</font>手机号码：
        </div>
        <div>
            <input type="text" id="phone" name="phone"/>
        </div>
        <div>
            <font color="red">*</font>密码：
        </div>
        <div>
            <input type="password" id="password" name="password"/>
        </div>
        <div>
            <font color="red">*</font>验证码：
        </div>
        <div>
            <input type="text" id="checkCode" name="checkCode" size="6"/>
            <input id="btnSendCode" type="button" value="发送验证码" onclick="sendMessage()" />
        </div>
        <div class="row buttons">
            <input type="button" onclick="register()" value="注册"/>
        </div>
</div><!-- form -->
<script type="text/javascript">
    /*-------------------------------------------*/
    var InterValObj; //timer变量，控制时间
    var count = 5; //间隔函数，1秒执行
    var curCount;//当前剩余秒数
    var code = ""; //验证码
    var codeLength = 6;//验证码长度
    function sendMessage() {
        curCount = count;
        var phone=$("#phone").val();//手机号码
        if(phone != ""){
            //产生验证码
            for (var i = 0; i < codeLength; i++) {
                code += parseInt(Math.random() * 9).toString();
            }
            //设置button效果，开始计时
            $("#btnSendCode").attr("disabled", "true");
            $("#btnSendCode").val("请在" + curCount + "秒内输入验证码");
            InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
            //向后台发送处理数据
            $.ajax({
                type: "POST", //用POST方式传输
                dataType: "json", //数据格式:JSON
                url: '/login/sendMobileMess', //目标地址
                data: {
                    target : phone
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) { },
                success: function (msg){
                    if (msg.status > 100000) {
                        alert(msg.content);
                    } else {
                        alert('短息已经发送到您的手机上');
                    }
                }
            });
        }else{
            alert("手机号码不能为空！");
        }
    }
    //timer处理函数
    function SetRemainTime() {
        if (curCount == 0) {
            window.clearInterval(InterValObj);//停止计时器
            $("#btnSendCode").removeAttr("disabled");//启用按钮
            $("#btnSendCode").val("重新发送验证码");
            code = ""; //清除验证码。如果不清除，过时间后，输入收到的验证码依然有效
        }
        else {
            curCount--;
            $("#btnSendCode").val("请在" + curCount + "秒内输入验证码");
        }
    }
    function register () {
        $.ajax({
            type: "POST",
            url: '/login/register',
            data: {
                phone : $('#phone').val(),
                code : $('#checkCode').val(),
                email : $('#email').val()
            },
            dataType: "json",
            error: function (XMLHttpRequest, textStatus, errorThrown) { },
            success: function (msg){
                if (msg.status > 100000) {
                    alert(msg.content);
                } else {
                    alert(msg.content);
                }
            }
        });
    }
</script>