<div id="content-header">
    <div id="breadcrumb">
        <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="/power/" class="current">权限管理</a> <a href="/power/index" class="current">用户表</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">

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
                        <th class="tl"><div>用户名</div></th>
                        <th class="tl"><div>角色</div></th>
                        <th class="tl"><div>标识</div></th>
                        <th class="tl"><div>最后登录时间</div></th>
                        <th class="tl"><div>操作</div></th>
                    </tr>
                    </thead>
                    <tbody  class="tbodays">
                    <?php if($count == 0){?>
                        <tr><td colspan="7">没有符合条件记录</td></tr>
                    <?php }else {  foreach ($adminList as $k => $row){?>
                        <tr >
                            <td><div><?php echo $row->id;?></div></td>
                            <td><div><?php echo $row->name;?></div></td>
                            <td><div><?php echo $row->admin_type;?></div></td>
                            <td><div><?php echo $row->del_flag;?></div></td>
                            <td><div><?php echo $row->last_login_time;?></div></td>
                            <td>
                                <a title="排序修改" href="#" data-id="<?php echo $row->id;?>"   class="edit_btn pointer theme-color btn btn-small">修改权限组</a>
<!--                                <a title="删除" href="#" data-id="--><?php //echo $row->id;?><!--"  class="rowDelete pointer theme-color btn btn-danger btn-small">删除</a>-->
                            </td>
                        </tr>
                    <?php }}?>
                    </tbody>
                </table>

                <?php $this->renderPartial('//page/index',array('page'=>$page)); ?>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo FF_STATIC_BASE_URL;?>/common-js/zq.admin.js" type="text/javascript"></script>

<!--修改--START---->
<div class="modal hide" id="modal-edit-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>修改</h3>
    </div>
    <div class="modal-body">
        <form id="admin_form">
            <div class="form-horizontal form-alert">
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>分组名称 :</label>
                    <div class="controls">
                        <textarea  placeholder="行业名称"></textarea><br />
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer modal_operate">
        <button type="button" class="btn_edit btn btn-primary">保存</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<!--修改--END---->


