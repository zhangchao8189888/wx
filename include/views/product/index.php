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
        <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="/product/" class="current">产品管理</a> <a href="/product/index" class="current">产品类型</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="controls">
                <div style="float: right;margin-right: 20px"><a href="#" id="com_add" class="btn btn-success" onclick="javascript:MathRand();"/>添加产品类型</a></div>
            </div>
        </div>
        <div class="span12"><div class="widget-box">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th class="tl"><div>ID</div></th>
                    <th class="tl" width="7%"><div>类型名称</div></th>
                    <th class="tl" width="10%"><div>类型编号</div></th>
                    <th class="tl"><div>类型描述</div></th>
                    <th class="tl"><div>修改时间</div></th>
                    <th class="tl"width="5%"><div>排序</div></th>
                    <th class="tl" width="15%"><div>操作</div></th>
                </tr>
                </thead>
                <tbody  class="tbodays">
                <?php if($count == 0){?>
                    <tr><td colspan="7">没有符合条件记录</td></tr>
                <?php }else {  foreach ($tdataList as $k => $row){?>
                    <tr >
                        <td><div><?php echo $row->id;?></div></td>
                        <td><div><?php echo $row->type_name;?></div></td>
                        <td><div><?php echo $row->type_code;?></div></td>
                        <td><div><?php echo $row->description;?></div></td>
                        <td><div><?php echo $row->update_time;?></div></td>
                        <td><div><?php echo $row->type_sort;?></div></td>
                        <td>
                            <a title="排序修改" href="#" data-id="<?php echo $row->id;?>" data-sort="<?php echo $row->type_sort;?>"  class="type_sort pointer theme-color btn btn-small">修改</a>
                            <a title="删除" href="#" data-id="<?php echo $row->id;?>"  class="rowDelete pointer theme-color btn btn-danger btn-small">删除</a>
                        </td>
                    </tr>
                <?php }}?>
                </tbody>
            </table>

            <?php $this->renderPartial('//page/index',array('page'=>$page)); ?>
        </div>
    </div>
</div>

<script src="/upload/common-js/ff.ptype.js" type="text/javascript"></script>
<div class="modal hide" id="modal-event1">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>新增产品类型</h3>
    </div>
    <form action="" id="company_validate" method="post" class="form-horizontal"  novalidate="novalidate">
        <div class="modal-body">
            <div class="designer_win">
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>类型名称：<input type="text" maxlength="20" id="type_name"name="type_name" /></div>
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>类型编号：<input type="text" readonly="readonly"maxlength="20" id="type_code"name="type_code" /></div>
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>类型描述：<textarea maxlength="500" id="type_desc" class="desc_size" name="type_desc"></textarea></div>
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>类型排序：<input type="text" maxlength="20" id="add_type_sort"name="add_type_sort" /></div>
            </div>
        </div>

        <div class="modal-footer modal_operate">
            <button type="button" class="btn btn-add btn-primary">添加</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
</div>

    <!--排序弹出框--start-->

    <div class="modal hide" id="modal-event2">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>产品类型排序修改</h3>
        </div>
        <div class="modal-body">
            <form id="pro_type_form">
                <div class="form-horizontal form-alert">
                    <div class="control-group">
                        <label class="control-label"><em class="red-star">*</em>类型排序号 :</label>
                        <div class="controls">
                            <input type="text" id="new_sort" name="new_sort" placeholder="类型排序号">
                            <input type="hidden" id="id" value="" />
                        </div>
                    </div>
            </form>
        </div>

        <div class="modal-footer modal_operate">
            <button type="button" class="btn_sort btn btn-primary">添加</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </div>
    <!--排序弹出框---end-->
<style type="text/css">
    .desc_size{
        width: 200px;
        height: 130px;
    }
</style>


