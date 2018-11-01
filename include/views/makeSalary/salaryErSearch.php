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
        <a href="/makeSalary/examineSalary" class="current">工资查询</a>
    </div>
</div>
<div class="widget-title">
    <ul class="nav nav-pills">
        <li class="<?php echo empty($active) || $active=='first' ? 'active' : '';?>"><a href="<?php echo $this->createUrl('salaryFirstSearchPage');?>">一次工资</a></li>
        <li class="<?php echo $active=='second' ? 'active' : '';?>"><a href="<?php echo $this->createUrl('salarySecondSearchPage');?>">二次工资</a></li>
        <li class="<?php echo $active=='nian' ? 'active' : '';?>"><a href="<?php echo $this->createUrl('salaryNianSearchPage');?>">年终奖</a></li>
    </ul>

</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!--            <span class="btn btn-primary" style="margin-right: 20px;" id="cancelUnit">取消管理</span><span class="btn btn-primary" id="addUnit">添加管理单位</span><br><br>-->
            <form action="<?php echo FF_DOMAIN.'/'.$this->route;?>" method="get">
                <span class="label" style="margin-right: 20px;">单位查询</span><input type="text" name="name" id="searchName" value="<?php echo $searchName;?>">
                <br>
                <span class="label" style="margin-right: 20px;">起始月份查询</span><input type="text" name="start_date" class="Wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM'})" value="<?php echo $start_date;?>">
                <span class="label" style="margin-right: 20px;">结束月份查询</span><input type="text" name="end_date" class="Wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM'})" value="<?php echo $end_date;?>">
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
                            <th class="tl"><div>单位名称</div></th>
                            <th class="tl"><div>工资月份</div></th>
                            <th class="tl"><div>操作时间</div></th>
                            <th class="tl"><div>状态</div></th>
                        </tr>
                        </thead>
                        <tbody  class="tbodays">
                        <input type="hidden" value="" name="type" id="type" />
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else {  foreach ($salaryTimeList as $k => $row){
                            ?>
                            <tr>
                                <td><div><input type="checkbox" name="unitCancelCheckbox" value="<?php echo $row->id?>"></div></td>
                                <td class="tl" width="4%"><div><?php echo $row->id;?></div></td>
                                <td><div><?php echo $row->customer->customer_name;?></div></td>
                                <td><div><?php echo $row->salaryTime;?></div></td>
                                <td><div><?php echo $row->add_time;?></div></td>
                                <!--                                <td><div><a href="javascript:void();" class="updateStatus" data-status="--><?php //echo $row->salary_status?><!--" data-id="--><?php //echo $row->id;?><!--">--><?php //echo FConfig::item('config.examine_status.'.$row->salary_status);?><!--</a></div></td>-->
                                <td class="tl pl10">
                                    <a title="查看" href="<?php echo $this->createUrl('checkSalaryList', array('id'=>$row->id,'type'=>$active));?>" style="cursor:pointer" target="_blank" class="rowCheck theme-color">查看</a>
                                    <?php if ($row->salary_status < 1 || $row->salary_status == 3) {?><a title="删除" data-id="<?php echo $row->id;?>" data-type="<?php echo $salaryTypeStr;?>" style="cursor:pointer" class="rowDel theme-color">删除</a>
                                    <?php }

                                    if ($row->salary_status == 1) {
                                        echo '<em style="color: #4db6ff">审核中...</em>';
                                    } else if ($row->salary_status == 2) {
                                        echo '<em style="color: #7FFF8E">审核通过</em>';
                                    } else if ($row->salary_status == 3) {
                                        echo '<em style="color: #ff1013">审核拒绝</em>';
                                    }?>
                                </td>
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
<script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/common-js/salary/zq.salarySearch.js"></script>