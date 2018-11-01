<?php
/* @var $this JController */
$this->pageTitle = '首页';
?>
<div>
    <div id="content-header">
        <div id="breadcrumb">
            <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        </div>
    </div>
    <!--Action boxes-->
    <div class="container-fluid">

        <div class="quick-actions_homepage">
            <ul class="quick-actions">
                <?php
                $memu_admin = FConfig::item('admin.memu');
                if(!empty($memu_admin)){
                    $color = array('b','g','y','o','s','r');
                    foreach ($memu_admin as $a_k => $a_v) {
                        if(in_array($a_k,$this->user_menu_list)){
                            $random_num = rand(0,count($color)-1);
                            ?>
                            <li class="bg_l<?php echo $color[$random_num]?> span2"> <a href="<?php echo $a_v['controller']?>"> <i class="icon-<?php echo $a_v['icon']?>"></i><?php echo $a_v['resource']?> </a> </li>
                        <?php
                        }
                    }
                }?>
            </ul>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-picture"></i> </span>
                        <h5>最新数据</h5>
                    </div>
                    <div class="widget-content">
                        <div class="widget-content nopadding">
                            <div class="dataTables_length">
                                <span class="pull-right">
                                <span class="badge badge-warning"><?php echo $count; ?></span>&nbsp;
                                </span>
                            </div>
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                <tr>
                                    <th class="tl" width="4%"><div>编号</div></th>
                                    <th class="tl"><div>管理员</div></th>
                                    <th class="tl"><div>单位名称</div></th>
                                    <th class="tl"><div>工资状态</div></th>
                                    <th class="tl"><div>工资月份</div></th>
                                    <th class="tl"><div>操作时间</div></th>
                                    <th class="tl"><div>状态</div></th>
                                    <!--                            <th class="tl"><div>标记</div></th>-->
                                </tr>
                                </thead>
                                <tbody  class="tbodays">
                                <input type="hidden" value="" name="type" id="type" />
                                <?php if($count == 0){?>
                                    <tr><td colspan="7">没有符合条件记录</td></tr>
                                <?php }else {  foreach ($customList as $k => $row){
                                    $salaryStatusName = empty($salaryTime[$row->companyId]) ? '未做工资' : '已做工资';
                                    $grantStatusArr = FConfig::item('config.grant_status');
                                    $salaryStatus = !empty($salaryTime[$row->companyId]->salary_status) ? $salaryTime[$row->companyId]->salary_status : 0;
                                    $grantStatusName = empty($salaryTime[$row->companyId]) ? '暂无审核' : $grantStatusArr[$salaryStatus];
                                    ?>
                                    <tr>
                                        <td class="tl" width="4%"><div><?php echo $row->id;?></div></td>
                                        <td><div><?php echo $this->user['name'];?></div></td>
                                        <td><div><?php echo $row->companyName;?></div></td>
                                        <td><div><?php echo $salaryStatusName;?></div></td>
                                        <td><div><?php echo $searchDate.'-01';?></div></td>
                                        <td><div><?php echo $row->opTime;?></div></td>
                                        <td><div><a href="javascript:void();" class="dealExamine" data-status="<?php echo $salaryStatus;?>" data-id="<?php echo $salaryTime[$row->companyId]->id;?>"><?php echo $grantStatusName;?></a></div></td>
                                        <!--                                <td><div>--><?php //echo $row->remark;?><!--</div></td>-->
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
    </div>
    <!--End-Action boxes-->
    <script type="text/javascript">
        $(document).ready(function(){
            // === jQeury Gritter, a growl-like notifications === //
            $.gritter.add({
                title:  '哗啦科技有你更精彩！',
                text: '期待您de意见，反馈邮箱：<br><br><a href="mailto:admin@aladdin-holdings.com">admin@aladdin-holdings.com</a>',
                image:  'upload/img/demo/envelope.png',
                sticky: true
            });
        });
    </script>
       