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
        <a href="/product/" class="current">档案管理</a>
        <a href="/product/productList" class="current">人员信息</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="controls">
                    <div style="float: right;margin-right: 5px"><a href="#" id="emp_add" class="btn btn-success"/>新增</a></div>
                    <div style="float: right;margin-right: 5px"><a href="#" id="emp_import" class="btn btn-success"/>导入</a></div>
                    <div style="float: right;margin-right: 5px"><a href="#" id="emp_modify" class="btn btn-success"/>更改状态</a></div>
                </div>
            </div>
            <div class="widget-box">
                <div class="widget-title">
                    <ul class="nav nav-pills">
                        <li class="active"><a href="index.php?action=Product&mode=getProductList">在职员工</a></li>
                        <li class=""><a href="index.php?action=Product&mode=getProductNumList">离职员工</a></li>
                        <li class=""><a href="index.php?action=Product&mode=getProductNumList">退休员工</a></li>
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
                            <th class="tl" width="4%"><div></div></th>
                            <th class="tl" width="4%"><div>ID</div></th>
                            <th class="tl"><div>员工编号</div></th>
                            <th class="tl"><div>姓名</div></th>
                            <th class="tl"><div>状态</div></th>
                            <th class="tl"><div>入职时间</div></th>
                            <th class="tl"><div>操作</div></th>
                        </tr>
                        </thead>
                        <tbody  class="tbodays">
                            <input type="hidden" value="" name="type" id="type" />
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else {  foreach ($empList as $k => $row){
                            $empInfo = $row->employ_info;
                            ?>
                            <tr >
                                <td><div><input type="checkbox" value="<?php echo $row->id;?>" name="check_emp"/></div></td>
                                <td><div><?php echo $row->id;?></div></td>
                                <td><div><?php echo $empInfo->e_yuangong_bianhao;?></div></td>
                                <td><div><a  href="#" class="checkInfo pointer" data-id="<?php echo $row->id;?>" ><?php echo $row->e_name;?></a></div></td>
                                <td><div><?php echo $employ_status[$row->e_status];?></div></td>
                                <td><div><?php echo $row->e_hetong_date;?></div></td>
                                <td>
                                    <a title="编辑" href="#" data-id="<?php echo $row->id;?>" >编辑</a>|
                                    <a title="查看" href="#" data-id="<?php echo $row->id;?>" >查看</a>
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


    <script type="text/javascript">
        $(function (){

            var BaseUrl = "<?php echo FF_DOMAIN;?>";
        });
    </script>
    <script language="javascript" type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/common-js/zq.employ.js" charset="utf-8"></script>