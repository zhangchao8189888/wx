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
        <a href="/makeSalary/" class="current">工资管理</a>
        <a href="/makeSalary/unit" class="current">单位管理</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <span class="btn btn-primary" style="margin-right: 20px;" id="cancelUnit">取消管理</span><span class="btn btn-primary" id="addUnit">添加管理单位</span><br><br>
            <form action="<?php echo FF_DOMAIN.'/'.$this->route;?>" method="get">
                <span class="label" style="margin-right: 20px;">单位查询</span><input type="text" name="name" id="searchName" value="<?php echo $searchName;?>">
                <br>
                <span class="label" style="margin-right: 20px;">月份查询</span><input type="text" name="date" class="Wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM'})" value="<?php echo $searchDate;?>">
                <input type="submit" style="margin-left: 10px;margin-bottom: 6px;" value="查询">
            </form>

        </div>

    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-content nopadding">
                    <div class="dataTables_length">
                                <span class="pull-right">
                                <span class="badge badge-warning"><?php echo $count; ?></span>&nbsp;
                                </span>
                    </div>
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="tl" width="4%"><div><input type="checkbox" id="unitCancelCheckbox"></div></th>
                            <th class="tl" width="4%"><div>编号</div></th>
                            <th class="tl"><div>管理员</div></th>
                            <th class="tl"><div>单位名称</div></th>
                            <th class="tl"><div>工资状态</div></th>
                            <th class="tl"><div>工资月份</div></th>
                            <th class="tl"><div>操作时间</div></th>
                            <th class="tl"><div>状态</div></th>
<!--                            <th class="tl"><div>标记</div></th>-->
                        </tr>
                        </thead>
                        <tbody  class="tbodays">
                        <input type="hidden" value="" name="type" id="type" />
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else {  foreach ($customList as $k => $row){
                            $salaryStatusName = empty($salaryTime[$row->companyId]) ? '未做工资' : '已做工资';
                            $grantStatusArr = FConfig::item('config.grant_status');
                            $salaryStatus = !empty($salaryTime[$row->companyId]->salary_status) ? $salaryTime[$row->companyId]->salary_status : 0;
                            $grantStatusName = empty($salaryTime[$row->companyId]) ? '暂无审核' : $grantStatusArr[$salaryStatus];
                        ?>
                            <tr>
                                <td><div><input type="checkbox" name="unitCancelCheckbox" value="<?php echo $row->id?>"></div></td>
                                <td class="tl" width="4%"><div><?php echo $row->id;?></div></td>
                                <td><div><?php echo $this->user['name'];?></div></td>
                                <td><div><?php echo $row->companyName;?></div></td>
                                <td><div><?php echo $salaryStatusName;?></div></td>
                                <td><div><?php echo $searchDate.'-01';?></div></td>
                                <td><div><?php echo $row->opTime;?></div></td>
                                <td><div><a href="javascript:void();" class="dealExamine" data-status="<?php echo $salaryStatus;?>" data-id="<?php echo $salaryTime[$row->companyId]->id;?>"><?php echo $grantStatusName;?></a></div></td>
<!--                                <td><div>--><?php //echo $row->remark;?><!--</div></td>-->
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
<script language="javascript" type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/common-js/adminCompany/zq.unit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script>