<?php
/**
 * Created by PhpStorm.
 * User: zhangchao-rj
 * Date: 2018/7/19
 * Time: 上午11:14
 */

$typeList=$data['typeList'];
?>

<div id="content-header">
    <div id="breadcrumb">
        <a href="/index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="/product/" class="current">员工管理</a>
        <a href="/product/productList" class="current">工资查询管理</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="controls">
                    <form method="get" action="">
                        选择单位：<input type="text" name="customer_name" value="<?php echo $search_name['customer_name']; ?>" style="margin: 2px 10px;"/>
                        身份证号：<input type="text" name="e_num" value="<?php echo $search_name['e_num']; ?>" style="margin: 2px 10px;"/>
                        <input type="submit" value="搜索" name="yt0" class="btn btn-success" id="search_by"/>
                    </form>
                    <div style="float: right;margin-right: 5px"><a href="#" id="add_btn" class="btn btn-success"/>新增查询用户</a></div>
                </div>
            </div>
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
                            <th class="tl" width="4%"><div></div></th>
                            <th class="tl"><div>单位名称</div></th>
                            <th class="tl"><div>姓名</div></th>
                            <th class="tl"><div>身份证号</div></th>
                            <th class="tl"><div>用户名</div></th>
                            <th class="tl"><div>密码</div></th>
                            <th class="tl"><div>最近登录时间</div></th>
                            <th class="tl"><div>操作</div></th>
                        </tr>
                        </thead>
                        <tbody  class="tbodays">
                        <input type="hidden" value="" name="type" id="type" />
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else {  foreach ($data_list as $k => $row){
                            ?>
                            <tr>
                                <td class="tl" width="4%"><div></div></td>
                                <td><div><?php echo $row['e_company'];?></div></td>
                                <td><div><?php echo $row['e_name'];?></div></td>
                                <td><div><?php echo $row['e_num'];?></div></td>
                                <td><div><?php echo $row['name'];?></div></td>
                                <td><div><?php echo $row['password'];?></div></td>
                                <td><div><?php echo $row['last_login_time'];?></div></td>
                                <td>
                                    <a title="编辑" style="cursor: pointer" class="edit_btn" data-id="<?php echo $row->id;?>" >编辑</a>|
                                    <a title="查看" style="cursor: pointer"  class="check_btn" data-id="<?php echo $row->id;?>" >查看</a>
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
<!--添加--START---->
<div class="modal hide" id="modal-add-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>新增工资查询</h3>
    </div>

    <form  method="post" id="custom_form_add">
        <div class="modal-body">
            <div class="form-horizontal form-alert">
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>企业名称 :</label>
                    <div class="controls">
                        <input type="text"  id="customer_name" name="customer_name"  placeholder="企业名称"/>
                        <input type="hidden" id="cid" name="cid" />
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer modal_operate">
            <button type="button" class="btn btn-primary addUser">新增查询用户</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
</div>
<!--添加--END---->

<script type="text/javascript">
    $(function (){

        var BaseUrl = "<?php echo FF_DOMAIN;?>";

        $("#add_btn").click(function(){
            $("#cid").val('');
            $('#custom_form_add')[0].reset();
            $("#modal-add-event").modal({show:true});
        });
        $(".addUser").click(function () {
            var obj = {
                "customer_name" : $("#customer_name").val(),
            };
            $.ajax(
                {
                    type: "POST",
                    url: GLOBAL_CF.DOMAIN+'/employ/AddUserSearchSalaryAjax',
                    data: obj,
                    dataType:'json',
                    success: function(data){
                        if (data.status > 100000) {
                            alert(data.content);
                            return;
                        }
                        window.location.reload();
                    }
                }
            );
        })
    });
</script>
<script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script>