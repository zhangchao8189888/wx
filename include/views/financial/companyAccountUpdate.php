<?php
/**
 * Created by PhpStorm.
 * User: zhangchao-rj
 * Date: 2018/8/28
 * Time: 下午3:04
 */

?>

<div id="content-header">
    <div id="breadcrumb">
        <a href="/index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="/product/" class="current">社保管理</a>
        <a href="/product/productList" class="current">社保列表</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-content nopadding">
                    <form id="salForm" action="" method="post">
                        <div class="form-horizontal form-alert">

                            <div class="control-group">
                                <!--<label class="control-label">单位查找：</label>
                                <div class="controls">
                                    <input type="text" id="companyList"/>
                                </div>
                                <div class="controls">
                                    <input type="hidden" id="produce_date" name="produce_date" value="2016" />
                                    <input type="button" value="查询" class="btn btn-primary" id="searchDeal" />-->
                                    <input type="button" value="批量修改" class="btn btn-primary" id="batchUpdate" />
                                </div>
                            </div>
                    </form>
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
                <div class="alert alert-error" style="display: none">

                </div>
                <div class="alert alert-success" style="display: none">
                </div>
                <div id="excelGrid" class="dataTable" style="width:1100px;height: 500px; overflow: hidden"></div>
            </div>
        </div>

    </div>
</div>
<!--添加--START---->
<div class="modal hide" id="modal-add-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>选择单位</h3>
    </div>
    <div class="modal-body">
公司查询：<input type="text" style="margin: 2px 10px;" id="companySearch"><a href="javascript:void();" class="btn btn-small btn-info" id="addCompanyName" style="margin-left: 10px;">添加</a>
        <input type="hidden" id="row" value=""/>
    </div>

</div>
<!--添加--END---->
<!--添加--START---->
<div class="modal hide" id="modal-import-event">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>批量导入</h3>
    </div>
    <div class="modal-body">
        <div class="control-group">
            <label class="control-label">导入文件：</label>
            <div class="controls"><input type="hidden" name="max_file_size" value="10000000"/>
                <input name="file"  type="file"/>　
                <input type="button" value="导入" class="btn btn-success" id="submitBtn1" >
            </div>
        </div>
    </div>

</div>
<!--添加--END---->
<script language="javascript" type="text/javascript">
    var companyList = <?php echo json_encode($companyList);?>;
var user = <?php echo json_encode($user);?>;
companyList = eval(companyList);
$( "#companySearch" ).autocomplete({
source: companyList
});
$( "#companyList" ).autocomplete({
source: companyList
});
</script>
<link href='<?php echo FF_STATIC_BASE_URL;?>/css/custom.css' rel='stylesheet' type='text/css' />
    <script src="<?php echo FF_STATIC_BASE_URL;?>/js//hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.css">
<script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/common-js/financial/zq.companyAccountUpdate.js?333"></script>
<script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/js/datepicker/WdatePicker.js"></script>
<script>
    function callback_fun(obj) {
        var option  = $(obj).attr("class");

        if (option) {

        }
    }
</script>