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
        <a href="/product/" class="current">产品管理</a>
        <a href="/product/productList" class="current">产品列表</a>
    </div>
</div>
<div class="container-fluid">
    <div class="accordion-heading">
        <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseGTwo" data-toggle="collapse" class="collapsed"> <span class="icon"><i class="icon-circle-arrow-right"></i></span>
                <h5>高级搜索</h5>
            </a> </div>
    </div>
    <div class="accordion-body collapse" id="collapseGTwo" style="height: 0px;">
        <form name="search-form" class="search-form" action="/product/productList">
            <div class="search-message">
                产品名称 ：<input type="text" value="<?php echo $product_name;?>" name="search_product_name" /><br />
                产品类型 ：<input type="text" value="<?php echo $product_type;?>" name="search_product_type" /><br />
                投资期限 ：<select name="search_earn_days">
                    <?php foreach ($config_earn_days as $k => $v){
                        if($k == $earn_days){?>
                            <option value="<?php echo $k;?>" selected="selected"><?php echo $v;?></option>
                        <?php }else{?>
                            <option value="<?php echo $k;?>"><?php echo $v;?></option>
                        <?php }}?>
                </select><br />
                <input type="submit" class="search-mobile btn btn-primary" value="查找" />
            </div>
        </form>
    </div>
    <div class="row-fluid">

        <div class="span12">
            <div class="controls">
                <div style="float: right;margin-right: 20px"><a href="#" id="pro_add" class="btn btn-success"/>添加产品</a></div>
            </div>
        </div>

        <div class="span12">
            <div class="widget-box">
                        <div class="widget-content nopadding">
                            <div class="dataTables_length">
                                <span class="pull-right">
                                <span class="badge badge-warning"><?php echo $count; ?></span>&nbsp;
                                </span>
                            </div>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th class="tl" width="4%"><div>ID</div></th>
                                <th class="tl"><div>产品编号</div></th>
                                <th class="tl"><div>产品名称</div></th>
                                <th class="tl"><div>产品类型</div></th>
                                <th class="tl"><div>收益率</div></th>
                                <th class="tl"><div>起投资金</div></th>
<!--                                <th class="tl"><div>保障级别</div></th>-->
                                <th class="tl"><div>项目总金额</div></th>
                                <th class="tl"><div>利息分配</div></th>
<!--                                <th class="tl"><div>起息日类别</div></th>-->
                                <th class="tl"><div>T+N类别</div></th>
                                <th class="tl"><div>T+N天数</div></th>
                                <th class="tl"width="8%"><div>固定日期开始</div></th>
                                <th class="tl"width="8%"><div>固定日期结束</div></th>
                                <th class="tl"><div>投资期限</div></th>
                                <th class="tl"><div>投资期限类型</div></th>
                                <th class="tl"width="6%"><div>操作</div></th>
                            </tr>
                            </thead>
                            <tbody  class="tbodays">
                            <?php if($count == 0){?>
                                <tr><td colspan="10">没有符合条件记录</td></tr>
                            <?php }else {  foreach ($dataList as $k => $row){?>
                                <tr >
                                    <td><div><?php echo $row['id'];?></div></td>
                                    <td><div><?php echo $row['product_code'];?></div></td>
                                    <td><div><?php echo $row['product_name'];?></div></td>
                                    <td><div>
                                            <?php
                                                if (!empty($proTypeList)) {
                                                    foreach($proTypeList as $key=>$val){
                                                        if($val['id'] == $row['product_type_id']){
                                                            echo $val['type_name'];
                                                        }
                                                    }
                                                }
                                                ?>
                                    </div></td>
                                    <td><div><?php echo $row['yield_rate_year'];?></div></td>
                                    <td><div><?php echo $row['fund_min_val'];?></div></td>
<!--                                    <td><div>--><?php //echo $guarantee_levels[$row['guarantee_level']];?><!--</div></td>-->
                                    <td><div><?php echo $row['upper_limit'];?></div></td>
                                    <td><div><?php echo $invest_issue_types[$row['invest_issue_type']];?></div></td>
<!--                                    <td><div>--><?php //echo $invest_start_types[$row['invest_start_type']];?><!--</div></td>-->
                                    <td><div><?php echo $invest_date_types[$row['invest_date_type']];?></div></td>
                                    <td><div><?php echo $row['invest_days'];?></div></td>
                                    <td><div><?php echo $row['invest_start_date'];?></div></td>
                                    <td><div><?php echo $row['invest_end_date'];?></div></td>
                                    <td><div><?php echo $row['earn_days'];?></div></td>
                                    <td><div><?php echo $row['earn_days_sign'];?></div></td>
                                    <td class="tr">
                                        <a title="删除" href="#" data-id="<?php echo $row['id'];?>"  class=" rowDelete pointer theme-color">删除</a> |
                                        <a title="修改" href="#" data-id="<?php echo $row['id'];?>"  class="pro_update pointer theme-color">修改</a>
                                    </td>
                                </tr>
                            <?php }}?>
                            </tbody>
                        </table>
                    </div>
                    <?php $this->renderPartial('//page/index',array('page'=>$page)); ?>
        </div>
    </div>
</div>



<script src="/upload/common-js/ff.product.js" type="text/javascript"></script>

<!--产品添加--START---->
<div class="modal hide" id="modal-add-event">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>产品新增</h3>
    </div>
    <div class="modal-body">
        <form id="pro_form">
        <div class="form-horizontal form-alert">
            <div class="control-group">
                <label class="control-label"><em class="red-star">*</em>产品名称 :</label>
                <div class="controls">
                    <input type="text" id="product_name" class="span3" name="product_name" placeholder="产品名称">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">产品类型 :</label>
                <div class="controls">
                    <select id="product_type_id">
                        <?php
                        if (!empty($proTypeList)) {
                            foreach($proTypeList as $key=>$val){
                                echo '<option value="'.$val['id'].'">'.$val['type_name'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">收益率 :</label>
                <div class="controls">
                    <input type="text" id="yield_rate_year" placeholder="收益率">（<em class="red-star">*例如：8.7% 填 8.7</em>）
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">起投资金 :</label>
                <div class="controls">
                    <input type="text" id="fund_min_val" placeholder="起投资金">元
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">保障方式 :</label>
                <div class="controls">
                    <select id="guarantee_level">
                        <?php
                        foreach($guarantee_levels as $k=>$v){
                            ?>
                            <option value="<?php echo $k?>"><?php echo $v?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">项目总金额 :</label>
                <div class="controls">
                    <input type="text" id="upper_limit" placeholder="项目总金额">元
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">利息分配 :</label>
                <div class="controls">
                    <select id="invest_issue_type">
                        <?php
                        foreach($invest_issue_types as $k=>$v){
                            ?>
                            <option value="<?php echo $k?>"><?php echo $v?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">起息日类别 :</label>
                <div class="controls">
                    <select id="invest_start_type">
                        <?php
                        foreach($invest_start_types as $k=>$v){
                        ?>
                            <option value="<?php echo $k?>"><?php echo $v?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="control-group" id="div_tN_type">
                <label class="control-label">T+N类别 :</label>
                <div class="controls">
                    <select id="invest_date_type">
                        <?php
                        foreach($invest_date_types as $k=>$v){
                            ?>
                            <option value="<?php echo $k?>"><?php echo $v?></option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="control-group" id="div_tN_days">
                <label class="control-label">T+N天数 :</label>
                <div class="controls">
                    <em style="text-align: left">T+</em><input id="invest_days" type="text" placeholder="天数">
                </div>
            </div>
            <div class="control-group" id="div_tEND_days">
                <label class="control-label">投资期限 :</label>
                <div class="controls">
                    <input id="earn_days" type="text" class="span1" placeholder="天数">
                </div>
            </div>
            <div class="control-group" style="display: none" id="div_invest_start_date">
                <label class="control-label">固定日期开始 :</label>
                <div class="controls">
                    <input type="text" id="invest_start_date" name="invest_start_date"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                </div>
            </div>
            <div class="control-group" style="display: none" id="div_invest_end_date">
                <label class="control-label">固定日期结束 :</label>
                <div class="controls">
                    <input type="text" id="invest_end_date" name="invest_end_date"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                </div>
            </div>
        </div>
        </form>
    </div>

    <div class="modal-footer modal_operate">
        <button type="button" class="btn btn-primary btn_add">添加</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>
<!--产品添加--END---->


<!--产品修改--START---->
<div class="modal hide" id="modal-update-event">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>产品修改</h3>
    </div>
    <div class="modal-body">
        <form id="pro_update_form">

            <div class="form-horizontal form-alert">
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>产品名称 :</label>
                    <div class="controls">
                        <input type="text" id="update_product_name" class="span3" name="product_name" placeholder="产品名称">
                        <input type="hidden" id="id" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">产品类型 :</label>
                    <div class="controls">
                        <select id="update_product_type_id">
                            <?php
                            if (!empty($proTypeList)) {
                                foreach($proTypeList as $key=>$val){
                                    echo '<option value="'.$val['id'].'">'.$val['type_name'].'</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">收益率 :</label>
                    <div class="controls">
                        <input type="text" id="update_yield_rate_year" placeholder="收益率">（<em class="red-star">*例如：8.7% 填 8.7</em>）
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">起投资金 :</label>
                    <div class="controls">
                        <input type="text" id="update_fund_min_val" placeholder="起投资金">元
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">保障方式 :</label>
                    <div class="controls">
                        <select id="update_guarantee_level">
                            <?php
                            foreach($guarantee_levels as $k=>$v){
                                ?>
                                <option value="<?php echo $k?>"><?php echo $v?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">项目总金额 :</label>
                    <div class="controls">
                        <input type="text" id="update_upper_limit" placeholder="项目总金额">元
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">利息分配 :</label>
                    <div class="controls">
                        <select id="update_invest_issue_type">
                            <?php
                            foreach($invest_issue_types as $k=>$v){
                                ?>
                                <option value="<?php echo $k?>"><?php echo $v?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">起息日类别 :</label>
                    <div class="controls">
                        <select id="update_invest_start_type">
                            <?php
                            foreach($invest_start_types as $k=>$v){
                                ?>
                                <option value="<?php echo $k?>"><?php echo $v?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="control-group" id="update_div_tN_type">
                    <label class="control-label">T+N类别 :</label>
                    <div class="controls">
                        <select id="update_invest_date_type">
                            <?php
                            foreach($invest_date_types as $k=>$v){
                                ?>
                                <option value="<?php echo $k?>"><?php echo $v?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>

                <div class="control-group" id="update_div_tN_days">
                    <label class="control-label">T+N天数 :</label>
                    <div class="controls">
                        <em style="text-align: left">T+</em><input id="update_invest_days" type="text" placeholder="天数">
                    </div>
                </div>
                <div class="control-group" id="update_div_tEND_days">
                    <label class="control-label">投资期限 :</label>
                    <div class="controls">
                        <input id="update_earn_days" type="text" class="span1" placeholder="天数">
                    </div>
                </div>
                <div class="control-group" style="display: none" id="update_div_invest_start_date">
                    <label class="control-label">固定日期开始 :</label>
                    <div class="controls">
                        <input type="text" id="update_invest_start_date" name="invest_start_date"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                    </div>

                </div>
                <div class="control-group" style="display: none" id="update_div_invest_end_date">
                    <label class="control-label">固定日期结束 :</label>
                    <div class="controls">
                        <input type="text" id="update_invest_end_date" name="invest_end_date"  onFocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',realDateFmt:'yyyy-MM-dd'})"/>
                    </div>

                </div>
                <div class="control-group">
                    <label class="control-label">投资期限标识 :</label>
                    <div class="controls">
                        <input type="text" id="update_earn_days_sign" placeholder="投资期限标识">
                    </div>
                </div>


            </div>


        </form>
    </div>

    <div class="modal-footer modal_operate">
        <button type="button" class="btn_update btn btn-primary">保存</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>

    </div>
</div>
<!--产品修改--END---->