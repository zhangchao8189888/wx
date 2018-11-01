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
        <a href="/index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="#" class="current">产品管理</a>
        <a href="#" class="current">产品发布</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="controls">
                <div style="float: right;margin-right: 20px"><a href="#" id="pro_add" class="btn btn-success"/>产品发布</a></div>
            </div>
        </div>
        <div class="span12">
            <div class="widget-box">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="tl"><div>ID</div></th>
                        <th class="tl"><div>发布编号</div></th>
                        <th class="tl"><div>产品名称</div></th>
                        <th class="tl"><div>产品类型</div></th>
                        <th class="tl"><div>收益率</div></th>
                        <th class="tl"><div>起投资金</div></th>
                        <th class="tl"><div>项目总金额</div></th>
                        <th class="tl"><div>利息分配</div></th>
                        <th class="tl"><div>投资期限</div></th>
                        <th class="tl"><div>发布状态</div></th>
                        <th class="tl"><div>发布时间</div></th>
                        <th class="tl"><div>操作</div></th>
                    </tr>
                    </thead>
                    <tbody  class="tbodays">
                    <?php if($count == 0){?>
                        <tr><td colspan="10">没有符合条件记录</td></tr>
                    <?php }else {  foreach ($publishList as $k => $row){?>
                        <tr >
                            <td><div><?php echo $row['id'];?></div></td>
                            <td><div><?php echo $row['publish_code'];?></div></td>
                            <td><div><?php echo $row['product']['product_name'];?></div></td>
                            <td><div>
                                    <?php
                                    if (!empty($proTypeList)) {
                                        foreach($proTypeList as $key=>$val){
                                            if($val['id'] == $row['product']['product_type_id']){
                                                echo $val['type_name'];
                                            }
                                        }
                                    }
                                    ?>
                                </div></td>
                            <td><div><?php echo $row['product']['yield_rate_year'];?></div></td>
                            <td><div><?php echo $row['product']['fund_min_val'];?></div></td>
                            <td><div><?php echo $row['product']['upper_limit'];?></div></td>
                            <td><div><?php echo $invest_issue_types[$row['product']['invest_issue_type']];?></div></td>
                            <td><div><?php echo $row['product']['earn_days'];?></div></td>
                            <td><div><?php echo $publish_status[$row['publish_status']];?></div></td>
                            <td><div><?php echo $row['create_time'];?></div></td>

                            <td class="tr">
                                <a title="推送" href="#" data-id="<?php echo $row['id'];?>"  class="pub_push pointer theme-color">推送</a>|
                            <?php if($row['publish_status'] == 1){?>
                                <a title="停用" href="#" data-id="<?php echo $row['id'];?>" data-status="-1"  class="pub_modify pointer theme-color">停用</a>
                            <?php }else{?>
                                <a title="开启" href="#" data-id="<?php echo $row['id'];?>" data-status="1"  class="pub_modify pointer theme-color">开启</a>
                            <?php }?>
                            </td>
                        </tr>
                    <?php }}?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script src="/upload/common-js/ff.publish.js" type="text/javascript"></script>

<!--产品发布添加--START---->
<div class="modal hide" id="modal-add-event">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>产品新增</h3>
    </div>
    <div class="modal-body">
        <form id="pro_form">
            <div class="form-horizontal form-alert">
                <div class="control-group">
                    <label class="control-label">产品:</label>
                    <div class="controls">
                        <select id="product_id">
                            <?php
                            if (is_array($productList)) {

                                foreach($productList as  $val){
                                    echo '<option value="'.$val['id'].'">'.$val['product_name'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <div class="modal-footer modal_operate">
        <button type="button" class="btn btn-warning btn_publish">发布</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<!--产品添加--END---->


<!--产品推送--START---->
<div class="modal hide" id="modal-push-event">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>产品推送</h3>
    </div>
    <div class="modal-body">
    <form id="pro_push_form">
        <div class="form-horizontal form-alert">
            <div class="control-group">
                <label class="control-label">产品:</label>
                <div class="controls">
                    <th><input type="checkbox" id="checkbox_per_id" value="1" />个人中心</th>
                    <input type="checkbox" id="checkbox_index_id" value="1"/>首页
                    <input type="hidden" id="push_id">
                </div>
            </div>

        </div>
    </form>
    </div>

    <div class="modal-footer modal_operate">
        <button type="button" class="btn btn-warning btn_push">推送</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<!--产品推送--END---->