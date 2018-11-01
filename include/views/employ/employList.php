<div id="content-header">
    <div id="breadcrumb">
        <a href="/index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="/product/" class="current">员工管理</a>
        <a href="/product/productList" class="current">员工列表列表</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="controls">
                    单位查找：<select id="e_company_id">
                        <option value="0">选择单位</option>
                        <?php
                        foreach($companyList as $val) {
                            echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="controls">
                    姓名：<input type="text" style="width:100px;" maxlength="20" id="e_name"name="e_name" autocomplete="off" />
                    身份证：<input type="text" style="width:200px;" maxlength="20" id="e_num"name="e_num" autocomplete="off" />
                    <div style="margin-left: 5px;"><span id="search_btn" class="btn btn-success"/>查询</span></div>
                </div>
            </div>
            <div class="widget-box">
                <div class="controls">
                    <div style="float: right;margin-right: 5px;"><span id="import_btn" class="btn btn-success"/>导入</span></div>
                </div>
            </div>
            <div class="widget-box">
                <div class="controls">
                    <!-- checked="checked"-->
                    <!--<input type="button" value="保存" class="btn btn-success" id="produceSave" >-->
                    <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前<input style="width:20px;"  id='clo_w'value="2"/>列</span>
                    <input type="checkbox" id="rowHeaders" autocomplete="off"> <span>锁定前<input style="width:20px;" id='clo_h'value="2"/>行</span>
                    &nbsp;&nbsp;选中行数：<span style="color: #049cdb" id="p_num"></span>&nbsp;&nbsp;选中合计：<span id="p_sum" style="color: #049cdb"></span>
                </div>
                <div id="excelGrid" class="dataTable" style="width:1000px;height: 500px; overflow: hidden"></div>
            </div>
        </div>

    </div>
</div>
<script>
</script>
<link href='<?php echo FF_STATIC_BASE_URL;?>/css/custom.css' rel='stylesheet' type='text/css' />
<script src="<?php echo FF_STATIC_BASE_URL;?>/js//hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.css">
<script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/common-js/employ/zq.employ.js?1"></script>
<script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/js/datepicker/WdatePicker.js"></script>
<!--添加--START---->
<div class="modal hide" id="modal-add-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>添加</h3>
    </div>

    <form id="social_form_add">
        <div class="modal-body">
            <div class="form-horizontal form-alert">
                <div class="control-group">
                    <label class="control-label"><em class="red-star">*</em>姓名 :</label>
                    <div class="controls">
                        <input type="text"  id="employ_name"name="employ_name" />
                        <input type="hidden" id="row_id" name="row_id" />
                        <input type="hidden" id="add_type" name="add_type" />
                    </div>
                </div>
                <!--   	'员工姓名	','身份证号','户口性质','开户行','银行卡号','社保基数','公积金基数','劳务费','残保金','档案费','备注'-->
                <div class="control-group">
                    <label class="control-label">身份证号 :</label>
                    <div class="controls">
                        <input type="text" id="employ_num"name="employ_num" readonly/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">身份类别 :</label>
                    <div class="controls">

                        <select id="e_type" name="e_type">
                            <?php $e_types = FConfig::item('config.employ_type');
                                  foreach ($e_types as $k => $e_type) {
                                      echo '<option value="'.$k.'">'.$e_type.'</option>';
                                  }

                            ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"> 开户行 :</label>
                    <div class="controls">
                        <input type="text"  id="bank_name"name="bank_name"  />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"> 银行卡号 :</label>
                    <div class="controls">
                        <input type="text"  id="bank_num"name="bank_num"  />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"> 公积金基数 :</label>
                    <div class="controls">
                        <input type="text"  id="gongjijinjishu"name="gongjijinjishu"  />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"> 社保基数 :</label>
                    <div class="controls">
                        <input type="text"  id="shebaojishu"name="shebaojishu"  />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"> 劳务费 :</label>
                    <div class="controls">
                        <input type="text"  id="laowufei"name="laowufei"  />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"> 残保金 :</label>
                    <div class="controls">
                        <input type="text"  id="canbaojin"name="canbaojin"  />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"> 档案费 :</label>
                    <div class="controls">
                        <input type="text"  id="danganfei"name="danganfei"  />
                    </div>
                </div>
                <div class="control-group date-rang">
                </div>
                <div class="control-group">
                    <label class="control-label">备注 :</label>
                    <div class="controls">
                        <textarea name="memo" id="memo" maxlength="140" form_type="textarea"></textarea>
                    </div>
                </div>
            </div>
            <input type="hidden" id="e_num_search" value="">
        </div>
        <div class="modal-footer modal_operate">
            <button type="button" class="btn add_btn btn-primary">保存</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
</div>
<!--添加--END---->
