<?php
/* @var $this JController */
$this->pageTitle = '前台用户管理';
?>
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
        <a href="/" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a> <a href="#" class="current">派遣单位</a> <a href="#" class="current">文件列表</a>
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
                        <strong>操作失败!</strong> <?php echo $error;?> </div>
                <?php }?>
                <?php if (!empty($success)) {?>
                    <div class="alert alert-success">
                        <button data-dismiss="alert" class="close">×</button>
                        <strong>操作成功</strong> <?php echo $success;?> </div>
                <?php }?>
                <div class="widget-content nopadding">
                    <form class="form-horizontal" method="post" action="<?php echo FF_DOMAIN;?>/dispatch/sysFileUpload" enctype="multipart/form-data" name="basic_validate" id="basic_validate" novalidate="novalidate">
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
                                <input type="button" value="导入" class="btn btn-success" id="submitBtn1" >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="widget-title"><span class="icon"><i class="icon-th"></i></span><h5>文件上传列表</h5>
                </div>
                <div class="widget-content nopadding">
                    <div class="dataTables_length">
            <span class="pull-right"> 
            <span class="badge badge-warning"><?php echo $count; ?></span>&nbsp;
            </span>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>文件名</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(count($files) == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else{ ?>
                            <?php foreach($files as $k =>$v){ ?>
                                <tr>
                                    <td><?php echo $v;?></td>
                                    <td>
                                        <a href="<?php echo FF_DOMAIN;?>/dispatch/fileDown?fName=<?php echo $v;?>" class="">下载<?php echo $user_status?></a>|
                                        <a href="<?php echo FF_DOMAIN;?>/dispatch/fileDel?fName=<?php echo $v;?>" class="">删除<?php echo $user_status?></a></td>
                                </tr>
                            <?php }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php $this->renderPartial('//page/index',array('page'=>$page)); ?>
        </div>
    </div>
</div>
<script src="/upload/common-js/ff.user.js" type="text/javascript"></script>
