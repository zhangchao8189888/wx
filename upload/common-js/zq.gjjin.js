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
        //data: [],
        startRows: 5,
        startCols: 8,
        rowHeaders: true,
        colHeaders: true,
        minSpareRows: 1,
        comments: true,
        colHeaders: ['所属单位',	'单位名称	','员工姓名	','身份证号','户口性质','	公积金基数','是否新参', '备注'],
        manualColumnResize: true,
        manualRowResize: true
    });
    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.color = 'red';

    };
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
                    //var success_list = res.content.success_list;
                    //for (var i = 0; i < success_list.length; i++) {
                    //    var row = success_list[i];
                    //    hot5.alter ('remove_row',row);
                    //}
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