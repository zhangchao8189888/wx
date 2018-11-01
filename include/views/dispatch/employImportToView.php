<div id="content-header">
    <div id="breadcrumb">
        <a href="/index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="/product/" class="current">派遣管理</a>
        <a href="/product/productList" class="current">花名册导入结果</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <!--<div class="controls">（需要粘贴员工excel表头）
                    <span>身份证号<input style="width:20px;" class="click_position" id='e_num' value="0"/>列</span>
                    <span>姓名<input style="width:20px;" class="click_position" id='e_name'value="0"/>列</span>
                    <span>合同号<input style="width:20px;" class="click_position" id='e_hetong_num'value="0"/>列</span>
                    <span>身份类别<input style="width:20px;" class="click_position" id='e_type'value="0"/>列</span>
                </div>
                <div class="controls">
                    <select id="custom_id">
                        <option value="-1">选择单位</option>
                        <?php
/*                            foreach($custom_list as $val) {
                                echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
                            }
                        */?>
                    </select>
                </div>-->
                <div class="controls">
                    <span id="excel_open" class="btn btn-success"/>导入excel</span>
                </div>
            </div>
            <div class="widget-box">
                <div class="controls">
                    <div style="float: right;margin-right: 5px"><span id="add_btn" class="btn btn-success"/>保存</span></div>
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
                <div id="excelGrid" class="dataTable" style="width:1000px;height: 500px; overflow: hidden"></div>
            </div>
        </div>
        <div class="span12" style="margin-left:0;">
            <div class="widget-box">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a href="#home">保存结果<em style="color: red" id="success"></em></a></li>
                    <li ><a href="#profile">错误信息<em style="color: red" id="error"></em></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="home">
                        <!--<div class="controls">
                            <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前两列</span>
                            <input type="button" value="保存工资" class="btn btn-success" id="save" />
                        </div>
                        <div id="sumGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>-->
                    </div>
                    <div class="tab-pane" id="profile">
                        <table id="errorInfo">

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link href='<?php echo FF_STATIC_BASE_URL;?>/css/custom.css' rel='stylesheet' type='text/css' />
<script src="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.css">
<script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/common-js/zq.excel_view.js?1"></script>
<script src="<?php echo FF_STATIC_BASE_URL;?>/js/ajaxfileupload.js?1"></script>
<div class="modal hide" id="modal-add-event-product">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>excel文件</h3>
    </div>
    <div class="modal-footer modal_operate">
        <a href="javascript:;" class="btn" style="position: relative;">上传<input type="file" id="upFile"  name="file" /> </a>
        <button type="btn" id="btn"   class="btn btn-primary btn_add">读取文件</button>
        <a href="#" class="btn" data-dismiss="modal">取消</a>
    </div>
</div>

<script>
    $(function(){
        $('#myTab a').click(function (e) {
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
        });
    });

</script>