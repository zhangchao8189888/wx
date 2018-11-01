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
    <li class="active"><a href="#basic">更新状态</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="basic">
        <table class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th class="tl" width="4%"><div>ID</div></th>
                <th class="tl"><div>员工编号</div></th>
                <th class="tl"><div>姓名</div></th>
                <th class="tl"><div>状态</div></th>
                <th class="tl"><div>入职时间</div></th>
            </tr>
            </thead>
            <tbody  class="tbodays">
            <form id="employForm" action="<?php FF_DOMAIN."/employ/getEmployByIds"?>">
                <input type="hidden" value="" name="type" id="type" />
                <?php foreach ($empList as $k => $row){
                    $empInfo = $row->employ_info;
                    ?>
                    <tr >
                        <td><div><?php echo $row->id;?></div></td>
                        <td><div><?php echo $empInfo->e_yuangong_bianhao;?></div></td>
                        <td><div><a  href="#" class="checkInfo pointer" data-id="<?php echo $row->id;?>" ><?php echo $row->e_name;?></a></div></td>
                        <td><div><?php echo $employ_status[$row->e_status];?></div></td>
                        <td><div><?php echo $row->e_hetong_date;?></div></td>
                    </tr>
                <?php }?>
            </form>
            </tbody>
        </table>
        <table cellspacing="0" cellpadding="2" width="100%" border="0">
            <tbody><tr id="_ctl0_WorkForm_leaveJobInfo1">
                <td style="width: 70px">
                    离职类型：
                </td>
                <td>
                    <select name="_ctl0:WorkForm:ddlLeaveType" id="_ctl0_WorkForm_ddlLeaveType" style="width:88px;">
                        <option selected="selected" value="-1">--请选择--</option>
                        <option value="2734102850047967241">自动离职</option>
                        <option value="2734107478527967242">退休</option>
                        <option value="2734117288447967243">病退</option>
                        <option value="4146813823487967237">辞退</option>
                        <option value="5583232253941710891">辞职</option>

                    </select>
                </td>
                <td style="width: 70px">
                    离职时间：
                </td>
                <td>
                    <input name="_ctl0:WorkForm:txtLeaveDate" type="text" value="2015-06-13" maxlength="20" id="_ctl0_WorkForm_txtLeaveDate" onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})" style="width:88px;" class=" Line">
                </td>
            </tr>

            <tr id="_ctl0_WorkForm_leaveJobInfo2">
                <td style="width: 70px">
                    离职原因：
                </td>
                <td colspan="3" style="height: 8px">
                    <select name="_ctl0:WorkForm:ddlLeaveReason" id="_ctl0_WorkForm_ddlLeaveReason" style="width:88px;">
                        <option selected="selected" value="-1">--请选择--</option>
                        <option value="-6331026777690079230">无法胜任工作</option>
                        <option value="5582999639807950883">身体原因</option>
                        <option value="5583012887254990884">另外找到工作</option>

                    </select>
                </td>
            </tr>

            <tr id="_ctl0_WorkForm_leaveJobInfo3">
                <td style="width: 70px">
                    备注：
                </td>
                <td colspan="3">
                    <textarea name="_ctl0:WorkForm:txtMemo" id="_ctl0_WorkForm_txtMemo" style="height:72px;width:96%;"></textarea>
                </td>
            </tr>

            </tbody></table>
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