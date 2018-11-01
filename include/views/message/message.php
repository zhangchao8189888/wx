<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/15
 * Time: 11:17
 */
$this->pageTitle = '短信统计';
?>
<div id="content-header">

    <div id="breadcrumb">
        <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" class="current">短信管理</a> <a href="#" class="current">短信统计</a>
    </div>
</div>
<div class="container-fluid">
    <div class="accordion-heading">
        <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGTwo" data-toggle="collapse" class="collapsed"> <span class="icon"><i class="icon-circle-arrow-right"></i></span>
                <h5>高级搜索</h5>
            </a> </div>
    </div>
    <div class="accordion-body collapse" id="collapseGTwo" style="height: 0px;">
    <form name="search-form" class="search-form" action="/message/message">
        <div class="search-message">
            电话号：<input type="text" value="<?php echo $mobile_no?>" name="mobile_no" id="mobile_no" /><br />
            时间：开始<input type="text" id="begin" name="send_time_begin" value="<?php echo $send_time_begin?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd H:m:s',realDateFmt:'yyyy-MM-dd H:i:s'})"/>结束<input type="text" id="end" name="send_time_end" value="<?php echo $send_time_end?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd H:m:s',realDateFmt:'yyyy-MM-dd H:m:s'})"/><br />
            <input type="submit" class="search-mobile btn btn-primary" value="查找" />
        </div>
    </form>
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
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="">序号</th>
                            <th width="50%">手机号</th>
                            <th width="">发送次数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else{ ?>
                            <?php foreach($msgList as $k =>$v){
                                ?>
                                <tr>
                                    <td><div><?php
                                            static $i = 1;
                                            echo $i++;
                                            ?></div></td>
                                    <td><?php echo $v->mobile_no?></td>
                                    <td><?php echo $v->cnt?></td>
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

