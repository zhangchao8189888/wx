/**
 * Created by zhangchao8189888 on 16/10/7.
 */
$(function () {
    var
        container = document.getElementById('excelGrid'),position_val, changeList = {},
        hot5;
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders');

    var greenTdRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.fontWeight = 'bold';
        td.style.color = 'green';
        td.style.background = '#CEC';

    };
    var redTdRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.fontWeight = 'bold';
        td.style.color = 'red';
        td.style.background = '#CEC';

    };
    var whiteRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.fontWeight = 'bold';
        td.style.color = 'white';
        td.style.background = 'white';

    };
    hot5 = Handsontable(container, {
        data: createBigData(),
        startRows: 5,
        startCols: 7,
        rowHeaders: true,
        minSpareRows: 1,
        contextMenu: true,
        comments: true,
        readOnly: true,
        colHeaders: ['日期','单位名称','工资类型','原账户余额','进帐金额','实发扣减','残保金','劳务费','社保扣减','公积金扣减','个税扣减','剩余金额','备注'],
        manualColumnResize: true,
        manualRowResize: true,
        afterChange: function (change, source) {
            if (source === 'loadData' || source === 'updateData' ) {
                return; //don't save this change
            }
            for(var val = 0; val < change.length; val++) {
                if (change[val][2] != change[val][3]) {
                    var row = parseInt(change[val][0]);
                    var col = change[val][1];
                    var rowData = this.getSourceDataAtRow(row);
                    if (rowData.deal_date && ($.util.checkRate(rowData.deal_out_val) || $.util.checkRate(rowData.deal_into_val))) {

                        rowData.deal_out_val = rowData.deal_out_val ? rowData.deal_out_val: 0.00;
                        rowData.deal_into_val = rowData.deal_into_val ?rowData.deal_into_val: 0.00;
                        rowData.deal_mark = rowData.deal_mark ?rowData.deal_mark: '';
                        rowData.deal_name = rowData.deal_name ?rowData.deal_name: '';
                        rowData.deal_company_name = rowData.deal_company_name ?rowData.deal_company_name: '';

                        rowData.row = row;
                        rowData.col = col;
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
                    if (res.code > 100000) {
                        changeList = {};
                        if (res.errorList && res.errorList.length > 0) {
                            $(".alert-error").html('<button data-dismiss="alert" class="close">×</button>');
                            for(var val = 0; val < res.errorList.length; val++) {
                                var error = res.errorList[val];
                                var row = parseInt(error.row);
                                var rowData = hot5.getSourceDataAtRow(row);
                                rowData.deal_out_val = rowData.deal_out_val ? rowData.deal_out_val: 0.00;
                                rowData.deal_into_val = rowData.deal_into_val ?rowData.deal_into_val: 0.00;
                                rowData.deal_mark = rowData.deal_mark ?rowData.deal_mark: '';
                                rowData.deal_name = rowData.deal_name ?rowData.deal_name: '';
                                rowData.deal_company_name = rowData.deal_company_name ?rowData.deal_company_name: '';

                                rowData.row = row;
                                rowData.col = col;
                                row += 1;
                                $(".alert-error").show();
                                $(".alert-error").append('<strong>第'+row+'行保存失败请点击保存</strong>');

                            }
                        }
                    } else {
                        $(".alert-success").html('<button data-dismiss="alert" class="close">×</button>');
                        changeList = {};
                        if (res.success_List && res.success_List.length > 0) {
                            for(var val = 0; val < res.success_List.length; val++) {
                                var succ = res.success_List[val];
                                var  row = parseInt(succ.row);
                                hot5.setDataAtRowProp(row,'row_id',succ.row_id,"updateData");
                                row += 1;
                                $(".alert-success").show();
                                $(".alert-success").append('<strong>第'+row+'行保存成功</strong>');

                            }
                        }
                    }
                },
                error: function () {
                    alert('保存失败，请重试');
                }
            });

        },
        isEmptyRow: function (row) {
            var col, colLen, value, meta;

            for (col = 1, colLen = this.countCols(); col < colLen; col ++) {
                if(col == 0 ||col == 1 ||col == 2 ||col == 3 ||col == 4 ||col == 6){
                    continue;
                }
                value = this.getDataAtCell(row, col);

                if (value !== '' && value !== null && typeof value !== 'undefined') {
                    if (typeof value === 'object') {
                        meta = this.getCellMeta(row, col);

                        return Handsontable.helper.isObjectEquals(this.getSchema()[meta.prop], value);
                    }
                    return false;
                }
            }

            return true;
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
//['日期','单位名称','工资类型','原账户余额','进帐金额','实发扣减','残保金','劳务费','社保扣减','公积金扣减','个税扣减','剩余金额','备注'],
        columns: [
            {   data: "op_date",
                type: 'text'
            },
            {
                data: "company_name",
                type: 'text'
            },
            {
                data: "sal_type",
                type: 'text'
            },
            {
                data: "account_val",
                type: 'text'
            },
            {
                data: "into_val",
                type: 'text'
            },
            {
                data: "shifa_val",
                type: 'text'
            },
            {
                data: "canbao_val",
                type: 'text'
            },
            {
                data: "laowu_val",
                type: 'text'
            },
            {
                data: "shebao_val",
                type: 'text'
            },
            {
                data: "gongjijin_val",
                type: 'text'
            },
            {
                data: "geshui_val",
                type: 'text'
            },
            {data: "remian_val", type: 'text'},
            {data: "memo",
                //colWidths:200,
                type: 'text'}
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
                url: "/financial/getDetailListAjax",
                data: {
                    companyId : company.companyId
                },
                dataType: "json",
                success: function(data){
                    var jData = data.content;
                    if ($.util.isArray(jData)) {

                        hot5.loadData(jData);
                        hot5.updateSettings({
                            cells: function (row, col, prop) {
                                var cellProperties = {};
                                var rowData = hot5.getSourceDataAtRow(row);
                                if (prop == "remian_val" || prop == "account_val" ) {
                                    if (rowData.remian_val > 0 ) {
                                        cellProperties.renderer = greenTdRenderer;
                                    } else if(rowData.remian_val < 0) {
                                        cellProperties.renderer = redTdRenderer;
                                    }
                                }
                                if (prop == "into_val") {
                                    cellProperties.renderer = greenTdRenderer;
                                } else if (prop == "shifa_val"
                                    ||prop == "canbao_val" ||prop == "laowu_val" ||prop == "shebao_val"||prop == "geshui_val"||prop == "gongjijin_val") {
                                    cellProperties.renderer = redTdRenderer;
                                }
                                if (rowData[prop] == "0") {
                                    cellProperties.renderer = whiteRenderer;
                                }

                                return cellProperties;
                            }
                        });

                    }


                }
            }
        );
    }
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
});