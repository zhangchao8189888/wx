<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/15
 * Time: 18:08
 */

$typeList=$data['typeList'];
?>

<div id="content-header">
    <div id="breadcrumb">
        <a href="/index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="/makeSalary/" class="current">财务管理</a>
        <a href="/makeSalary/unit" class="current">银行流水</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">

            <form action="<?php echo FF_DOMAIN.'/'.$this->route;?>" method="get">
                <span class="label" style="margin-right: 20px;">单位查询</span><input type="text" name="name" id="searchName" value="<?php echo $searchName;?>">

                <input type="submit" style="margin-left: 10px;margin-bottom: 6px;" value="查询">
            </form>

        </div>

    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="/financial/salaryAccount/">银行流水</a></li>
                        <li class=""><a href="/financial/salaryAccountDetail">银行流水工资表</a></li>
                    </ul>

                </div>
                <div class="widget-content nopadding">
                    <div class="dataTables_length">
                                <span class="pull-right">
                                <span class="badge badge-warning"><?php echo $count; ?></span>&nbsp;
                                </span>
                    </div>
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="tl" width="4%"><div>编号</div></th>
                            <th class="tl"><div>公司名称</div></th>
                            <th class="tl"><div>余额</div></th>
                            <th class="tl"><div>明细</div></th>
                            <th class="tl"><div>备注</div></th>
                        </tr>
                        </thead>
                        <tbody  class="tbodays">
                        <input type="hidden" value="" name="type" id="type" />
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else {
                            $i = 0;
                            foreach ( $list as $k => $row){  $i++;?>
                            <tr>
                                <td class="tl" width="4%"><div><?php echo $i;?></div></td>
                                <td><div><?php echo $row->customer_name;?></div></td>
                                <td><div><?php echo $row->account_val;?></div></td>
                                <td><div><a href="javascript:void();" class="toDetail" data-id="<?php echo $row->id;?>"data-name="<?php echo $row->customer_name;?>">明细</a></div></td>
                                <td><div><?php echo $row->remark;?></div></td>
                            </tr>
                        <?php }}?>
                        </tbody>
                    </table>
                </div>
                <?php $this->renderPartial('//page/index',array('page'=>$page)); ?>
            </div>
        </div>
    </div>
</div>
<!--添加--START---->
<div class="modal hide" id="modal-add-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>添加管理单位</h3>
    </div>
    <div class="modal-body">
        <a href="javascript:void();" class="btn btn-small" id="addUnitAjax">添加单位</a>
        公司查询：<input type="text" style="margin: 2px 10px;" id="companySearch"><a href="javascript:void();" class="btn btn-small btn-info" id="addCompanySearch" style="margin-left: 10px;">查询</a>
    </div>
    <div style="height: 500px;overflow-y: scroll;">
        <table class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th class="tl" width="4%"><div><input type="checkbox" id="checkboxAll"></div></th>
                <th class="tl"><div>单位编号</div></th>
                <th class="tl"><div>单位名称</div></th>
            </tr>
            </thead>
            <tbody id="addUnitBody" class="tbodays">
            </tbody>
        </table>
    </div>
</div>
<!--添加--END---->
<!--处理审核--START---->
<div class="modal hide" id="modal-deal-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>处理审核</h3>
    </div>
    <div class="modal-body">
        <input type="hidden" value="" id="dealActId">
        <a href="javascript:void();" class="btn btn-primary dealAct" data-val="2">同意</a>
        <a href="javascript:void();" class="btn btn-primary dealAct" data-val="3">拒绝</a>
        <!--        <a href="javascript:void();" class="btn btn-primary dealAct" data-val="4">取消</a>-->
    </div>
</div>
<!--处理审核--END---->

<script type="text/javascript">
    $(function (){

        var list = <?php echo json_encode($jsonList);?>;
        list = eval(list);
        $( "#companySearch" ).autocomplete({
            source: list
        });
        $( "#searchName" ).autocomplete({
            source: list
        });
    });
</script>
<script language="javascript" type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/common-js/financial/zq.account.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script>