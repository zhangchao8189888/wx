<div id="content-header">
    <div id="breadcrumb">
        <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="index.php?action=Company&mode=toDepartmentEdit">查看工资</a>
    </div>
</div>
<div class="container-fluid">
    <div class="container-fluid"><span class="btn btn-primary" id="export">导出</span>
<!--    <div class="search-form">-->
<!--        <div class="row-fluid1">-->
<!--            <div class="widget-box">-->
<!--                <div class="controls">-->
<!--                    <select id="e_company_id">-->
<!--                        <option value="-1">选择单位</option>-->
<!--<!--                        --><?php
////                        foreach($custom_list as $val) {
////                            echo '<option value="'.$val['id'].'">'.$val['name'].'</option>';
////                        }
////                        ?>
<!--                    </select>-->
<!--                    姓名：<input type="text" style="width:100px;" maxlength="20" id="e_name"name="e_name" autocomplete="off" />-->
<!--                    身份证：<input type="text" style="width:200px;" maxlength="20" id="e_num"name="e_num" autocomplete="off" />-->
<!--                    <input type="hidden" value="" id="company_id" name="company_id"/>-->
<!--                    <input type="submit" value="搜索" name="yt0" class="btn btn-success" id="search_by">-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
    <?php
    /* @var $this sortController */
    /* @var $model Sort */

    $this->breadcrumbs=array(
        '分类',
    );

    ?>
    <div class="tree_l">


        <div class="zTreeDemoBackground left">
            <ul id="treeDemo" class="ztree"></ul>
        </div>

    </div>
    <div class="search_r">

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
    </div>
        <form id="salForm" action="" method="post">
            <input type="hidden" name="excel_data" id="excel_data" value="" />
            <input type="hidden" name="head" id="head" value="" />
        </form>
    <script>
        var head = <?php echo json_encode($header);?>;
        var content = <?php echo json_encode($content,true);?>;
    </script>
    <link href='<?php echo FF_STATIC_BASE_URL;?>/css/custom.css' rel='stylesheet' type='text/css' />
    <script src="<?php echo FF_STATIC_BASE_URL;?>/js//hot-js/handsontable.full.js"></script>
    <link rel="stylesheet" media="screen" href="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.css">
    <script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/common-js/salary/salaryView.js"></script>
</div>