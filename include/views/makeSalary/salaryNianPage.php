
<script language="javascript"  type="text/javascript">
    $(function(){
        $('#myTab a').click(function (e) {
            e.preventDefault();//阻止a链接的跳转行为
            $(this).tab('show');//显示当前选中的链接及关联的content
        })
        $('#test').bind('input propertychange', function() {
            alert("aa");
            $('#content').html($(this).val().length + ' characters');
        });
    });
    function chanpinDownLoad(){
        $("#iform").attr("action","index.php?action=Admin&mode=fileProDownload");
        //$("#nfname").val($("#newfname").val());
        $("#iform").submit();
    }
    function b(){
        $("#iform").attr("action","index.php?action=Salary&mode=sumSalary");
        $("#iform").submit();
    }
    function nian_b(){
        $("#iform_nian").attr("action","index.php?action=Salary&mode=sumNianSalary");
        $("#iform_nian").submit();
    }
    function nian_er(){
        $("#iform_er").attr("action","index.php?action=Salary&mode=sumErSalary");
        $("#iform_er").submit();
    }
    function changeSum () {
        var val = $("#change").val();
        if (val == 1){
            $("#first").show(),$("#nian").hide(),$("#second").hide();
        } else if(val == 2) {
            $("#first").hide();$("#nian").show();$("#second").hide();
        } else if(val == 3) {
            $("#first").hide();$("#nian").hide();$("#second").show();
        }

    }
</script>
<link href='<?php echo FF_STATIC_BASE_URL;?>/css/custom.css' rel='stylesheet' type='text/css' />
<script src="<?php echo FF_STATIC_BASE_URL;?>/js//hot-js/handsontable.full.js"></script>
<link rel="stylesheet" media="screen" href="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.css">

<script src="<?php echo FF_STATIC_BASE_URL;?>/js/tags-input/bootstrap-tagsinput.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo FF_STATIC_BASE_URL;?>/js/tags-input/bootstrap-tagsinput.css">
<script src="<?php echo FF_STATIC_BASE_URL;?>/common-js/salary/zq.salaryNian.js?3"></script>
<script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script>
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
                                <option value="-1">选择单位</option>
                                <?php
                                foreach($custom_list as $val) {
                                    echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div style="width: 500px;margin-top: 20px;margin-left: 20px">
                            <div id="medium"></div>
                        </div>
                        <!--功能项-->
                        <div id="first" class="manage"
                             style="word-wrap: break-word; background-color: red;">

                            <input type="checkbox" class="add_focus" data-text="身份证选取"  data-val="shenfenzheng" autocomplete="off">选择身份证：<input type="text"id="shenfenzheng" class="shenfenText click_position"/>
                            <input type="checkbox" class="add_focus" data-text="相加项选取"  data-val="add" autocomplete="off">年终奖项：<input type="text" id="add" class="plusText click_position"/>
                            发年终奖月份（2012-01-01）：<input type="text" name="salaryTime_er" id="salaryTime_nian" onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM'})"/>
                            是否做过本月一次工资：
                            <select name="isFirst" id="isFirst" >
                                <option value="1">是</option>
                                <option value="0">否</option>
                            </select>
                            <input type="button" value="年终奖计算" data-type="1" class="sumNianSalary" /></font>
                            <font id="add_text" color="green"></font>
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
                                <div id="exampleGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span12" style="margin-left:0;">
                    <div class="widget-box">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active"><a href="#home">计算结果</a></li>
                            <li><a href="#profile">错误信息<em style="color: red" id="error"></em></a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="home">
                                <div class="controls">
                                    <!-- checked="checked"-->
                                    <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前两列</span>
                                    <input type="button" value="保存工资" class="btn btn-success" id="save" />
                                </div>
                                <div id="sumGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>
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
                    <input type="text" style="margin: 2px 10px;" id="companySearch" value=""/>
                    <div class="tips"><em style="color: red;padding-right: 10px;">*</em>工资月份：
                        <input type="text" id="salary_date" name="salary_date" value="<?php echo date("Y-m");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM'})"/>
                    </div>
                    <div class="tips"><em style="color: red;padding-right: 10px;">*</em>年度：
                        <input type="text" id="salary_year" name="salary_year" value="<?php echo date("Y");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy'})"/>
                    </div>
                    <div class="tips">备注：<textarea id="mark">

                        </textarea></div>
                </div>
            </div>

            <div class="modal-footer modal_operate">
                <input type ='hidden' value="" id="nianzhong_bit" />
                <button type="button" id="salarySave" class="btn btn-primary">保存</button>
                <a href="#" class="btn" data-dismiss="modal">取消</a>
            </div>
    </form>
    <script type="text/javascript">
        $(function (){

            var list = <?php echo json_encode($jsonList);?>;
            list = eval(list);
            $( "#companySearch" ).autocomplete({
                source: list
            });
            $( "#searchName" ).autocomplete({
                source: list
            });
        });
    </script>
    <div class="search_suggest" id="custor_search_suggest">
        <ul class="search_ul">

        </ul>
        <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
    </div>
</div>