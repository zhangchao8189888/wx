<link href='<?php echo FF_STATIC_BASE_URL;?>/css/custom.css' rel='stylesheet' type='text/css' />
<script src="<?php echo FF_STATIC_BASE_URL;?>/js//hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.css">

<script src="<?php echo FF_STATIC_BASE_URL;?>/js/tags-input/bootstrap-tagsinput.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo FF_STATIC_BASE_URL;?>/js/tags-input/bootstrap-tagsinput.css">
<script src="<?php echo FF_STATIC_BASE_URL;?>/common-js/employ/zq.employImport.js"></script>
<div id="content-header">
    <div id="breadcrumb">
        <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="index.php?action=Product&mode=productUpload">工资管理</a>
        <a href="#" class="current">查看导入文件  </a>
    </div>
</div>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <form enctype="multipart/form-data" id="iform" action="" method="post">
                    <div class="manage">
                        <div class="controls">
                            <select id="e_company_id">
                                <option value="0">选择单位</option>
                                <?php
                                foreach($companyList as $val) {
                                    echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
                                }
                                ?>
                            </select>

                            <input type="button" value="用上个月" name="yt0" class="btn btn-success" id="use_last_month">
                        </div>
                        <div style="width: 500px;margin-top: 20px;margin-left: 20px">
                            <div id="medium"></div>
                        </div>
                        <!--功能项-->
                        <div id="first" class="manage"
                             style="word-wrap: break-word;display: block;">
                            <input type="button" value="导入" id="employSave" class="btn btn-success"/></font>
                            <input id="focus_id" type="hidden" value=""/>

                        </div>
                    </div>
                </form>
                <div class="span12" style="margin-left:0;">
                    <div class="widget-box">
                        <div class="tab-content">
                            <div>
                                <div class="controls">
                                    <!-- checked="checked"-->
                                    <input type="button" value="重置" class="btn btn-primary" id="reload" />
                                </div>
                                <div id="sumGrid" class="dataTable" style="width: 1400px; height: 200px; overflow: auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span12" style="margin-left:0;">
                    <div class="widget-box">
                        <ul class="nav nav-tabs" id="myTab">
                            <li><a href="#home">成功信息<em style="color: red" id="success"></em></a></li>
                            <li><a href="#profile">错误信息<em style="color: red" id="error"></em></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home">
                                <table id="successInfo">
                                    <tr><td>1</td><td>1</td><td>1</td></tr>

                                </table>
                            </div>
                            <div class="tab-pane" id="profile">
                                <table id="errorInfo">
                                    <tr><td>2</td><td>2</td><td>2</td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal hide" id="modal-event1">

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>保存工资</h3>
    </div>
    <form action="" id="company_validate" method="post" class="form-horizontal"  novalidate="novalidate">
        <div class="modal-body">
            <div class="designer_win">
                <div class="tips"><em style="color: red;padding-right: 10px;">*</em>所属公司：
                    <select id="company_id">
                        <?php foreach($company as $v){?>
                            <option value="<?php echo $v->id;?>"><?php echo $v->customer_name;?></option>
                        <?php }?>
                    </select>
                    <div class="tips"><em style="color: red;padding-right: 10px;">*</em>工资月份：
                        <input type="text" id="salary_date" name="salary_date" value="<?php echo date("Y-m");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM'})"/>
                    </div>
                    <div class="tips">备注：<textarea id="mark">

                        </textarea></div>
                </div>
            </div>

            <div class="modal-footer modal_operate">
                <button type="button" id="salarySave" class="btn btn-primary">保存</button>
                <a href="#" class="btn" data-dismiss="modal">取消</a>
            </div>
    </form>
    <div class="search_suggest" id="custor_search_suggest">
        <ul class="search_ul">

        </ul>
        <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
    </div>
</div>