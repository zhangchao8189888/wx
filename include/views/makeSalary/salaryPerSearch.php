<div id="content-header">
    <div id="breadcrumb">
        <a href="/index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="/product/" class="current">工资管理</a>
        <a href="/product/productList" class="current">工资列表查询</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <!--<div class="controls">
                    单位查找：<select id="e_company_id">
                        <option value="0">选择单位</option>
                        <?php
/*                        foreach($custom_list as $val) {
                            echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
                        }
                        */?>
                    </select>
                </div>-->
                <div class="controls">
                    姓名：<input type="text" style="width:100px;" maxlength="20" id="e_name"name="e_name" autocomplete="off" />
                    身份证：<input type="text" style="width:200px;" maxlength="20" id="e_num"name="e_num" autocomplete="off" /><br/>
                    <span class="label" style="margin-right: 20px;">起始月份查询</span><input type="text" id="start_date" class="Wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM'})" value="<?php echo $start_date;?>">
                    <span class="label" style="margin-right: 20px;">结束月份查询</span><input type="text" id="end_date" class="Wdate" onfocus="WdatePicker({dateFmt: 'yyyy-MM'})" value="<?php echo $end_date;?>">
                    <div style="margin-left: 5px;"><span id="search_btn" class="btn btn-success"/>查询</span></div>
                </div>
            </div>
            <div class="widget-box">
                <div class="controls">
                    <!-- checked="checked"-->
                    一次工资
                    <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前<input style="width:20px;"  id='clo_w'value="2"/>列</span>
                    <input type="checkbox" id="rowHeaders" autocomplete="off"> <span>锁定前<input style="width:20px;" id='clo_h'value="2"/>行</span>
                    &nbsp;&nbsp;选中行数：<span style="color: #049cdb" id="p_num"></span>&nbsp;&nbsp;选中合计：<span id="p_sum" style="color: #049cdb"></span>
                </div>
                <div id="firstGrid" class="dataTable" style="width:1000px;height: 200px; overflow: hidden"></div>
            </div>
            <div class="widget-box">
                <div class="controls">
                    <!-- checked="checked"-->
                    二次工资
                </div>
                <div id="erGrid" class="dataTable" style="width:1000px;height: 200px; overflow: hidden"></div>
            </div>
            <div class="widget-box">
                <div class="controls">
                    年终奖
                </div>
                <div id="nianGrid" class="dataTable" style="width:1000px;height: 200px; overflow: hidden"></div>
            </div>
        </div>

    </div>
</div>
<!--选择--START---->
<div class="modal hide" id="modal-add-event2">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>选择</h3>
    </div>

    <form id="custom_form_add">
        <div class="modal-body">
            <div class="form-horizontal form-alert">
                <table border="1" borderColor="grey" cellpadding="5" width="98%" id="check">
                    <tr>
                        <td>公司名称</td>
                        <td>姓名</td>
                        <td>身份证</td>
                    </tr>
                </table>

            </div>

        </div>
        <div class="modal-footer modal_operate">
            <button type="button" id="search_save" class="btn btn-primary">确定</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
</div>
<!--添加--END---->

<link href='<?php echo FF_STATIC_BASE_URL;?>/css/custom.css' rel='stylesheet' type='text/css' />
<script src="<?php echo FF_STATIC_BASE_URL;?>/js//hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.css">
<script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/common-js/salary/zq.salaryPerSearch.js"></script>
<script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/js/datepicker/WdatePicker.js"></script>
