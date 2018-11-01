<div id="content-header">
    <div id="breadcrumb">
        <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="/power/" class="current">权限管理</a> <a href="/power/index" class="current">用户表</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="controls">
                <div style="float: right;margin-right: 20px"><a href="#" id="com_add" class="btn btn-success" onclick="javascript:MathRand();"/>添加组</a></div>
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
                        <th class="tl"><div>用户名</div></th>
                        <th class="tl"><div>最后登录时间</div></th>
                        <th class="tl"><div>操作</div></th>
                    </tr>
                    </thead>
                    <tbody  class="tbodays">
                    <?php if($count == 0){?>
                        <tr><td colspan="7">没有符合条件记录</td></tr>
                    <?php }else {  foreach ($authRuleList as $k => $row){?>
                        <tr >
                            <td><div><?php echo $row->id;?></div></td>
                            <td><div><?php echo $row->name;?></div></td>
                            <td><div><?php echo $row->last_login_time;?></div></td>
                            <td>
                                <a title="排序修改" href="#" data-id="<?php echo $row->id;?>"  class="type_sort pointer theme-color btn btn-small">修改</a>
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
</div>



