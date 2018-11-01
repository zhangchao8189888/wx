<div id="loginbox">
    <?php if (!empty($content)) {?>
        <div class="alert alert-error">
            <button data-dismiss="alert" class="close">×</button>
            <strong>登录失败!</strong> <?php echo $content;?> </div>
    <?php }?>
    <form id="loginform" class="form-vertical" action="<?php echo FF_DOMAIN;?>/login/getLogin" method="post">
        <div class="control-group normal_text"> <h3>HuaLaOA<!--<img src="img/logo.png" alt="Logo" />--></h3></div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_lg"><i class="icon-user"></i></span><input type="text" name="usrname" placeholder="用户名" />
                    <input type="hidden" name="login_mode" value="company" />
                </div>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <div class="main_input_box">
                    <span class="add-on bg_ly"><i class="icon-lock"></i></span><input type="password" name="password" placeholder="密码" />
                </div>
            </div>
        </div>
        <div class="form-actions">
            <!--<span class="pull-left"><a href="#" class="flip-link btn btn-info" id="to-recover">Lost password?</a></span>-->
            <span class="pull-right"><input type="submit" value="登陆" class="btn btn-success" /></span>
        </div>
    </form>
    <form id="recoverform" action="#" class="form-vertical">
        <p class="normal_text">Enter your e-mail address below and we will send you instructions how to recover a password.</p>

        <div class="controls">
            <div class="main_input_box">
                <span class="add-on bg_lo"><i class="icon-envelope"></i></span><input type="text" placeholder="E-mail address" />
            </div>
        </div>

        <div class="form-actions">
            <span class="pull-left"><a href="#" class="flip-link btn btn-success" id="to-login">&laquo; Back to login</a></span>
            <span class="pull-right"><a class="btn btn-info"/>Reecover</a></span>
        </div>
    </form>
</div>
<script src="<?php echo FF_STATIC_BASE_URL?>/js/matrix.login.js"></script>
<script language="JavaScript" type="text/javascript">
    $(function(){
        $(".close").click(function (){
            $(".alert-error").remove();
        });
    });
</script>
