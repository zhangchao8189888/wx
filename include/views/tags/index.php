<?php
/* @var $this JController */
$this->pageTitle = '标签管理';
?>
<div id="content-header">
    <div id="breadcrumb">
        <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="/tags/" class="current">标签管理</a> <a href="/tags/index" class="current">标签列表</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span-12"
            <div class="controls">
                <div style="float: right;margin-right: 20px"><a href="#" id="tag_add" class="btn btn-success">新增标签</a></div>
            </div>
        </div>
        <div class="widget-box">
            <div class="widget-content nopadding">
                <form method="post" name="form1" action="/tag/edit" onsubmit="return check();">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>标签名称</th>
                            <th>标签类型</th>
                            <th>标签数值</th>
                            <th>节点类型</th>
                            <th>排序</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($parent)){
                            foreach($parent as $key=>$item){
                                ?>
                                <tr id="<?php echo $item['id']; ?>">
                                    <td>
                                        <?php echo $item['id']; ?>
                                    </td>
                                    <td>
                                        <?php echo $item['tag_name']; ?>
                                    </td>
                                    <td>
                                        <?php echo $tagTypes[$item['tag_type']]; ?>
                                    </td>
                                    <td>
                                        -- --
                                    </td>
                                    <td>
                                        父节点
                                    </td>
                                    <td>
                                         --
                                    </td>
                                </tr>
                            <?php
                                if (!empty($list[$item['id']]))
                                foreach($list[$item['id']] as $k=>$v){?>
                                    <tr id="<?php echo $v['id']; ?>">
                                        <td style="text-align: right">
                                            ---<?php echo $v['id']; ?>
                                        </td>
                                        <td>
                                            <?php echo $v['tag_name']; ?>
                                        </td>
                                        <td>
                                            <?php echo $tagTypes[$v['tag_type']]; ?>
                                        </td>
                                        <td>
                                            <?php
                                                $pain_val = array('1','2','3');
                                                $val = $v['tag_val'];
                                                if(in_array("$val",$pain_val) && $v['parent_id'] == 4){
                                                    echo $tagGains[$v['tag_val']];
                                                }else{
                                                    echo $val;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            子节点
                                        </td>
                                        <td>
                                            <?php echo $v['tag_sort']; ?>
                                        </td>
                                        <td>
                                            <a title="排序修改" href="#" data-id="<?php echo $v['id'];?>" data-parent_id="<?php echo $v['parent_id'];?>" data-old_sort="<?php echo $v['tag_sort'];?>"  class="tag_sort pointer theme-color btn btn-small">修改</a>
                                            <a class="rowDel btn btn-danger btn-small" data-id="<?php echo $v['id'];?>" href="javascript:void(0);" >删除</a>
                                        </td>
                                    </tr>
                            <?php }}}else{?>
                            <tr><td colspan="4" align="center"><?php echo "此标签暂无内容";	?></td></tr>
                        <?php }?>

                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!--修改----start-->
<div class="modal hide" id="modal-event2">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>标签排序修改</h3>
    </div>
    <div class="modal-body">
        <form id="tag_form">
            <div class="form-horizontal form-alert">
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>标签排序号 :</label>
                    <div class="controls">
                        <input type="text" id="new_sort" name="new_sort" placeholder="标签排序号">
                        <input type="hidden" id="id" value="" />
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal-footer modal_operate">
        <button type="button" class="btn_sort btn btn-primary">保存</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<!--修改----end-->
<script src="/upload/common-js/ff.tag.js" type="text/javascript"></script>
<div class="modal hide" id="modal-add-event13">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>标签新增</h3>
    </div>
    <form id="form_add_tag" method="post">
        <div class="modal-body">
            <div class="form-horizontal form-alert">
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>标签名称 :</label>
                    <div class="controls">
                        <input type="text" id="tag_name" placeholder="标签名称">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">标签类型 :</label>
                    <div class="controls">
                        <select id="tag_type">
                            <?php
                            foreach($tagTypes as $key=>$val){
                             echo '<option value="'.$key.'">'.$val.'</option>';
                            }?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">父节点:</label>
                    <div class="controls">
                        <select  id="add_parent_id">
                            <?php
                            foreach($parent as $key=>$val){
                                echo '<option value="'.$val['id'].'">'.$val['tag_name'].'</option>';
                            }?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">数值 :</label>
                    <div class="controls">
                        <input type="text" id="tag_val" class="span3 number" placeholder="数值">（<em class="red-star">*例如：all、7-8</em>）
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">排序 :</label>
                    <div class="controls">
                        <input type="text" id="add_tag_sort" placeholder="数值">
                    </div>
                </div>

            </div>
        </div>

        <div class="modal-footer modal_operate">
            <button type="button" class="btn btn-add btn-primary">添加</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
</div>
