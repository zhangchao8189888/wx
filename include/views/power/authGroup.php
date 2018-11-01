<div id="content-header">
    <div id="breadcrumb">
        <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="/power/" class="current">权限管理</a> <a href="/power/authGroup" class="current">权限组表</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="controls">
                <div style="float: right;margin-right: 20px"><a href="#" id="add_btn" class="btn btn-success" />添加组</a></div>
            </div>
        </div>
        <div class="span12">
            <div class="widget-box">
                <div class="dataTables_length">
                                <span class="pull-right">
                                <span class="badge badge-warning"><?php echo $count; ?></span>&nbsp;
                                </span>
                </div>
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="tl"><div>ID</div></th>
                        <th class="tl"><div>名称</div></th>
                        <th class="tl"><div>规则</div></th>
                        <th class="tl"><div>描述</div></th>
                        <th class="tl"><div>状态</div></th>
                        <th class="tl"><div>操作</div></th>
                    </tr>
                    </thead>
                    <tbody  class="tbodays">
                    <?php if($count == 0){?>
                        <tr><td colspan="7">没有符合条件记录</td></tr>
                    <?php }else {  foreach ($authGroupList as $k => $row){?>
                        <tr >
                            <td><div><?php echo $row->id;?></div></td>
                            <td><div><?php echo $row->title;?></div></td>
                            <td><div><?php echo $row->rules;?></div></td>
                            <td><div><?php echo $row->describe;?></div></td>
                            <td><div><?php echo $row->status;?></div></td>
                            <td>
                                <a title="修改" href="#" data-id="<?php echo $row->id;?>"  class="edit_btn pointer theme-color btn btn-small">修改</a>
                                <a title="删除" href="#" data-id="<?php echo $row->id;?>"  class="rowDelete pointer theme-color btn btn-danger btn-small">删除</a>
                            </td>
                        </tr>
                    <?php }}?>
                    </tbody>
                    <input type="hidden" id="menus" value="<?php echo $menus;?>" />
                </table>

                <?php $this->renderPartial('//page/index',array('page'=>$page)); ?>
            </div>
        </div>
    </div>
</div>


<!--添加--START---->
<div class="modal hide" id="modal-add-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>添加</h3>
    </div>
    <div class="modal-body">
        <form id="group_form_add">
            <div class="form-horizontal form-alert">
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>分组名称 :</label>
                    <div class="controls">
                        <input id="group_name_add" placeholder="分组名称">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>状态 :</label>
                    <div class="controls">
                        <input type="radio" class="status_add" value="1" checked/>开启
                        <input type="radio" class="status_add" value="0" />关闭
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>规则 :</label>
                    <div class="controls" id="rule_checkbox">
                        <?php foreach ($menus as $k=>$v) { ?>
                            <input type="checkbox" name="rule_add" value="<?php echo $k; ?>"><?php echo $v['resource']; ?>
                        <?php }?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>描述 :</label>
                    <div class="controls">
                        <textarea id="describe_add" placeholder="描述"></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer modal_operate">
        <button type="button" class="save_add btn btn-primary">保存</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<!--添加--END---->
<!--修改--START---->
<div class="modal hide" id="modal-edit-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>修改</h3>
    </div>
    <div class="modal-body">
        <form id="group_form_edit">
            <div class="form-horizontal form-alert">
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>分组名称 :</label>
                    <div class="controls">
                        <input id="group_name_edit" placeholder="分组名称">
                        <input type="hidden" id="group_id">
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>状态 :</label>
                    <div class="controls">
                        <input type="radio" name="status_edit" class="status_edit" value="1" />开启
                        <input type="radio" name="status_edit" class="status_edit" value="0" />关闭
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>规则 :</label>
                    <div class="controls">
                        <?php foreach ($menus as $k=>$v) { ?>
                            <input type="checkbox" name="rule_edit" value="<?php echo $k; ?>"><?php echo $v['resource']; ?>
                        <?php }?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>描述 :</label>
                    <div class="controls">
                        <textarea id="describe_edit" placeholder="描述"></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer modal_operate">
        <button type="button" class="save_edit btn btn-primary">保存</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<!--修改--END---->

<script src="<?php echo FF_STATIC_BASE_URL;?>/common-js/zq.group.js" type="text/javascript"></script>



