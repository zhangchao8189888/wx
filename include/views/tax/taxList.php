<div id="content-header">
    <div id="breadcrumb">
        <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="index.php?action=Company&mode=toDepartmentEdit">查看个税</a>
    </div>
</div>
<div class="container-fluid">
    <div class="container-fluid"><span class="btn btn-primary" id="export">导出</span>&nbsp&nbsp&nbsp<span class="btn btn-primary" id="exportFirst">一次工资单位导出</span>&nbsp&nbsp&nbsp<span class="btn btn-primary" id="exportEr">二次工资单位导出</span>
            <div class="search-form">
                <div class="row-fluid1">
                    <div class="widget-box">
                        <div class="controls">
                            月份查询：<input type="text" id="sal_date" name="sal_date" value="<?php echo date("Y-m");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM'})"/>
                            单位名称：<input type="text" id="com_name" name="com_name" value="<?php echo $com;?>" />
                            <span class="btn btn-success" id="search_btn">查询</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        /* @var $this sortController */
        /* @var $model Sort */

        $this->breadcrumbs=array(
            '分类',
        );

        ?>
        <div class="span12" style="margin-left:0;">
            <div class="widget-box">
                <div class="tab-content">
                    <div class="tab-pane active" id="home">
                        <div class="controls">
                            <!-- checked="checked"-->
                            <form id="excelForm" method="post">
                                <input type="hidden" name="salaryId" id="salaryId" value=""/>
                            </form>
                            <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前两列</span>
                        </div>
                        <div id="exampleGrid" class="dataTable" style="width: 1000px; height: 400px; overflow: auto"></div>
                    </div>
                </div>
            </div>
        </div>
        <form id="salForm" action="" method="post">
            <input type="hidden" name="excel_data" id="excel_data" value="" />
            <input type="hidden" name="head" id="head" value="" />
            <input type="hidden" name="date" id="date" value="" />
        </form>
        <link href='<?php echo FF_STATIC_BASE_URL;?>/css/custom.css' rel='stylesheet' type='text/css' />
        <script src="<?php echo FF_STATIC_BASE_URL;?>/js//hot-js/handsontable.full.js"></script>
        <link rel="stylesheet" media="screen" href="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.css">
        <script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/common-js/financial/zq.taxList.js?11"></script>
        <script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/js/datepicker/WdatePicker.js"></script>
    </div>