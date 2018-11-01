<link type="text/css" rel="stylesheet" href="<?php echo FF_DOMAIN; ?>/upload/css/index/index.css" />
<!-- 注册页面 -->
<div id="pg-register" class="f-bg-gray f-pdb40">
    <div class="g-in">
        <div class="register-title">
            <h3 class="f-h3">注册</h3>
        </div>
        <div class="register-container f-cb">
            <div class="f-fl register-ad">
                <img src="<?php echo FF_DOMAIN; ?>/upload/images/reg_img.png" width="526" height="568" />
            </div>
            <div class="register-form m-form f-fr">
                <h3 class="register-form-title">立即注册，开始投资</h3>
                <form id="register-form">
                    <div class="form-item">
                        <em class="icon email-icon"></em>
                        <div class="ipt">
                            <input type="text" id="email" name="email" class="u-ipt u-ipt-pl30" placeholder="电子邮箱" />
                            <div class="vd-error">
                                <em class="vd-arrow"></em>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <em class="icon phone-icon"></em>
                        <div class="ipt">
                            <input type="text" id="account_phone" name="account_phone" class="u-ipt u-ipt-pl30" placeholder="手机号码" />
                            <div class="vd-error">
                                <em class="vd-arrow"></em>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <em class="icon code-icon"></em>
                        <div class="ipt f-cb">
                            <input type="text" id="checkCode" name="checkCode"  class="u-ipt u-ipt-w110 u-ipt-pl30 f-fl" placeholder="输入效验码" />

                            <div class="code-box f-fl">
                                <img class="codeImage f-fl" src='/login/getCodeImg' width="77" height="40"  />
                                <a class="refresh f-fl"></a>
                            </div>
                            <div class="vd-error">
                                <em class="vd-arrow"></em>
                            </div>

                        </div>
                    </div>
                    <div class="form-item">
                        <em class="icon phone-code-icon"></em>
                        <div class="ipt f-cb">
                            <input type="text" id="verifyMess" name="verifyMess" class="u-ipt u-ipt-w110 u-ipt-pl30 f-fl" placeholder="输入手机验证码" />
                            <input type="button" class="sendbtn u-btn u-btn-w132 u-btn-h40 u-btn-c1 f-fl" value="发送">
                            <div class="vd-error">
                                <em class="vd-arrow"></em>
                            </div>
                        </div>
                    </div>
                    <div class="form-item">
                        <em class="icon password-icon"></em>
                        <div class="ipt">
                            <input type="password" id="account_password" name="account_password" class="u-ipt u-ipt-pl30" placeholder="密码为8个以上字母和数字组合" />
                            <div class="vd-error">
                                <em class="vd-arrow"></em>
                            </div>
                        </div>
                    </div>
                    <div class="form-item f-cb">
                        <span class="icon checked-icon f-fl"></span>
                        <p class="protocol f-fl">我同意并接受萤火虫理财<a href="#">注册协议</a>, <a href="#">商业借款协议</a> 和 <a href="#">个人借款协议</a> </p>
                    </div>
                    <div>
                        <input type="submit" class="register-btn u-btn u-btn-auto u-btn-h42 u-btn-c1" value="立即注册" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end 注册页面 -->
<script>
    require([GLOBAL_CF.BASE_URL+'/app/register.js']);
</script>