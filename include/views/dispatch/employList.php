<div id="content-header">
    <div id="breadcrumb">
        <a href="index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="index.php?action=Company&mode=toDepartmentEdit">部门编辑</a>
    </div>
</div>
<div class="container-fluid">
    <div class="search-form">
        <div class="row-fluid1">
            <div class="widget-box">
                <div class="controls">
                    选择单位：<input type="text" style="margin: 2px 10px;" id="companySelect">
                    选择部门：<input type="text" style="margin: 2px 10px;" id="department">
                    合同号查询：<input type="text" style="margin: 2px 10px;" id="contract_no">
                    客服姓名：<input type="text" style="width:100px;" maxlength="20" id="op_user"name="op_user" autocomplete="off" />
                    姓名：<input type="text" style="width:100px;" maxlength="20" id="e_name"name="e_name" autocomplete="off" />
                    身份证：<input type="text" style="width:200px;" maxlength="20" id="e_num"name="e_num" autocomplete="off" />
                    <input type="hidden" value="" id="company_id" name="company_id"/>
                    <input type="submit" value="搜索" name="yt0" class="btn btn-success" id="search_by">
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
    <div class="tree_l">


        <div class="zTreeDemoBackground left">
            <ul id="treeDemo" class="ztree"></ul>
        </div>

    </div>
    <div class="search_r">
        <div class="span12" style="margin-left:0;">
            <div class="widget-box">
                <div class="tab-content">
                    <div class="control-group">
                        <label class="control-label"><em class="red-star">*</em>规则 :</label>
                        <div class="controls" id="rule_checkbox">
                            <?php foreach ($employInfo_header_property as $k=>$v) { ?>
                                <input type="checkbox" class="colCheck" checked value="<?php echo $v; ?>" head_val="<?php echo $k; ?>"><?php echo $k; ?>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="span12" style="margin-left:0;">
            <div class="widget-box">
                <div class="tab-content">
                    <div>
                        <div class="controls" style="background-color: #F5F5FF;margin-bottom: 10px;width:900px;border: 2px solid #4285c8;">
                            <!-- checked="checked"-->
                            <div>
                                <input type="button" style="display: none" value="社保增员" class="btn btn-rounded btn-info" id="socialAdd" >
                                <input type="button" style="display: none" value="公积金增员" class="btn btn-rounded btn-info" id="gjjinAdd" >
                                <input type="button" style="display: none" value="社保减员" class="btn btn-rounded btn-danger" id="socialSub" >
                                <input type="button" style="display: none" value="公积金减员" class="btn btn-rounded btn-danger" id="gjjinSub" >
                            </div>
                            <div>
                                <span>社保基数：</span><span id="social_val">0</span>
                                <span>公积金基数：</span><span id="gjjin_val">0</span>
                            </div>
                            <div>
                                <span>社保金增员时间：</span><span id="social_date"></span>
                                <span>公积金增员时间：</span><span id="gjjin_date"></span>
                            </div>
                        </div>
                        <div class="controls">
                            <input type="button" value="保存" class="btn btn-success" id="employSave" >
                            <input type="checkbox" id="colHeaders" autocomplete="off"> <span>锁定前<input style="width:20px;"  id='clo_w'value="2"/>列</span>
                            <input type="checkbox" id="rowHeaders" autocomplete="off"> <span>锁定前<input style="width:20px;" id='clo_h'value="2"/>行</span>
                        </div>
                        <div id="nowKucunGrid" class="dataTable" style="width:1100px;height: 500px; overflow: hidden"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link href='<?php echo FF_STATIC_BASE_URL;?>/css/custom.css' rel='stylesheet' type='text/css' />
    <link href='<?php echo FF_STATIC_BASE_URL;?>/js/ztree/zTreeStyle/zTreeStyle.css' rel='stylesheet' type='text/css' />
    <script src="<?php echo FF_STATIC_BASE_URL;?>/js//hot-js/handsontable.full.js"></script>
    <link rel="stylesheet" media="screen" href="<?php echo FF_STATIC_BASE_URL;?>/js/hot-js/handsontable.full.css">
    <script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/js/ztree/jquery.ztree.core-3.5.js"></script>
    <script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/common-js/zq.employList.js?145"></script>
    <script type="text/javascript" src="<?php echo FF_STATIC_BASE_URL;?>/common-js/zq.employList_left.js"></script>
</div>
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
                        <input type="text"  id="e_name_s"name="e_name_s"  placeholder="姓名" readonly/>
                        <input type="hidden" id="row_id" name="row_id" />
                        <input type="hidden" id="add_type" name="add_type" />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">身份证号 :</label>
                    <div class="controls">
                        <input type="text" id="e_num_s"name="e_num_s" readonly/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">身份类别 :</label>
                    <div class="controls">
                        <input type="text"  id="e_type_s"name="e_type_s"  readonly/>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">（社保/公积金）基数 :</label>
                    <div class="controls">
                        <input type="text"  id="social_base_s"name="social_base_s"  />
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label">增员月份 :</label>
                    <div class="controls">
                        <input type="text" style="width:90px;" id="add_date_s" name="start_date_s"  value="<?php echo date("Y-m");?>"  onFocus="WdatePicker({isShowClear:false,readOnly:true,'dateFmt':'yyyy-MM'})"/>
                    </div>
                </div>
                <div class="control-group date-rang">
                </div>
                <div class="control-group">
                    <label class="control-label">备注 :</label>
                    <div class="controls">
                        <textarea name="remark" id="remark" maxlength="140" form_type="textarea"></textarea>
                    </div>
                </div>
            </div>
<input type="hidden" id="e_num_search" value="">
        </div>
        <div class="modal-footer modal_operate">
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
</div>
<!--添加--END---->

<!--选择--START---->
<div class="modal hide" id="modal-add-event2">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3>选择</h3>
    </div>

    <form id="custom_form_add">
        <div class="modal-body">
            <div class="form-horizontal form-alert">
                <table border="1" borderColor="grey" cellpadding="5" width="98%" id="check">
                    <tr>
                        <td>公司名称</td>
                        <td>姓名</td>
                        <td>身份证</td>
                    </tr>
                </table>

            </div>

        </div>
        <div class="modal-footer modal_operate">
            <button type="button" id="search_save" class="btn btn-primary">确定</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
    </form>
</div>
<!--添加--END---->
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
        $( "#companySelect" ).autocomplete({
            source: list
        });
    });
</script>