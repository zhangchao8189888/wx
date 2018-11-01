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
        <a href="/financial/" class="current">财务管理</a>
        <a href="/financial/examineSalary" class="current">工资表</a>
    </div>
</div>
<div class="widget-title">
    <ul class="nav nav-pills">
        <li class="<?php echo empty($active) || $active=='first' ? 'active' : '';?>"><a href="<?php echo $this->createUrl('salaryListPage');?>">一次工资</a></li>
        <li class="<?php echo $active=='second' ? 'active' : '';?>"><a href="<?php echo $this->createUrl('salarySecondSearchPage');?>">二次工资</a></li>
        <li class="<?php echo $active=='nian' ? 'active' : '';?>"><a href="<?php echo $this->createUrl('salaryNianSearchPage');?>">年终奖</a></li>
    </ul>

</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!--            <span class="btn btn-primary" style="margin-right: 20px;" id="cancelUnit">取消管理</span><span class="btn btn-primary" id="addUnit">添加管理单位</span><br><br>-->
            <form action="<?php echo FF_DOMAIN.'/'.$this->route;?>" method="get">
                <span class="label" style="margin-right: 20px;">单位查询</span><input type="text" name="name" id="companySearch" value="<?php echo $searchName;?>">
                <br>
                <span class="label" style="margin-right: 20px;">工资月份</span>
                <input type="text" id="date" name="date" value="<?php echo $searchDate;?>"  onFocus="WdatePicker({isShowClear:false,readOnly:false,'dateFmt':'yyyy-MM'})"/>
                <br>
                <span class="label" style="margin-right: 20px;">起始月份查询</span><input type="text" name="start_date" class="Wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM'})" value="<?php echo $start_date;?>">
                <span class="label" style="margin-right: 20px;">结束月份查询</span><input type="text" name="end_date" class="Wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM'})" value="<?php echo $end_date;?>">
                <input type="submit" style="margin-left: 10px;margin-bottom: 6px;" value="查询">
            </form>
            按审批状态排序：
            <select id="sortByStatus"  date-type="first">
                <option value="0">取消排序</option>
                <option <?php if($sort == 1) echo "selected"; ?> value="1">升序</option>
                <option <?php if($sort == 2) echo "selected"; ?> value="2">降序</option>
            </select>


            <input type="button" value="全选" id="selectAll" />
            <input type="button" value="全不选" id="unSelect" />
            <span class="btn btn-primary" id="export">导出</span>
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
                            <th class="tl"><div>工资类型</div></th>
                            <th class="tl"><div>实发工资</div></th>
                            <th class="tl"><div>缴中企</div></th>
                            <th class="tl"><div>操作时间</div></th>
                            <th class="tl"><div>状态</div></th>
                        </tr>
                        </thead>
                        <tbody  class="tbodays" id="tbodays">
                        <input type="hidden" value="" name="type" id="type" />
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else {  foreach ($list as $k => $row){
                            ?>
                            <tr>
                                <td><div><input type="checkbox" name="unitCancelCheckbox" value="<?php echo $row['id'];?>"></div></td>
                                <td class="tl" width="4%"><div><?php echo $row['id'];?></div></td>
                                <td><div><?php echo $row['companyName'];?></div></td>
                                <td><div><a title="查看" href="<?php echo $this->createUrl('checkSalaryList', array('id'=>$row['id'],'type'=>"first"));?>" style="cursor:pointer" target="_blank" class="rowCheck theme-color"><?php echo $row['salaryTime'];?></a></div></td>
                                <td><div>一次工资</div></td>
                                <td><div><?php echo $row['shifa'];?></div></td>
                                <td><div><?php echo $row['pay_zhongqi'];?></div></td>
                                <td><div><?php echo $row['op_salaryTime'];?></div></td>
                                <td class="tl">
                                    <a title="查看" href="<?php echo $this->createUrl('checkSalaryList', array('id'=>$row['id'],'type'=>$active));?>" style="cursor:pointer" target="_blank" class="rowCheck theme-color">查看</a>
                                    <?php
                                    $s = $row['salary_status'];
                                    $color = "color: #ABABAB";
                                    if ($s == 1) {
                                        $color = "color: #4db6ff";
                                    } elseif ($s == 2) {
                                        $color = "color: #26ab77";
                                    } elseif ($s == 3) {
                                        $color = "color: #FF293F";
                                    }
                                    $s = $row['salary_status'];
                                    echo "<span style='{$color}'>{$salary_status[$s]}</span>";
                                    ?>
                                </td>
                            </tr>
                        <?php }}?>
                        </tbody>
                    </table>
                </div>
                <?php /*$this->renderPartial('//page/index',array('page'=>$page)); */?>
            </div>
        </div>
    </div>
</div>
<form action="/financial/salaryListExport" target="_blank" method="post" id="viewExportPage">
    <input type="hidden" name="salaryIdList" id="salaryIdList" value=""/>
    <input type="hidden" name="type" id="type" value="<?php echo $active;?>"/>
</form>
<script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/common-js/salary/zq.salarySearch.js"></script>
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
        $("#sortByStatus").change(function () {
            var sortVal = $(this).val();
            var type = $(this).attr("date-type");
            var url = "";
            if (type == 'first') {
                url = "salaryListPage";
            } else if (type == 'er') {
                url = "salaryErSearchPage";
            } else if (type == 'nian') {
                url = "salaryNianSearchPage";
            }
            location.href = "/financial/"+url+"?sort="+sortVal;
        });
        $("#selectAll").click(function () {//全选
            $("#tbodays :checkbox").each( function() {
                $(this).attr('checked', true);
                $(this).parent().addClass('checked');
            });
        });

        $("#unSelect").click(function () {//全不选
            $("#tbodays :checkbox").each( function() {
                $(this).attr('checked', false);
                $(this).parent().removeClass('checked');
            });
        });
        $("#export").click(function () {
            var id = $(this).attr("data-id");
            var data = {};
            var id_list = [];
            $("#tbodays :checkbox").each( function() {
                if ($(this).attr('checked')) {
                    id_list.push($(this).val());
                }
            });
            if (id_list.length == 0) {
                alert("选择要导出的单位！");
                return;
            }
            $("#salaryIdList").val(id_list);
            $("#viewExportPage").submit();
        });
    });
</script>