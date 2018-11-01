<?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16/9/25
 * Time: 下午10:29
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
                按审批状态排序：
                <select id="sortByStatus" date-type="nian">
                    <option value="0">取消排序</option>
                    <option <?php if($sort == 1) echo "selected"; ?> value="1">升序</option>
                    <option <?php if($sort == 2) echo "selected"; ?> value="2">降序</option>
                </select>
            </div>

        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title">
                        <ul class="nav nav-pills">
                            <li class=""><a href="/financial/examineSalary">一次工资</a></li>
                            <li class=""><a href="/financial/examineErSalary">二次工资</a></li>
                            <li class="active"><a href="/financial/examineNianSalary">年终奖</a></li>
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
                                <th class="tl" width="4%"><div><input type="checkbox" id="unitCancelCheckbox"></div></th>
                                <th class="tl" width="4%"><div>编号</div></th>
                                <th class="tl"><div>单位名称</div></th>
                                <th class="tl"><div>工资月份</div></th>
                                <th class="tl"><div>补扣税</div></th>
                                <th class="tl"><div>实发工资</div></th>
                                <th class="tl"><div>缴中企</div></th>
                                <th class="tl"><div>操作时间</div></th>
                                <th class="tl"><div>状态</div></th>
                                <th class="tl"><div>流水明细</div></th>
                            </tr>
                            </thead>
                            <tbody  class="tbodays">
                            <input type="hidden" value="" name="type" id="type" />
                            <?php if($count == 0){?>
                                <tr><td colspan="7">没有符合条件记录</td></tr>
                            <?php }else {  foreach ($list as $k => $row){
                                ?>
                                <tr>
                                    <td><div><input type="checkbox" name="unitCancelCheckbox" value="<?php echo $row['id']?>"></div></td>
                                    <td class="tl" width="4%"><div><?php echo $row['id'];?></div></td>
                                    <td><div><?php echo $row['companyName'];?></div></td>
                                    <td><div><a title="查看" href="<?php echo $this->createUrl('checkSalaryList', array('id'=>$row['id'],'type'=>"nian"));?>" style="cursor:pointer" target="_blank" class="rowCheck theme-color"><?php echo $row['salaryTime'];?></a></div></td>
                                    <td><div><?php echo $row['sum_daikoushui'];?></div></td>
                                    <td><div><?php echo $row['shifa'];?></div></td>
                                    <td><div><?php echo $row['pay_zhongqi'];?></div></td>
                                    <td><div><?php echo $row['op_salaryTime'];?></div></td>
                                    <?php
                                    $class = '';
                                    $buttonHtml = "";
                                    if($row['salary_status']==0) {
                                        $class = 's-black';
                                        $buttonHtml = FConfig::item('config.examine_status.'.$row['salary_status']);
                                    } elseif ($row['salary_status']==1) {
                                        $class = 's-btn';
                                        $buttonHtml = "<a href='javascript:void();' class='s-btn dealExamine' data-type='first' data-status='".$row['salary_status']."' data-id='".$row['id']."'>处理审批</a>";
                                    } elseif ($row['salary_status']==2) {
                                        $class = 's-sure';
                                        $buttonHtml = FConfig::item('config.examine_status.'.$row['salary_status']);
                                    } elseif ($row['salary_status']==3) {
                                        $class = 's-unsure';
                                        $buttonHtml = FConfig::item('config.examine_status.'.$row['salary_status']);
                                    }
                                    ?>
                                    <td><div class="<?php echo $class;?>"><?php echo $buttonHtml;?></div></td>
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
            <a href="javascript:void();" class="btn btn-primary dealAct" data-val="2" data-type="nian">同意</a>
            <a href="javascript:void();" class="btn btn-primary dealAct" data-val="3" data-type="nian">拒绝</a>
        </div>
    </div>
    <!--处理审核--END---->

    <script language="javascript" type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/common-js/zq.examineSalary.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script><?php
/**
 * Created by PhpStorm.
 * User: zhangchao8189888
 * Date: 16/10/7
 * Time: 上午9:35
 */ 