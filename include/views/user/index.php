<?php
/* @var $this JController */
$this->pageTitle = '前台用户管理';
?>
<div id="content-header">
    <div id="breadcrumb">
        <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" class="current">用户管理</a> <a href="#" class="current">用户列表</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"><span class="icon"><i class="icon-th"></i></span><h5>Front-user</h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="dataTables_length">
            <span class="pull-right"> 
            <span class="badge badge-warning"><?php echo $count; ?></span>&nbsp;
            </span>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Uid</th>
                            <th>用户名</th>
                            <th>注册来源</th>
                            <th>用户等级</th>
                            <th>创建时间</th>
                            <th>更新时间</th>
                            <th>注册电话</th>
                            <th>邮箱</th>
                            <th>注册IP</th>
                            <th>用户状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else{ ?>
                        <?php foreach($userList as $k =>$v){ ?>
                                <tr>
                                    <td><?php echo $v['id'];?></td>
                                    <td><?php echo $v['nick_name'];?></td>
                                    <td><?php echo FConfig::item("config.source_type.$v->source_type");?></td>
                                    <td><?php echo FConfig::item("config.user_level.$v->user_level");?></td>
                                    <td><?php echo $v['create_time'];?></td>
                                    <td><?php echo $v['update_time'];?></td>
                                    <td><?php echo $v['phone_num'];?></td>
                                    <td><?php echo $v['email'];?></td>
                                    <td><?php echo long2ip($v['register_ip']);?></td>
                                    <td><?php echo FConfig::item("config.user_status.$v->user_status");?></td>
                                    <td>
                                        <?php
                                        if($v['user_status']) {
                                            ?>
                                            <a href="#" data-id="<?php echo $v['id'] ?>" data-status="0" class="rowModify">停用</a>
                                        <?php
                                        }else {
                                        ?>
                                            <a href="#" data-id="<?php echo $v['id'] ?>" data-status="1" class="rowModify">启用</a>
                                        <?php
                                        }
                                        ?>
                                       | <a href="#" class="">修改<?php echo $user_status?></a></td>
                                </tr>
                            <?php }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php $this->renderPartial('//page/index',array('page'=>$page)); ?>
        </div>
    </div>
</div>
<script src="/upload/common-js/ff.user.js" type="text/javascript"></script>
