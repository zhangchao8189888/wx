<script type="text/javascript">
    $(function(){
        $('#test').bind('input propertychange', function() {
            alert("aa");
            $('#content').html($(this).val().length + ' characters');
        });

        $("#submitBtn1").click(function () {
            if (1) {

                if($('file').val()=="") {alert("文件名不能为空");return;}
                $("#basic_validate").submit();
            } else {
                alert("选择单位");
            }
        });
    });
    function chanpinDownLoad(){
        $("#iform").attr("action","index.php?action=Employ&mode=getEmployTemlate");
        //$("#nfname").val($("#newfname").val());
        $("#iform").submit();
    }
</script>
<div id="content-header">
    <div id="breadcrumb">
        <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="#">员工导入</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
                    <h5>员工导入 </h5>
                </div>
                <?php if (!empty($error)) {?>
                    <div class="alert alert-error">
                        <button data-dismiss="alert" class="close">×</button>
                        <strong>导入失败!</strong> <?php echo $error;?> </div>
                <?php }?>
                <?php if (!empty($succ)) {?>
                    <div class="alert alert-success">
                        <button data-dismiss="alert" class="close">×</button>
                        <strong>导入成功</strong> <?php echo $succ;?> </div>
                <?php }?>
                <div class="widget-content nopadding">
                    <div class="form-actions">
                        <form id="iform" method="post">
                            <input type="button" value="下载员工导入模版" onclick="chanpinDownLoad()" class="btn btn-primary"/>
                        </form>

                    </div>
                    <form class="form-horizontal" method="post" action="<?php echo FF_DOMAIN;?>/dispatch/fileImport" enctype="multipart/form-data" name="basic_validate" id="basic_validate" novalidate="novalidate">
                        <div class="control-group" id="createError" style="display:none;">
                            <label class="control-label">&nbsp;</label>
                            <div class="controls">
                                <span class="colorRem"></span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label">导入文件：</label>
                            <div class="controls"><input type="hidden" name="max_file_size" value="10000000"/>
                                <input name="file"  type="file"/>　
                            </div>
                        </div>
                        <div class="form-actions">
                            <div class="tips"><em style="color: red;padding-right: 10px;">*</em>所属公司：<input type="text" maxlength="20" id="e_company"name="e_company" autocomplete="off" /><input type="hidden" value="" id="company_id" name="company_id"/></div> <input type="button" value="导入" class="btn btn-success" id="submitBtn1" >
                            <div class="search_suggest" id="custor_search_suggest">
                                <ul class="search_ul">

                                </ul>
                                <div class="extra-list-ctn"><a href="javascript:void(0);" id="quickChooseProduct" class="quick-add-link"><i class="ui-icon-choose"></i>选择客户</a></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>