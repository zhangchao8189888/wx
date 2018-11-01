/**
 * Created by zhangchao8189888 on 16-3-28.
 */

$(function(){
    var  setting = {
        view: {
            dblClickExpand: false,
            showLine: true
        },
        async: {
            enable: true,
            url:GLOBAL_CF.DOMAIN+"/dispatch/getDepartmentTreeJson",
            autoParam:["id", "name=n", "level=lv"],
            dataType:'json',
            otherParam:{company_id:$("#company_id").val()},
            dataFilter: filter,
            type: "post"
        },
        callback: {
            beforeAsync: beforeAsync,
            onAsyncSuccess: onAsyncSuccess,
            onAsyncError: onAsyncError,
            //onRightClick: OnRightClick,
            onClick: onClick
        }
    };
    //initMyZtree();
    $.fn.zTree.init($("#treeDemo"), setting);
    zTree = $.fn.zTree.getZTreeObj("treeDemo");
    $('.close').click(function(){
        $('.modal-backdrop').hide();
        $('.modal').hide();
        $('#modal_del').hide();
    });

    /*搜索*/
    $('#classify').click(function(){
        var searchTxt = $(this).prev().val();
        var data = {
            name:searchTxt
        }
        if(searchTxt == ''){return false;}
        $.ajax({
            type:'POST',
            url:'./backend.php?r=sort/SearchSort&name',
            data: data,
            dataType:'Json',
            success:function(result){
                var html = '';
                $('.search_list tbody').html('');
                if(result.data == ''){
                    html = '<tr class="odd"><td style="color:red">搜索结果为空</td></tr>'
                    $('.search_list tbody').append(html);
                    return false;
                }
                $.each(result.data, function(i,item ){
                    var regExp = new RegExp(searchTxt,'g');
                    var newName = item.name.replace(regExp,'<span style="color:red">'+searchTxt+'</span>');

                    html = '<tr class="odd"><td>' + newName+'</td></tr>'
                    $('.search_list tbody').append(html);
                });

            }
        })
    });
    function filter(treeId, parentNode, childNodes) {
        if (!childNodes) return null;
        for (var i=0, l=childNodes.length; i<l; i++) {
            childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
        }
        return childNodes;
    }

    function beforeAsync() {
        curAsyncCount++;
    }
    var firstAsyncSuccessFlag = 0;
    function onAsyncSuccess(event, treeId, treeNode, msg) {
        if (firstAsyncSuccessFlag == 0) {
            try {
                var selectedNode = zTree.getSelectedNodes();
                var nodes = zTree.getNodes();
                zTree.expandNode(nodes[0], true);
                var childNodes = zTree.transformToArray(nodes[0]);
                zTree.expandNode(childNodes[1], true);
                zTree.selectNode(childNodes[1]);
                var childNodes1 = zTree.transformToArray(childNodes[1]);
                zTree.checkNode(childNodes1[1], true, true);
                firstAsyncSuccessFlag = 1;
            } catch (err) {

            }
        }
        curAsyncCount--;
        if (curStatus == "expand") {
            expandNodes(treeNode.children);
        } else if (curStatus == "async") {
            asyncNodes(treeNode.children);
        }

        if (curAsyncCount <= 0) {
            if (curStatus != "init" && curStatus != "") {
                $("#demoMsg").text((curStatus == "expand") ? demoMsg.expandAllOver : demoMsg.asyncAllOver);
                asyncForAll = true;
            }
            curStatus = "";
        }
    }

    function onAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown) {
        curAsyncCount--;

        if (curAsyncCount <= 0) {
            curStatus = "";
            if (treeNode!=null) asyncForAll = true;
        }
    }

    var curStatus = "init", curAsyncCount = 0, asyncForAll = false,
        goAsync = false;

    function expandNodes(nodes) {
        if (!nodes) return;
        curStatus = "expand";
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        for (var i=0, l=nodes.length; i<l; i++) {
            zTree.expandNode(nodes[i], true, false, false);
            if (nodes[i].isParent && nodes[i].zAsync) {
                expandNodes(nodes[i].children);
            } else {
                goAsync = true;
            }
        }
    }


    function asyncNodes(nodes) {
        if (!nodes) return;
        curStatus = "async";
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        for (var i=0, l=nodes.length; i<l; i++) {
            if (nodes[i].isParent && nodes[i].zAsync) {
                asyncNodes(nodes[i].children);
            } else {
                goAsync = true;
                zTree.reAsyncChildNodes(nodes[i], "refresh", true);
            }
        }
    }
    function onClick(e,treeId, treeNode) {
        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
        var data = zTree.getSelectedNodes()[0];
        if (!data.isParent) {
            createBigData(data.id);
        } else {
            zTree.expandNode(treeNode);
        }

    }

    function showTips(msg){
        $('.modal-backdrop').show();
        $('#modal_tips .modal-body').html(msg);
        $('#modal_tips').show();
    }
    $("#searchPro").click(function () {
        createBigData();
    });
    var T_columns = {};
    var T_head = {};
    var T_jData = {};
    $("#search_by").click(function () {
        var e_name = $("#e_name").val();
        var e_num = $("#e_num").val();
        var department = $("#department").val();
        var e_company = $("#companySelect").val();
        var op_user = $("#op_user").val();
        var contract_no = $("#contract_no").val();
        /*if(e_name=="" && e_num=="" && e_company == ""){
            alert("请填写搜索条件");return;
        }*/

        var obj = {
            e_name : e_name,
            e_num : e_num,
            department : department,
            op_user : op_user,
            contract_no : contract_no,
            e_company : e_company
        };
        $.ajax(
            {
                type: "post",
                url:GLOBAL_CF.DOMAIN+"/dispatch/getEmployListBySearch",
                data: obj,
                dataType: "json",
                success: function(data){
                    var type=data.type;
                    if(type==1){
                        var res=data.res;
                        if(res=="" || res==null){
                            alert("暂无结果");
                            return false;
                        }
                        $('#modal-add-event2').modal({show:true});
                        var tr='<table border="1" borderColor="grey" cellpadding="5" width="98%">';
                            tr+="<tr>";
                            tr+=" <td>公司id</td>";
                            tr+=" <td>公司名称</td>";
                            tr+="<td>姓名</td>";
                            tr+="<td>身份证</td>";
                            tr+="</tr>";
                        $.each(res,function (i,row) {
                            tr+="<tr class='checkPerson'>";
                            tr+="<td>"+row['company_id']+"</td>";
                            tr+="<td>"+row['company']+"</td>";
                            tr+="<td>"+row['name']+"</td>";
                            tr+="<td>"+row['num']+"</td>";
                            tr+="</tr>";
                        });

                            tr+="</table>";
                        $("#check").html(tr);
                    }else{
                        var jData = data.data_list;
                        e_company_id = jData[0]['e_company_id'];
                        //var sum = data.zp_sum;
                        var head = data.head;
                        T_columns = data.columns;
                        T_head = data.head;
                        nowKucunGrid.updateSettings(
                            {
                                colHeaders: head,
                                columns:data.columns

                            }
                        );
                        T_jData = jData;
                        nowKucunGrid.loadData(jData);
                        current_company_id = e_company_id;

                        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                        var node = zTree.getNodeByParam("id", e_company_id);
                        zTree.selectNode(node);
                        var obj = {};
                    }
                }
            }
        );
    });

    $(".checkPerson").live("click",function () {
        var e_num=$(this).find("td").eq(3).text();
        $(".checkPerson").removeAttr("style");
        $(this).css("color","red");
        $("#e_num_search").val(e_num);
    });
    $("#search_save").click(function () {
        var e_num = $("#e_num_search").val();
        if(!e_num){
            alert("请点击表格选择具体条目");
            return false;
        }
        var obj = {
            e_num : e_num
        };
        $.ajax(
            {
                type: "post",
                url:GLOBAL_CF.DOMAIN+"/dispatch/getEmployListBySearch",
                data: obj,
                dataType: "json",
                success: function(data){
                        var jData = data.data_list;
                        //var sum = data.zp_sum;
                        var head = data.head;
                        var e_company_id = jData[0]['e_company_id'];
                        nowKucunGrid.updateSettings(
                            {
                                colHeaders: head,
                                columns:data.columns

                            }
                        );
                        nowKucunGrid.loadData(jData);
                        current_company_id = e_company_id;

                        var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                        var node = zTree.getNodeByParam("id", e_company_id);
                        zTree.selectNode(node);
                        var obj = {};
                    $('#modal-add-event2').modal('hide');

                }
            }
        );
    });

    var current_company_id = 0;
    function createBigData(id) {
        if (!id) {
            return;
        }

        $.ajax(
            {
                type: "post",
                url:GLOBAL_CF.DOMAIN+"/dispatch/getEmployList",
                data: {
                    id : id

                },
                dataType: "json",
                success: function(data){
                    if (data.status && data.status == 100001) {
                        alert(data.content);
                        return;
                    }
                    var jData = data.data_list;
                    //var sum = data.zp_sum;
                    var head = data.head;
                    nowKucunGrid.updateSettings(
                        {
                            colHeaders: head,
                            columns:data.columns

                        }
                    );
                    nowKucunGrid.loadData(jData);
                    current_company_id = id;

                }
            }
        );
    }
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders');

    Handsontable.Dom.addEvent(colHeaders, 'click', function () {
        if (this.checked) {
            var i = parseInt($("#clo_w").val());
            nowKucunGrid.updateSettings({
                fixedColumnsLeft: i
            });
        } else {
            nowKucunGrid.updateSettings({
                fixedColumnsLeft: 0
            });
        }

    });
    Handsontable.Dom.addEvent(rowHeaders, 'click', function () {
        if (this.checked) {
            var i = parseInt($("#clo_h").val());
            nowKucunGrid.updateSettings({
                fixedRowsTop: i
            });
        } else {
            nowKucunGrid.updateSettings({
                fixedRowsTop: 0
            });
        }

    });
    var nowKucunGrid = document.getElementById('nowKucunGrid'),changeList = {},selectData,
    nowKucunGrid = new Handsontable(nowKucunGrid, {
        data: [],
        currentRowClassName: 'currentRow',
        startRows: 5,
        startCols: 4,
        rowHeaders: true,
        colHeaders: true,
        minSpareRows: 2,
        comments: true,
        contextMenu: true,
        hiddenColumns: {
            columns: ['op_user_name', 'company_name', 'department_name'],
            indicators: true
        },
        afterSelectionEndByProp : function (r,p,r2,p2) {
            if (!(p == p2 && r== r2)) {
                return;
            }
            var rowData = selectData = this.getSourceDataAtRow(r);
            if (!rowData['row_id']) {
                return;
            }
            var row_id = rowData['row_id'];
            var url = GLOBAL_CF.DOMAIN+"/dispatch/getEmploySocialById";
            var formData = {
                row_id: row_id
            }
            $.ajax({
                url: url,
                data: formData, //returns all cells' data
                dataType: 'json',
                type: 'POST',
                success: function (res) {
                    var data = res.content;
                    var social = data.social;//社保
                    var gjjin = data.gjjin;//公积金
                    if (social == 'empty') {
                        $("#socialAdd").show();
                        $("#socialSub").hide();
                        $("#social_val").html('<span style="color: red">0</span>');
                        $("#social_date").text('');
                    } else {
                        if (social.add_status == 1) {
                            $("#socialAdd").hide();
                            $("#socialSub").show();
                            $("#social_val").html('<span style="color: green">'+social.e_social_base+'</span>');
                            $("#social_date").html('<span style="color: green">'+social.date+'</span>');
                        }
                    }
                    if (gjjin == 'empty') {
                        $("#gjjinAdd").show();
                        $("#gjjinSub").hide();
                        $("#gjjin_val").html('<span style="color: red">0</span>');
                        $("#gjjin_date").text('');
                    } else {
                        if (social.add_status == 1) {
                            $("#gjjinAdd").hide();
                            $("#gjjinSub").show();
                            $("#gjjin_val").html('<span style="color: green">'+gjjin.e_gjjin_base+'</span>');
                            $("#gjjin_date").html('<span style="color: green">'+gjjin.date+'</span>');
                        }
                    }
                },
                error: function () {
                    console.text('Save error');
                }
            });
        },
        afterOnCellMouseDown :function () {

        },
        afterChange: function (change, source) {
            if (source === 'loadData' || source === 'updateData' ) {
                return; //don't save this change
            }
            for(var val = 0; val < change.length; val++) {
                if (change[val][2] != change[val][3]) {
                    var row = parseInt(change[val][0]);
                    var col = change[val][1];
                    var rowData = this.getSourceDataAtRow(row);
                    changeList[row] = rowData;
                }
            }


        },
        beforeRemoveRow:function (index,amount) {
            var delId = [];
            for (var i = 0; i < amount; i++) {
                var rowData = this.getSourceDataAtRow(index);
                if (!rowData.row_id) continue;
                delId.push(rowData.row_id);
                index++;
            }
            //删除
            var url = GLOBAL_CF.DOMAIN+"/dispatch/delEmployList";
            var formData = {
                ids: delId
            }
            $.ajax({
                url: url,
                data: formData, //returns all cells' data
                dataType: 'json',
                type: 'POST',
                success: function (res) {
                    if (res.code > 100000) {
                        console.log(res.mess);
                    }
                    else {
                        console.log(res.mess);
                    }
                },
                error: function () {
                    console.text('Save error');
                }
            });
        }
    });
    $("#employSave").click(function () {
        var hasProp = false;
        if(changeList) {
            //
            for (var prop in changeList){
                hasProp = true;
                break;
            }
            if (!hasProp){
                alert("没有要保存的");
                return;
            }

        }
        var formData = {
            "data": changeList,
            current_company_id: current_company_id
        }
        var url = GLOBAL_CF.DOMAIN+"/dispatch/saveOrUpdateEmployList";
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.status > 100000) {
                    var error_list = res.content.error_list;
                    if (error_list && error_list.length > 0) {
                        alert(error_list[0].message);
                    }
                } else {
                    alert(res.content.message);
                }

                changeList = {};
                createBigData(current_company_id);
            },
            error: function () {
                alert('保存失败，请重试');
            }
        });
    });
    $("#socialAdd").click(function () {
        if (!selectData) {
            return;
        }
        $('#social_form_add')[0].reset();

        $('#modal-add-event').modal({show:true});
        $("#add_type").val('social');
        $("#e_name_s").val(selectData.e_name);
        $("#e_num_s").val(selectData.e_num);
        $("#e_type_s").val(selectData.e_type);
        $("#row_id").val(selectData.row_id);
    });
    $("#socialSub").click(function () {
        if (!selectData) {
            return;
        }
        var e_num = selectData.e_num;
        var obj = {
            e_num :e_num
        };

        var url = GLOBAL_CF.DOMAIN;
        url+='/social/ajaxSubSocial';
        $.ajax(
            {
                type: "POST",
                url: url,
                data: obj,
                dataType:'json',
                success: function(data){
                    if (data.status > 100000) {
                        alert(data.content);
                        return;
                    } else {

                        $('#modal-add-event').modal('hide');
                        alert(data.content);
                    }
                    //window.location.reload();
                }
            }
        );
    });
    $("#gjjinSub").click(function () {
        if (!selectData) {
            return;
        }
        $('#social_form_add')[0].reset();

        $('#modal-add-event').modal({show:true});
        $("#add_type").val('social');
        $("#e_name_s").val(selectData.e_name);
        $("#e_num_s").val(selectData.e_num);
        $("#e_type_s").val(selectData.e_type);
        $("#row_id").val(selectData.row_id);
    });
    $("#gjjinAdd").click(function () {
        if (!selectData) {
            return;
        }
        $('#social_form_add')[0].reset();

        $('#modal-add-event').modal({show:true});
        $("#add_type").val('gjjin');
        $("#e_name_s").val(selectData.e_name);
        $("#e_num_s").val(selectData.e_num);
        $("#e_type_s").val(selectData.e_type);
        $("#row_id").val(selectData.row_id);
    });
    $("#social_form_add").validate({
        onsubmit:true,
        submitHandler:function(form){
            var obj = {};
            obj.e_num = $("#e_num_s").val();
            obj.row_id = $("#row_id").val();
            obj.social_base = $("#social_base_s").val();
            obj.add_date = $("#add_date_s").val();
            obj.memo = $("#remark").val();
            var url = GLOBAL_CF.DOMAIN;
            if ($("#add_type").val() == 'social') {
                url+='/social/ajaxAddSocial';
            } else if ($("#add_type").val() == 'gjjin') {
                url+='/social/ajaxAddGjjin';
            }
            $.ajax(
                {
                    type: "POST",
                    url: url,
                    data: obj,
                    dataType:'json',
                    success: function(data){
                        if (data.status > 100000) {
                            alert(data.content);
                            return;
                        } else {

                            $('#modal-add-event').modal('hide');
                            alert(data.content);
                        }
                        //window.location.reload();
                    }
                }
            );
        },
        rules: {
            social_base_s: { required: true },
            add_date_s: { required: true }
        },
        messages: {
            social_base_s:
            {
                required: '必填'
            },
            add_date_s:
            {
                required: '必填'
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('success');
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }


    });
    $(".colCheck").click(function () {

        var columns = [];
        var header = [];
        var i = 0;
        $(".colCheck").each(function(){
            if ($(this).is(':checked')) {
                columns[i] = {data:$(this).val()};
                header[i] = $(this).attr("head_val");
                i ++;
            }
        })
        nowKucunGrid.updateSettings(
            {
                colHeaders: header,
                columns:columns

            }
        );
        nowKucunGrid.loadData(T_jData);

    });
});
