  <?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/15
 * Time: 18:08
 */

$typeList=$data['typeList'];
?>
<style type="text/css">
    .main .content {
        border: none;
        padding: 0;
    }

    .main .path {
        display: none;
    }

    fieldset {
        margin-bottom: 5px;
        padding: 5px;
    }
    fieldset {
        display: block;
        -webkit-margin-start: 2px;
        -webkit-margin-end: 2px;
        -webkit-padding-before: 0.35em;
        -webkit-padding-start: 0.75em;
        -webkit-padding-end: 0.75em;
        -webkit-padding-after: 0.625em;
        border: 2px groove threedface;
        border-image-source: initial;
        border-image-slice: initial;
        border-image-width: initial;
        border-image-outset: initial;
        border-image-repeat: initial;
        min-width: -webkit-min-content;
    }
</style>
<script language="javascript" type="text/javascript">
    $(document).ready(function () {
        $('#myTab a').click(function (e) {
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
        })
    });
</script>
<div id="content-header">
    <div id="breadcrumb">
        <a href="/index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="/product/" class="current">档案管理</a>
        <a href="/product/productList" class="current">人员信息</a>
    </div>
</div>
<div class="container-fluid">
<div class="row-fluid">

    <div class="span12" style="margin-left:0;">
        <div class="widget-box">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a href="#basic">基本信息</a></li>
<!--                <li><a href="#bujiao">上个月漏交保险<em style="color: red" id="yanfuNum"></em></a></li>-->
<!--                <li><a href="#bukou">上个月垫付保险<em style="color: red" id="dianfuNum"></em></a></li>-->
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="basic">
                <table cellspacing="0" cellpadding="0" width="100%" border="0">
                    <tbody><tr>
                        <td width="50%">
                            <span id="_ctl0_WorkForm_lblCurrentName"><?php echo $employInfo->e_name?></span>&nbsp;简历信息
                        </td>
                        <td width="50%" align="right">
                            <input type="submit" id="modifyInfo" value="修改">
<!---->
<!--                            <input type="button" value="导出" class="button" onclick="exportWord(this);">-->
<!--                            <input type="button" class="button" value="打印">-->
<!--                            <input type="button" class="button" value="关闭" onclick="parent.close();">-->
<!--                            <input type="hidden" name="_ctl0:WorkForm:IndexID" id="_ctl0_WorkForm_IndexID">-->
                        </td>
                    </tr>
                    </tbody></table>
                <fieldset>
                <legend><b>
                        基本信息</b></legend>
                <table cellpadding="1" width="100%" cellspacing="0">
                <tbody><tr>
                    <td style="width: 70px">
                        姓名：
                    </td>
                    <td>
                        <input name="e_name" type="text" value="<?php echo $employInfo->e_name?>" maxlength="25" id="e_name" disabled="disabled" class="aspNetDisabled Line" >&nbsp;<span style="color: #ff0000">*</span>
                        <span id="_ctl0_WorkForm_lbRemind" style="color:Red;"></span>
                    </td>
                    <td style="width: 70px">
                        证件号码：
                    </td>
                    <td>
                        <input name="e_num" type="text" value="<?php echo $employInfo->e_num?>" id="e_num" disabled="disabled" class="aspNetDisabled Line" >&nbsp;<span style="color: #ff0000">*</span>
                        <span id="_ctl0_WorkForm_lbRemindCard" style="color:Red;"></span>
                    </td>
                    <td valign="top" style="width: 180px" align="center" rowspan="12">
                        <img id="_ctl0_WorkForm_imgPic" src="..\..\images\100.jpeg" style="height:150px;width:110px;"><br>
                        <br>
                        <input name="_ctl0:WorkForm:txtPhotoValue" type="text" id="_ctl0_WorkForm_txtPhotoValue" disabled="disabled" class="aspNetDisabled Line" style="display: none">
                        <p>

                            <input name="_ctl0:WorkForm:hidfilename" type="hidden" id="_ctl0_WorkForm_hidfilename">
                        </p>


                    </td>
                </tr>
                <tr>
                    <td style="width: 70px">
                        籍贯：
                    </td>
                    <td>
                        <input name="e_jiguan" type="text" value="<?php echo $employInfo->employ_info->e_jiguan?>" maxlength="10" id="e_jiguan" disabled="disabled" class="aspNetDisabled Line" >&nbsp;<span style="color: #ff0000">*</span>
                    </td>
                    <td>
                        档案编号：
                    </td>
                    <td>
                        <input name="e_dangan_bianhao" value="<?php echo $employInfo->employ_info->e_dangan_bianhao?>" type="text" maxlength="25" id="e_dangan_bianhao" disabled="disabled" class="aspNetDisabled Line" >
                    </td>
                </tr>
                <tr>
                    <td style="width: 70px">
                        用户帐号：
                    </td>
                    <td>
                        <input name="e_bank_no" type="text" value="<?php echo $employInfo->employ_info->e_bank_no?>" id="e_bank_no" disabled="disabled" class="aspNetDisabled Line" >
                    </td>
                    <td>
                        员工编号：
                    </td>
                    <td>
                        <input name="e_yuangong_bianhao" value="<?php echo $employInfo->employ_info->e_yuangong_bianhao?>" type="text" id="e_yuangong_bianhao" disabled="disabled" class="aspNetDisabled Line" >
                        <span id="_ctl0_WorkForm_lbRemindEmpID" style="color:Red;"></span>
                    </td>
                </tr>
                <tr>
                    <td style="width: 70px">
                        工资卡号：
                    </td>
                    <td width="28%">
                        <input name="bank_num" type="text" value="<?php echo $employInfo->bank_num?>" id="bank_num" disabled="disabled" class="aspNetDisabled Line" />
                    </td>
                    <td>
                        社保号：
                    </td>
                    <td>
                        <input name="e_shebao_no" type="text" value="<?php echo $employInfo->employ_info->e_shebao_no?>" id="e_shebao_no" disabled="disabled" class="aspNetDisabled Line" />
                    </td>
                </tr>
                <tr>
                    <td style="width: 70px">
                        曾用名（英）：
                    </td>
                    <td>
                        <input name="e_oldName" type="text" maxlength="15" id="e_oldName" disabled="disabled" class="aspNetDisabled Line" >
                    </td>
                    <td>
                        性别：
                    </td>
                    <td>
                        <select name="e_sex" id="e_sex" disabled="disabled" class="aspNetDisabled" style="width:88px;">
                            <option <?php if($employInfo->employ_info->e_sex==1){ echo 'selected="selected"';}?> value="1">男</option>
                            <option <?php if($employInfo->employ_info->e_sex==2){ echo 'selected="selected"';}?> value="0">女</option>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="width: 70px">
                        出生日期：
                    </td>
                    <td>
                        <input name="e_birthday" type="text" id="e_birthday" value="<?php echo $employInfo->employ_info->e_birthday?>" disabled="disabled" class="aspNetDisabled Line"  style="width:80px;">
                        年龄：
                        <input name="e_age" type="text" maxlength="3" id="e_age" disabled="disabled" class="aspNetDisabled Line" autocomplete="off" onkeyup="qc.getBirthday(this,'_ctl0_WorkForm_txtBirth');this.value=this.value.replace(/\D/g,'');" onpaste="this.value=this.value.replace(/\D/g,'')" style="width:50px;">
                    </td>
                    <td>
                        工作地：
                    </td>
                    <td>
                        <input name="e_work_address" type="text" id="e_work_address" value="<?php echo $employInfo->employ_info->e_work_address?>" disabled="disabled" class="aspNetDisabled Line">
                    </td>
                </tr>
                <!--<tr>
                    <td style="width: 70px">
                        出生地：
                    </td>
                    <td>
                        <input name="_ctl0:WorkForm:txtHomePlace" type="text" maxlength="40" id="_ctl0_WorkForm_txtHomePlace" disabled="disabled" class="aspNetDisabled Line" >
                    </td>
                    <td>
                        民族：
                    </td>
                    <td>
                        <input name="_ctl0:WorkForm:txtFolk" type="text" maxlength="16" id="_ctl0_WorkForm_txtFolk" disabled="disabled" class="aspNetDisabled Line" >
                    </td>
                </tr>-->
                <tr>
                    <td style="width: 70px">
                        户口：
                    </td>
                    <td>
                        <input name="e_hukou" type="text"  value="<?php echo $employInfo->employ_info->e_hukou?>" maxlength="40" id="e_hukou" disabled="disabled" class="aspNetDisabled Line" >
                    </td>
                    <td>
                        户口性质：
                    </td>
                    <td>
                        <select name="_ctl0:WorkForm:ddlHKXZ" id="_ctl0_WorkForm_ddlHKXZ" disabled="disabled" class="aspNetDisabled" style="width:188px;">
                            <option selected="selected" value="-1">--请选择--</option>
                            <?php foreach($employ_type as $key=>$val){
                                echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
                            }?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="width: 70px">
                        婚姻状况：
                    </td>
                    <td>
                        <select name="e_is_marriage" id="e_is_marriage" disabled="disabled" class="aspNetDisabled" style="width:108px;">
                            <option selected="selected" value="-1">--请选择--</option>
                            <option value="1">已婚</option>
                            <option value="2">未婚</option>

                        </select>
                    </td>
                    <td>
                        毕业院校：
                    </td>
                    <td>
                        <input name="_ctl0:WorkForm:txtSchool" type="text" maxlength="50" id="_ctl0_WorkForm_txtSchool" disabled="disabled" class="aspNetDisabled Line">
                    </td>
                </tr>

                <tr>
                    <td style="width: 70px">
                        创建人：
                    </td>
                    <td>
                        <input name="_ctl0:WorkForm:txtCreator" type="text" value="<?php echo $this->user['name']?>" maxlength="50" id="_ctl0_WorkForm_txtCreator" disabled="disabled" class="aspNetDisabled Line" onfocus="this.blur()"><input name="_ctl0:WorkForm:txtCreatorID" type="text" value="681" id="_ctl0_WorkForm_txtCreatorID" disabled="disabled" class="aspNetDisabled Line" style="display: none">
                    </td>
                    <td>
                        创建时间：
                    </td>
                    <td>
                        <input name="_ctl0:WorkForm:txtCreateTime" type="text" value="2014-10-09" maxlength="50" id="_ctl0_WorkForm_txtCreateTime" disabled="disabled" class="aspNetDisabled Line" onfocus="this.blur()">
                    </td>
                </tr>
                </tbody></table>
                </fieldset>
                </div>
                <div class="tab-pane" id="bujiao">
                </div>
                <div class="tab-pane" id="bukou">
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(function (){
        var BaseUrl = "<?php echo FF_DOMAIN;?>";
        $("#modifyInfo").click(function(){
            $("#basic input[type='text']").each(function(i){
                $(this).attr("disabled",false);
            });
            $("#basic select").each(function(i){
                $(this).attr("disabled",false);
            });
        });

    });
</script>