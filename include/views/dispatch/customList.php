<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/4/15
 * Time: 18:08
 */

$typeList=$data['typeList'];
?>

<div id="content-header">
    <div id="breadcrumb">
        <a href="/index.php" title="返回首页" class="tip-bottom"><i class="icon-home"></i>首页</a>
        <a href="/product/" class="current">派遣管理</a>
        <a href="/product/productList" class="current">派遣单位</a>
    </div>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span12">
            <div class="widget-box">
                <div class="controls">
                    <form method="get" action="dispatch/customerList">
                    选择单位：<input type="text" name="customer_name" style="margin: 2px 10px;"/>
                    <input type="submit" value="搜索" name="yt0" class="btn btn-success" id="search_by"/>
                    </form>
                    <div style="float: right;margin-right: 5px"><a href="#" id="add_btn" class="btn btn-success"/>新增</a></div>
                </div>
            </div>
            <div class="widget-box">
                <div class="widget-content nopadding">
                    <div class="dataTables_length">
                                <span class="pull-right">
                                <span class="badge badge-warning"><?php echo $count; ?></span>&nbsp;
                                </span>
                    </div>
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th class="tl" width="4%"><div></div></th>
                            <th class="tl"><div>单位名称</div></th>
                            <th class="tl"><div>负责人</div></th>
                            <th class="tl"><div>联系电话</div></th>
                            <th class="tl"><div>当前派遣期限</div></th>
                            <th class="tl"><div>残保金</div></th>
                            <th class="tl"><div>劳务费</div></th>
                            <th class="tl"><div>操作</div></th>
                        </tr>
                        </thead>
                        <tbody  class="tbodays">
                        <input type="hidden" value="" name="type" id="type" />
                        <?php if($count == 0){?>
                            <tr><td colspan="7">没有符合条件记录</td></tr>
                        <?php }else {  foreach ($customList as $k => $row){
                            ?>
                            <tr>
                                <td class="tl" width="4%"><div></div></td>
                                <td><div><?php echo $row->customer_name;?></div></td>
                                <td><div><?php echo $row->customer_principal;?></div></td>
                                <td><div><?php echo $row->customer_principal_phone;?></div></td>
                                <td><div><?php
                                        $dateArr = json_decode($row->date_rang_json);
                                        echo $dateArr[count($dateArr)-1]?></div></td>

                                <td><div><?php echo $row->canbaojin;?>元</div></td>
                                <td><div><?php echo $row->service_fee;?>元</div></td>
                                <td>
                                    <a title="编辑" style="cursor: pointer" class="edit_btn" data-id="<?php echo $row->id;?>" >编辑</a>|
                                    <a title="查看" style="cursor: pointer"  class="check_btn" data-id="<?php echo $row->id;?>" >查看</a>
                                </td>
                            </tr>
                        <?php }}?>
                        </tbody>
                    </table>
                </div>
                <?php $this->renderPartial('//page/index',array('page'=>$page)); ?>
            </div>
        </div>
        <div class="span12" style="margin-left:0;">
            <div class="widget-box">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a href="#home">详细信息</a></li>
                </ul>

                <div class="tab-content cus_detail" style="display: none">
                    <div class="tab-pane active" id="home">
                        <div class="form-horizontal form-alert">
                            <div class="control-group">
                                <label class="control-label"><em class="red-star">*</em>企业名称 :</label>
                                <div class="controls" id="customer_name_text"></div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">联系人 :</label>
                                <div class="controls" id="customer_principal_text"></div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">联系方式 :</label>
                                <div class="controls" id="customer_principal_phone_text"></div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">公司地址 :</label>
                                <div class="controls" id="customer_address_text"></div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">残保金 :</label>
                                <div class="controls" id="canbaojin_text"></div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">劳务费 :</label>
                                <div class="controls" id="service_fee_text"></div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">派遣期限 :</label>

                                <div class="controls" id="date_rang_text"></div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">备注 :</label>
                                <div class="controls" id="remark_text"></div>
                            </div>
                            <a title="隐藏"  id="display" class="btn btn-success">隐藏</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
    <!--添加--START---->
    <div class="modal hide" id="modal-add-event">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>添加</h3>
        </div>

        <form id="custom_form_add">
        <div class="modal-body">
                <div class="form-horizontal form-alert">
                    <div class="control-group">
                        <label class="control-label"><em class="red-star">*</em>企业名称 :</label>
                        <div class="controls">
                            <input type="text"  id="customer_name"name="customer_name"  placeholder="企业名称"/>
                            <input type="hidden" id="cid" name="cid" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">客户经理 :</label>
                        <div class="controls">
                            <select id="op_id">
                                <option value="0">当前用户</option>
                                <?php
                                foreach ($adminList as $admin) {//
                                    echo "<option value='{$admin['id']}'>{$admin['name']} </option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">联系人 :</label>
                        <div class="controls">
                            <input type="text" id="customer_principal"name="customer_principal"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">联系方式 :</label>
                        <div class="controls">
                            <input type="text"  id="customer_principal_phone"name="customer_principal_phone"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">公司地址 :</label>
                        <div class="controls">
                            <input type="text"  id="customer_address"name="customer_address"  />
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">残保金 :</label>
                        <div class="controls">
                            <input type="text"  class="span1" id="canbaojin"name="canbaojin"  />元
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">劳务费 :</label>
                        <div class="controls">
                            <input type="text"  class="span1" id="service_fee"name="service_fee"  />元
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">派遣期限 :</label>

                        <div class="controls">
                            <input type="text" style="width:90px;" id="start_date" name="start_date" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,maxDate:'#F{$dp.$D(\'end_date\')}'})"/>
                            <input type="text" style="width:90px;" id="end_date" name="end_date" value=""  onFocus="WdatePicker({isShowClear:false,readOnly:true,minDate:'#F{$dp.$D(\'start_date\')}'})"/>
                            <a style="cursor:pointer" class="date_rang_add">添加</a>
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

        </div>
        <div class="modal-footer modal_operate">
            <button type="submit" class="btn btn-primary">保存</button>
            <a href="#" class="btn" data-dismiss="modal">取消</a>
        </div>
        </form>
    </div>
    <!--添加--END---->

    <script type="text/javascript">
        $(function (){

            var BaseUrl = "<?php echo FF_DOMAIN;?>";
        });
    </script>
    <script language="javascript" type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/common-js/zq.customList.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo FF_DOMAIN;?>/upload/js/datepicker/WdatePicker.js"></script>