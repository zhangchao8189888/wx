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
        <a href="/makeSalary/examineSalary" class="current">工资审核</a>
    </div>
</div>
<div class="widget-title">
    <ul class="nav nav-pills">
        <li class="active"><a href="<?php echo $this->createUrl('examineSalary');?>">一次工资</a></li>
        <li class=""><a href="<?php echo $this->createUrl('examineErSalary');?>">二次工资</a></li>
        <li class=""><a href="<?php echo $this->createUrl('examineNianSalary');?>">年终奖</a></li>
    </ul>

</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
<!--            <span class="btn btn-primary" style="margin-right: 20px;" id="cancelUnit">取消管理</span><span class="btn btn-primary" id="addUnit">添加管理单位</span><br><br>-->
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
                        <?php }else {  foreach ($list as $k => $row){
                            ?>
                            <tr>
                                <td><div><input type="checkbox" name="unitCancelCheckbox" value="<?php echo $row->id?>"></div></td>
                                <td class="tl" width="4%"><div><?php echo $row->id;?></div></td>
                                <td><div><?php echo $row->companyName;?></div></td>
                                <td><div><?php echo $row->salaryTime;?></div></td>
                                <td><div><?php echo $row->op_salaryTime;?></div></td>
                                <td><div><a href="javascript:void();" class="updateStatus <?php echo $row->salary_status==0 ? 'btn btn-primary' : '';?>" data-type="first" data-status="<?php echo $row->salary_status?>" data-id="<?php echo $row->id;?>"><?php echo FConfig::item('config.examine_status.'.$row->salary_status);?></a></div></td>
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

<script type="text/javascript">
    $(function (){

        $(".updateStatus").click(function(){
            if ($(this).attr('data-status') == '0') {
                if (confirm('是否要申请发放工资？')) {
                    var id = $(this).attr('data-id');
                    var type = $(this).attr('data-type');
                    var url = GLOBAL_CF.DOMAIN+'/makeSalary/updateExamineStatus';
                    $.post(url,{'id':id,type:type},function(data){
                        if (data.status==100000) {
                            alert(data.content);
                            window.location.reload();
                        } else {
                            alert(data.content);
                        }
                    },'JSON');
                }
            } else {
                return false;
            }
        });
    });
</script>
<script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script>