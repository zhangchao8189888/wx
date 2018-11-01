
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/15
 * Time: 18:08
 */

//$typeList=$data['typeList'];
?>

<div id="content-header">
    <div id="breadcrumb">
        <a href="/index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="/financial/" class="current">工资管理</a>
        <a href="/financial/examineSalary" class="current">年终奖</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!--            <span class="btn btn-primary" style="margin-right: 20px;" id="cancelUnit">取消管理</span><span class="btn btn-primary" id="addUnit">添加管理单位</span><br><br>-->
            <form action="<?php echo FF_DOMAIN.'/'.$this->route;?>" method="get">
                <span class="label" style="margin-right: 20px;">单位查询</span><input type="text" name="name" id="searchName" value="<?php echo $searchName;?>">
                <br>
                <span class="label" style="margin-right: 20px;">月份查询</span><input type="text" name="date" class="Wdate" onfocus="WdatePicker({dateFmt: 'yyyy'})" value="<?php echo $searchDate;?>">
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
                            <th class="tl"><div>年份</div></th>
                            <th class="tl"><div>年终奖时间</div></th>
                            <th class="tl"><div>年终奖总数</div></th>
                            <th class="tl"><div>代扣税总数</div></th>
                            <th class="tl"><div>应发合计总数</div></th>
                            <th class="tl"><div>实发合计总数</div></th>
                            <th class="tl"><div>交中企总数</div></th>
                            <th class="tl"><div>操作</div></th>
                        </tr>
                        </thead>
                        <tbody  class="tbodays">
                        <input type="hidden" value="" name="type" id="type" />
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else {  foreach ($list as $k => $row){
                            ?>
                            <tr>
                                <td><div><input type="checkbox" name="unitCancelCheckbox" value="<?php echo $row['id'];?>"></div></td>
                                <td class="tl" width="4%"><div><?php echo $row['id'];?></div></td>
                                <td><div><?php echo $row['companyName'];?></div></td>
                                <td><div><a title="查看" href="<?php echo $this->createUrl('checkSalaryList', array('id'=>$row['id'],'type'=>"first"));?>" style="cursor:pointer" target="_blank" class="rowCheck theme-color"><?php echo $row['year'];?></a></div></td>
                                <td><div><?php echo $row['salaryTime'];?></div></td>
                                <td><div><?php echo $row['sum_nianzhongjiang'];?></div></td>
                                <td><div><?php echo $row['sum_daikoushui'];?></div></td>
                                <td><div><?php echo $row['sum_yingfaheji'];?></div></td>
                                <td><div><?php echo $row['sum_shifajika'];?></div></td>
                                <td><div><?php echo $row['sum_jiaozhongqi'];?></div></td>
                                <td><div><a href="javascript:void();" class="toDetail" data-id="<?php echo $row['companyId'];?>"data-name="<?php echo $row['companyName'];?>">明细</a></div></td>
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

<!--处理审核--START---->
<div class="modal hide" id="modal-deal-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>处理审核</h3>
    </div>
    <div class="modal-body">
        <input type="hidden" value="" id="dealActId">
        <a href="javascript:void();" class="btn btn-success dealAct" data-val="1" data-type="first">同意并发放</a>
        <a href="javascript:void();" class="btn btn-primary dealAct" data-val="2" data-type="first">同意</a>
        <a href="javascript:void();" class="btn btn-danger dealAct" data-val="3" data-type="first">拒绝</a>
    </div>
</div>
<!--处理审核--END---->

<script language="javascript" type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/common-js/zq.examineSalary.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script>
<script type="text/javascript">
    $(function() {
        $(".toDetail").click(function () {
            var id = $(this).attr("data-id");
            var name = $(this).attr("data-name");
            location.href = "/financial/nianSalaryAccountDetail?companyId="+id+"&companyName="+name;
        });
    });
</script>