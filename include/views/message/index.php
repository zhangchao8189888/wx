<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/15
 * Time: 11:17
 */
$this->pageTitle = '短信列表';
?>
<div id="content-header">
    <div id="breadcrumb">
        <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" class="current">短信管理</a> <a href="#" class="current">短信列表</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-content nopadding">
                    <div class="dataTables_length">
            <span class="pull-right">
            <span class="badge badge-warning"><?php echo $count; ?></span>&nbsp;
            </span>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="4%">Mid</th>
                            <th width="10%">手机号</th>
                            <th width="4%">验证码</th>
                            <th width="4%">发送时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else{ ?>
                            <?php foreach($msgList as $k =>$v){ ?>
                                <tr>
                                    <td><?php echo $v->id?></td>
                                    <td><?php echo $v->mobile_no?></td>
                                    <td><?php echo $v->mess_code?></td>
                                    <td><?php echo $v->send_time?></td>
                                </tr>
                        <?php }}?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php $this->renderPartial('//page/index',array('page'=>$page)); ?>
        </div>
    </div>
</div>

