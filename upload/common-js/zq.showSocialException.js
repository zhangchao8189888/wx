/**
 * Created by zhangchao8189888 on 16-4-9.
 */
$(function () {
    var
        container = document.getElementById('excelGrid'),position_val,
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
    function createBigData() {
        var type = $("#search_type").val();
        var date = $("#search_time").val();
        $.ajax(
            {
                type: "post",
                url:GLOBAL_CF.DOMAIN+"/social/ajaxSocialException",
                data: {
                    date:date,
                    type : type

                },
                dataType: "json",
                success: function(data){
                    if (data.status == 100001) {
                        alert(data.content);
                        return;
                    }
                    var jData = data.content;
                    //var sum = data.zp_sum;
                    var head = data.head;
                    //nowKucunGrid.updateSettings(
                    //    {
                    //        colHeaders: head,
                    //        columns:data.columns
                    //
                    //    }
                    //);
                    hot5.loadData(jData);

                }
            }
        );
    }
    var bindColumnSortingAfterClick = function () {
        var instance = this;

        var eventManager = Handsontable.eventManager(instance);
        eventManager.addEventListener(instance.rootElement, 'click', function (e){
            if(Handsontable.Dom.hasClass(e.target, 'columnClick')) {
                var col = getColumn(e.target)+1;
                $("#"+position_val).val(col);

            }
        });

        function countRowHeaders() {
            var THs = instance.view.TBODY.querySelector('tr').querySelectorAll('th');
            return THs.length;
        }

        function getColumn(target) {
            var TH = Handsontable.Dom.closest(target, 'TH');
            return Handsontable.Dom.index(TH) - countRowHeaders();
        }
    };
    var changeList = {};
    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.color = 'red';

    };
    hot5 = Handsontable(container, {
        data: createBigData(),
        startRows: 5,
        startCols: 0,
        rowHeaders: true,
        minSpareRows: 1,
        comments: true,
        colHeaders: ['所属单位',	'单位名称','部门','员工姓名','身份证号','社保增员月份','核对人','异常情况说明','类型','创建时间'],
        manualColumnResize: true,
        manualRowResize: true,
        columns: [
            {data: "belong_company_name"},//0
            {data: "company_name"},//1
            {data: "section"},//2
            {data: "e_name"},//3
            {data: "e_num"},//4
            {data: "date_month"},//5
            {data: "check_man"},//6
            {data: "exception_note"},//7
            {
                data: "type",
                type: 'autocomplete',
                source: ['社保', '公积金'],
                strict: false
            },// 8
            {data: "date",readOnly:true}//9
        ],
        afterChange: function (change, source) {
            if (source === 'loadData' || source === 'updateData' ) {
                return; //don't save this change
            }

            for(var val = 0; val < change.length; val++) {
                if (change[val][2] != change[val][3]) {
                    var row = parseInt(change[val][0]);
                    var col = change[val][1];
                    var rowData = this.getSourceDataAtRow(row);
                    //var sourceData = this.getSourceDataAtRow(row);
                    changeList[row] = rowData;
                    //changeList[row]['row_id'] = sourceData.row_id;
                }
            }


        }
    });
    $("#add_btn").click(function () {
        var formData = {
            data: changeList
        };
        var url = GLOBAL_CF.DOMAIN+"/social/saveSocialException";
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.status>100000) {
                    var error_list = res.content.error_list;
                    var success_list = res.content.success_list;
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

                    createBigData();
                }

            },
            error: function () {
                alert('保存失败，请重试');
            }
        });
    });

    //按时间查询
    $("#search_btn").click(function () {
        var type = $("#search_type").val();
        var date = $("#search_time").val();
        $.ajax({
            url: GLOBAL_CF.DOMAIN+"/social/ajaxSocialException",
            data: {
                date:date,
                type:type
            }, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
               if(res.status==100001){
                   alert(res.content);
                   return false;
                }else{
                   var jData = res.content;
                   hot5.loadData(jData);
                }
            }
        });
    });
});