/**
 * Created by zhangchao8189888 on 16/7/30.
 */

$(function () {
    var
        container = document.getElementById('excelGrid'),position_val, changeList = {},
        hot5;
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders');

    Handsontable.Dom.addEvent(colHeaders, 'click', function () {
        if (this.checked) {
            var i = parseInt($("#clo_w").val());
            hot5.updateSettings({
                fixedColumnsLeft: i
            });
        } else {
            hot5.updateSettings({
                fixedColumnsLeft: 0
            });
        }

    });
    Handsontable.Dom.addEvent(rowHeaders, 'click', function () {
        if (this.checked) {
            var i = parseInt($("#clo_h").val());
            hot5.updateSettings({
                fixedRowsTop: i
            });
        } else {
            hot5.updateSettings({
                fixedRowsTop: 0
            });
        }

    });

    var fontWeight = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.fontWeight = 'bold';
        td.style.color = 'green';

    };
    function returnFloat(value){
        var value=Math.round(parseFloat(value)*100)/100;
        var xsd=value.toString().split(".");
        if(xsd.length==1){
            value=value.toString()+".00";
            return value;
        }
        if(xsd.length>1){
            if(xsd[1].length<2){
                value=value.toString()+"0";
            }
            return value;
        }
    }
    hot5 = Handsontable(container, {
        data: createBigData(),
        startRows: 5,
        startCols: 7,
        rowHeaders: true,
        minSpareRows: 1,
        contextMenu: true,
        comments: true,
        colHeaders: ['日期',	'借-支出','贷-收入','-------','备注明细','	收（付）方名称','具体单位补充','审批状态'],
        colWidths: [80, 80, 80, 50, 250, 200, 200,90],
        manualColumnResize: true,
        manualRowResize: true,
        autoWrapRow: true,
        afterChange: function (change, source) {
            if (source === 'loadData' || source === 'updateData' ) {
                return; //don't save this change
            }
            if(source === 'updateAccount' ){
                updateAccount(change);
                return;
            }
            for(var val = 0; val < change.length; val++) {
                if (change[val][2] != change[val][3]) {
                    var row = parseInt(change[val][0]);
                    var col = change[val][1];
                    var rowData = this.getSourceDataAtRow(row);//&& ($.util.checkRate(rowData.deal_out_val) || $.util.checkRate(rowData.deal_into_val))
                    if (rowData.deal_date ) {
                        rowData.deal_out_val = rowData.deal_out_val ? $.util.formatStr(rowData.deal_out_val): 0.00;
                        rowData.deal_into_val = rowData.deal_into_val ?$.util.formatStr(rowData.deal_into_val): 0.00;
                        rowData.deal_mark = rowData.deal_mark ?rowData.deal_mark: '';
                        rowData.deal_name = rowData.deal_name ?rowData.deal_name: '';
                        rowData.deal_company_name = rowData.deal_company_name ?rowData.deal_company_name: '';

                        rowData.row = row;
                        rowData.col = col;
                        if (col == 'deal_company_name' && change[val][2] == '' && change[val][3] == '暂无') {
                            rowData.updateAccount = 0;
                        } else if (col == 'deal_company_name') {
                            rowData.updateAccount = 1;
                        }
                        changeList[row] = rowData;
                        if (!changeList.length) {
                            changeList.length = 0;
                        }
                        changeList.length++;
                    }
                }
            }
            if(!changeList.length || changeList.length < 1) {
                //alert("没有要保存的");
                return;
            } else {
                delete changeList.length;
            }
            var formData = {
                "data": changeList
            }
            var url = '/financial/saveOrUpdateDeal';
            $.ajax({
                url: url,
                data: formData, //returns all cells' data
                dataType: 'json',
                type: 'POST',
                success: function (res) {
                    var res = res.content;
                    if (res.code > 100000) {
                        changeList = {};
                        if (res.errorList && res.errorList.length > 0) {
                            $(".alert-error").html('<button data-dismiss="alert" class="close">×</button>');
                            for(var val = 0; val < res.errorList.length; val++) {
                                var error = res.errorList[val];
                                var row = parseInt(error.row);

                                row += 1;
                                $(".alert-error").show();
                                $(".alert-error").append('<strong>第'+row+'行保存失败请点击保存</strong>');

                            }
                        }
                    } else {
                        $(".alert-success").html('<button data-dismiss="alert" class="close">×</button>');
                        changeList = {};

                    }
                    if (res.successList && res.successList.length > 0) {
                        for(var val = 0; val < res.successList.length; val++) {
                            var succ = res.successList[val];
                            var  row = parseInt(succ.row);
                            hot5.setDataAtRowProp(row,'row_id',succ.row_id,"updateData");
                            row += 1;
                            $(".alert-success").show();
                            $(".alert-success").append('<strong>第'+row+'行保存成功</strong>');

                        }
                    }
                },
                error: function () {
                    alert('保存失败，请重试');
                }
            });

        },
        afterSelectionEndByProp : function (r,p,r2,p2) {
            if (p == p2 && r== r2 &&  p == "deal_company_name") {
                var rowData = this.getSourceDataAtRow(r);
                if (rowData.option.indexOf("已确认") > 0) {
                    return;
                }
                if (rowData.deal_into_val > 0) {
                    $("#row").val(r);
                    $("#modal-add-event").modal({show:true});
                }

             } else if (p == p2 && r== r2 &&  p == "option") {

            } else if (p == p2 &&  p == "deal_into_val") {
                var sum = 0;
                var hang = 0;
                for (var i = r; i <= r2; i++) {
                    var cellDate =this.getDataAtCell(i,p);
                    if (!parseFloat(cellDate)) cellDate = 0;
                    sum += parseFloat(cellDate);
                    hang ++;
                }
                $("#p_num").text(hang);
                $("#p_sum").text(returnFloat(sum));
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
            var url = GLOBAL_CF.DOMAIN+"/financial/delBankIntoOut";
            var formData = {
                ids: delId
            };
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
        },

        columns: [
            {   data: "deal_date",
                type: 'text'
            },
            {
                data: "deal_out_val",
                type: 'text'
            },
            {
                data: "deal_into_val",
                type: 'text'
            },
            {
                data: function () {
                    return '';
                }
            },
            {data: "deal_mark", type: 'text'},
            {data: "deal_name", type: 'text'},
            {
                data: "deal_company_name",
                type: 'text',
                renderer : fontWeight,
                readonly:true
            },
            {
                data: "option",
                type: 'text',
                renderer : "html"
            },
        ]
    });
    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.color = 'red';

    };

    function createBigData() {
        $.ajax(
            {
                type: "post",
                url: "/financial/getDealList",
                data: {
                    start_date : $('#start_date').val(),
                    end_date : $("#end_date").val(),
                    period : $("#period").val(),
                    companyList : $("#companyList").val()
                },
                dataType: "json",
                success: function(data){
                    var jData = data.data_list;
                    if ($.util.isArray(jData)) {

                        hot5.loadData(jData);
                    }


                }
            }
        );
    }
    $("#addCompanyName").click(function () {
        var row = $("#row").val();
        var text = $("#companySearch").val();
        if (!companyList.contains(text)) {
            text = '暂无';
        }
        hot5.setDataAtRowProp(row, 'deal_company_name', text,"updateAccount");
        $("#modal-add-event").modal('hide');
    });
    $("#searchDeal").click(function () {
        createBigData();
    });
    $("#add_btn").click(function () {
        var data = hot5.getData();
        var date = $("#produce_date").val();
        var formData = {
            data: data,
            date:date
        };
        var url = GLOBAL_CF.DOMAIN+"/social/SaveGjjinList";
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.status == 100001) {
                    var error_list = res.content.error_list;

                    if ($.util.isArray(error_list) && error_list.length > 0 ) {
                        var cell_pro = [],bit = 0;
                        for (var i = 0; i < error_list.length; i++) {
                            var error_obj = error_list[i];
                            if (error_obj.message) {
                                bit = 0;
                                cell_pro.push(
                                    {row: error_obj.key, col:bit, renderer: redRenderer,comment: error_obj.message}
                                );
                            }

                        }
                        hot5.updateSettings({
                            cell: cell_pro
                        })
                    }
                } else {
                    alert(res.content);
                }

                return;
            },
            error: function () {
                alert('保存失败，请重试');
            }
        });
    });
    var updateAccount = function (change) {
        if (change[0][2] != change[0][3]) {
            var row = parseInt(change[0][0]);
            var col = change[0][1];
            var rowData = hot5.getSourceDataAtRow(row);//&& ($.util.checkRate(rowData.deal_out_val) || $.util.checkRate(rowData.deal_into_val))
            if (rowData.deal_into_val == '' || rowData.deal_into_val < 1) {
                alert('入账收入为0，目前只支持入账操作！');
                hot5.setDataAtRowProp(row,col,'',"updateData");
                return;
            }
            if (col == 'deal_company_name' && change[0][2] == '' && change[0][3] == '暂无') {
                return;
            } else if (col == 'deal_company_name') {
                rowData.updateAccount = 1;
            }
            changeList[row] = rowData;
            if (!changeList.length) {
                changeList.length = 0;
            }
            rowData.row = row;
            rowData.col = col;
            changeList.length++;
        }
        if(!changeList.length || changeList.length < 1) {
            //alert("没有要保存的");
            return;
        } else {
            delete changeList.length;
        }
        var formData = {
            "data": changeList
        }
        var url = '/financial/updateCurrentAccount';
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                var res = res.content;
                if (res.code > 100000) {
                    changeList = {};
                    if (res.errorList && res.errorList.length > 0) {
                        $(".alert-error").html('<button data-dismiss="alert" class="close">×</button>');
                        for(var val = 0; val < res.errorList.length; val++) {
                            var error = res.errorList[val];
                            var row = parseInt(error.row);

                            row += 1;
                            $(".alert-error").show();
                            $(".alert-error").append('<strong>第'+row+'行保存失败请点击保存</strong>');

                        }
                    }
                } else {
                    $(".alert-success").html('<button data-dismiss="alert" class="close">×</button>');
                    changeList = {};

                }
                if (res.successList && res.successList.length > 0) {
                    for(var val = 0; val < res.successList.length; val++) {
                        var succ = res.successList[val];
                        var  row = parseInt(succ.row);
                        hot5.setDataAtRowProp(row,'row_id',succ.row_id,"updateData");
                        row += 1;
                        $(".alert-success").show();
                        $(".alert-success").append('<strong>第'+row+'行保存成功</strong>');

                    }
                }
            },
            error: function () {
                alert('保存失败，请重试');
            }
        });
    }
    $("#fileImport").click(function(){
        $("#modal-import-event").modal({show:true});
    });
    $("#submitBtn1").click(function(){
        $("#modal-import-event").modal({show:true});
    });

    $(".confirm").live("click",function(){

        var that = this;
        $.ajax(
            {
                type: "post",
                url: "/financial/confirmDeal",
                data: {
                    dealId : $(this).attr("data-id")
                },
                dataType: "json",
                success: function(data){
                    if (data.status == 100000) {
                        $(that).parent().html("<font style='color: #00B83F'>确认</font>");
                    }


                }
            }
        );

    });

    $(".cancel").live("click",function(){

        var that = this;
        $.ajax(
            {
                type: "post",
                url: "/financial/cancelDeal",
                data: {
                    dealId : $(this).attr("data-id")
                },
                dataType: "json",
                success: function(data){
                    if (data.status == 100000) {
                        $(that).parent().html("<font style='color: red'>拒绝</font>");
                    }


                }
            }
        );
    });
    $(".reconfirm").live("click",function(){

        var that = this;
        $.ajax(
            {
                type: "post",
                url: "/financial/reconfirmDeal",
                data: {
                    dealId : $(this).attr("data-id")
                },
                dataType: "json",
                success: function(data){
                    if (data.status == 100000) {
                        $(that).parent().html("<font style='color: red'>待确认</font>");
                    }


                }
            }
        );
    });

});