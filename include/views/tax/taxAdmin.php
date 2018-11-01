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
        <a href="/financial/" class="current">个税管理</a>
        <a href="/financial/examineSalary" class="current">个税设置</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <!--            <span class="btn btn-primary" style="margin-right: 20px;" id="cancelUnit">取消管理</span><span class="btn btn-primary" id="addUnit">添加管理单位</span><br><br>-->
            <form action="<?php echo FF_DOMAIN.'/'.$this->route;?>" method="get">
                <span class="label" style="margin-right: 20px;">单位查询</span><input type="text" name="name" id="searchName" value="<?php echo $searchName;?>">
                <br>
                <input type="submit" style="margin-left: 10px;margin-bottom: 6px;" value="查询">
            </form>

            按审批状态排序：
            <select id="sortByStatus"  date-type="first">
                <option value="0">取消排序</option>
                <option <?php if($sort == 1) echo "selected"; ?> value="1">升序</option>
                <option <?php if($sort == 2) echo "selected"; ?> value="2">降序</option>
            </select>
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
                            <th class="tl"><div>工资发放月份</div></th>
                        </tr>
                        </thead>
                        <tbody  class="tbodays">
                        <input type="hidden" value="" name="type" id="type" />
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else {
                            $i = 1;
                            foreach ($list as $k => $row){
                            ?>
                            <tr>
                                <td><div><input type="checkbox" name="unitCancelCheckbox" value="<?php echo $row->id;?>"></div></td>
                                <td class="tl" width="4%"><div><?php echo $i;$i++;?></div></td>
                                <td><div><?php echo $row->customer_name;?></div></td>

                                <td><div>
                                        <select class="sal_send" data-id="<?php echo $row->id;?>">
                                            <option <?php if($row->salary_send == 0) echo "selected";?> value="0" >未设置</option>
                                            <option <?php if($row->salary_send == 1) echo "selected";?> value="1">本月发薪</option>
                                            <option <?php if($row->salary_send == 2) echo "selected";?> value="2">下月发薪</option>
                                        </select>
                                    </div></td>

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


<script language="javascript" type="text/javascript"charset="utf-8">
    $(function(){
        $(".sal_send").change(function(){
            var id = $(this).attr("data-id");
            var sal_send = $(this).children('option:selected').val();
            $.ajax({
                type: 'post',
                url: '/tax/modifySalSend',
                dataType: 'json',
                data: {
                    id : id,
                    sal_send : sal_send
                },
                success: function(data){
                    if (data.status > 100000) {

                        alert('修改失败！');
                        window.location.reload();
                    } else {
                        alert('修改成功！');
                        //window.location.reload();
                    }
                },

                error: function(XHR, textStatus, errorThrown){
                    alert('服务器没有响应，请稍后重试');
                }
            });
        });
    });

</script>
<script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script>