<link href='<?php echo FF_STATIC_BASE_URL;?>/css/custom.css' rel='stylesheet' type='text/css' />
<script src="<?php echo FF_STATIC_BASE_URL;?>/js//hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.css">

<script src="<?php echo FF_STATIC_BASE_URL;?>/js/tags-input/bootstrap-tagsinput.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo FF_STATIC_BASE_URL;?>/js/tags-input/bootstrap-tagsinput.css">
<script src="<?php echo FF_STATIC_BASE_URL;?>/common-js/employ/zq.baseNumUpdate.js?1"></script>
<div id="content-header">
    <div id="breadcrumb">
        <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="index.php?action=Product&mode=productUpload">工资管理</a>
        <a href="#" class="current">基数批量修改  </a>
    </div>
</div>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <form enctype="multipart/form-data" id="iform" action="" method="post">
                    <div class="manage">
                        <div class="controls">

                        </div>
                        <div style="width: 500px;margin-top: 20px;margin-left: 20px">
                            <div id="medium"></div>
                        </div>
                        <!--功能项-->
                        <div id="first" class="manage"
                             style="word-wrap: break-word;display: block;">
                            <input type="button" value="批量修改" id="employSave" class="btn btn-success"/></font>
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
                                <div id="sumGrid" class="dataTable" style="width: 1400px; height: 300px; overflow: auto"></div>
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
