/**
 * Created by zhangchao8189888 on 16-4-1.
 */
$(function () {
    var
        container = document.getElementById('excelGrid'),position_val,
        hot5;
    Handsontable.hooks.add('afterGetColHeader', function (col, TH) {
        if (col >= 0) {//this.getSettings().columnSorting &&
            Handsontable.Dom.addClass(TH.querySelector('.colHeader'), 'columnClick');
        }
    });
    function createBigData() {
        hot5.loadData(ListJson);


        bindColumnSortingAfterClick.call(hot5);
    }
    $(".click_position").on('focus',function () {
        position_val = $(this).attr("id");
    });
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
    hot5 = Handsontable(container, {
        //data: ['111'],
        startRows: 1,
        startCols: 4,
        rowHeaders: true,
        colHeaders: true,
        minSpareRows: 1,
        comments: true,
        manualColumnResize: true,
        contextMenu: true,
        afterSelectionEndByProp : function (r,p,r2,p2) {
            if (p != p2) {
                return;
            } else if (p == p2 && r== r2) {
                var rowDate =this.getSourceDataAtRow(r);
                if (rowDate.message) {
                    $(".alert-error").show();

                    $(".alert-error").html('<button data-dismiss="alert" class="close">×</button>' +
                        '<strong>导入失败</strong>：'+rowDate.message);
                }

            } else {
                var sum = 0;
                var hang = 0;
                for (var i = r; i <= r2; i++) {
                    var cellDate =this.getDataAtCell(i,p);
                    if (!parseInt(cellDate)) cellDate = 0;
                    sum += parseInt(cellDate);
                    hang ++;
                }
                $("#p_num").text(hang);
                $("#p_sum").text(sum);
            }

        }
    });
    //createBigData();
    bindColumnSortingAfterClick.call(hot5);
    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.color = 'red';

    };

    var table_info ={};
    $("#add_btn").click(function () {
        var data = hot5.getData();
        data = JSON.stringify(data)
        var formData = {
            data: data,
            table_info: table_info
        };
        $.ajax(
            {
                type: "post",
                url:GLOBAL_CF.DOMAIN+"/dispatch/saveEmployList",
                data: formData,
                dataType: "json",
                success: function (res) {
                    layer.msg("保存完成，请查看详细结果");
                    var errorData = res.errorInfo;
                    var successData = res.successInfo;
                    $("#errorInfo").html('');
                    $("#error").html(errorData.length+"个错误");
                    $("#success").html("总行数："+successData.totalSum+",保存成功"+successData.saveSum+"行"+"，更新成功："+successData.updateSum);
                    for(var i =0 ; i < errorData.length; i++){
                        $("#errorInfo").append("<tr><td>"+errorData[i]+"</td></tr>");

                    }

                }

            }
        );
    });
    $("#excel_open").click(function () {
        $('#modal-add-event-product').modal({show:true});
    });
    $("#btn").click(function () {
        ajaxFileUpload();
    });
    function ajaxFileUpload() {
        $.ajaxFileUpload
        (
            {
                url: '/dispatch/readExcel', //用于文件上传的服务器端请求地址
                secureuri: false, //是否需要安全协议，一般设置为false
                fileElementId: 'upFile', //文件上传域的ID
                dataType: 'text', //返回值类型 一般设置为json
                success: function (data, status)  //服务器成功响应处理函数
                {

                    var data = $.parseJSON(data);
                    var code = data.code;
                    if (code > 100000) {
                        layer.msg(data.msg);
                    } else {
                        var excel_data = data.check_data;
                        hot5.loadData(excel_data.data);
                        var errorList = excel_data.error_info;
                        var diff_data = excel_data.diff_data;
                        table_info = excel_data.table_info;
                        //$(".alert-error").show();
                        $("#error").html(errorList.length+"个错误");
                        $("#errorInfo").html("<tobdy></tobdy>");
                        for(var i =0 ; i < errorList.length; i++){
                            $("#errorInfo").append("<tr><td>"+errorList[i]['error_info']+"</td></tr>");
                            hot5.updateSettings({
                                cell: [
                                    {row: 0, col: errorList[i]['head_key'], renderer: redRenderer}
                                ]
                            });
                            //hot5.setCellMeta(0, errorList[i]['head_key'], 'renderer', redRenderer);
                        }
                        for(var i =0 ; i < diff_data.length; i++){
                            $("#errorInfo").append("<tr><td>"+diff_data[i]+"</td></tr>");
                            /*hot5.updateSettings({
                                cell: [
                                    {row: 0, col: errorList[i]['head_key'], renderer: redRenderer}
                                ]
                            });*/
                            //hot5.setCellMeta(0, errorList[i]['head_key'], 'renderer', redRenderer);
                        }
                    }
                    $('#modal-add-event-product').modal("hide");

                },
                error: function (data, status, e)//服务器响应失败处理函数
                {
                    alert(e);
                }
            }
        )
        return false;
    }
});