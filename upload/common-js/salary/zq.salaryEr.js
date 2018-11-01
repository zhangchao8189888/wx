/**
 * Created by zhangchao8189888 on 16/8/6.
 */

$(document).ready(function () {
    $("#shenfenzheng").tagsinput({
        itemValue: 'id',
        itemText: 'text'
    });
    $("#add").tagsinput({
        itemValue: 'id',
        itemText: 'text'
    });

    $(".add_focus").click(function () {

        var text = $(this).attr("data-text");
        $("#focus_id").val( $(this).attr("data-val"));
        $(".add_focus").each(function() {
            if (text != $(this).attr("data-text")) {

                $(this).attr('checked', false);
                $(this).parents('.checked').find('span').removeClass('checked');

            }
        })
        $("#add_text").text(text);
    });
    Handsontable.hooks.add('afterGetColHeader', function (col, TH) {
        if (col >= 0) {//this.getSettings().columnSorting &&
            Handsontable.Dom.addClass(TH.querySelector('.colHeader'), 'columnClick');
        }
    });
    var bindColumnSortingAfterClick = function () {
        var instance = this;

        var eventManager = Handsontable.eventManager(instance);
        eventManager.addEventListener(instance.rootElement, 'click', function (e){
            if(Handsontable.Dom.hasClass(e.target, 'columnClick')) {
                var col = getColumn(e.target);
                var rowData = hot5.getData()[0];
                var id = $("#focus_id").val();
                if (id == 'shenfenzheng') {
                    $('#shenfenzheng').tagsinput('removeAll');
                }
                $("#"+id+"").tagsinput('add', { id: col, text: rowData[col] });
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
    var container = document.getElementById("exampleGrid");
    var hot5 = Handsontable(container, {
        //data: [],
        startRows: 5,
        minSpareRows: 1,
        rowHeaders: true,
        colHeaders: true,
        manualColumnResize: true,
        manualRowResize: true,
        contextMenu: true,
        rowHeights: function(){return 25;}
    });
    bindColumnSortingAfterClick.call(hot5);
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders');

    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.backgroundColor = 'red';

    };
    var sumGrid = document.getElementById("sumGrid");
    var hot6 = Handsontable(sumGrid, {
        data: [],
        rowHeaders: true,
        colHeaders: true,
        manualColumnResize: true,
        manualRowResize: true,
        readOnly:true,
        //minSpareRows: 1,
        contextMenu: true
    });
    Handsontable.Dom.addEvent(colHeaders, 'click', function () {
        if (this.checked) {
            hot6.updateSettings({
                fixedColumnsLeft: 2
            });
        } else {
            hot6.updateSettings({
                fixedColumnsLeft: 0
            });
        }

    });
    var excelMove = [];
    var excelHead = [];
    $('.sumErSalary').click(function () {
        if (!$("#shenfenzheng").val()) {
            alert("请选择添加项和身份证号！");
            return;
        }

        $.handsonTableFn.clearEmptyRowOrCol(hot5);
        var type=$(this).attr("data-type");
        var url="/sumErSalary";
        if (!$("#salaryTime_er").val()) {
            alert("请选择工资月份！");return;
        }
        var objData={
            shenfenzheng : $("#shenfenzheng").val(),
            add : $("#add").val(),
            salaryTime_er : $("#salaryTime_er").val(),
            data: JSON.stringify(hot5.getData())
        };
        $.ajax({
            url: GLOBAL_CF.DOMAIN+"/salary"+url,
            data: objData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.result === 'ok') {
                    var  salary = res.data;
                    excelHead =  res.head;
                    var shenfenleibie = res['shenfenleibie'];
                    var colWidths = [];
                    for(var i = 0;i < excelHead.length; i++){
                        if (i == shenfenleibie) colWidths.push(160);
                        else if (i == excelHead.length-1) {colWidths.push(160);}
                        else {
                            colWidths.push(80);
                        }
                    }
                    var errorList = res.error;
                    $("#error").html(errorList.length+"个错误");
                    $("#errorInfo").html("<tobdy></tobdy>");
                    for(var i =0 ; i < errorList.length; i++){
                        $("#errorInfo").append("<tr><td>"+errorList[i]['error']+"</td></tr>");
                    }
                    excelMove = res.move;

                    hot6.updateSettings({
                        colHeaders: excelHead
                    });
                    hot6.updateSettings({
                        colWidths: colWidths
                    });
                    hot6.loadData(salary);
                    hot6.updateSettings({
                        cells: function (row, col, prop) {
                            var cellProperties = {};
                            //console.log(hot6.getData()[row][6]);
                            if (hot6.getData()[row][shenfenleibie] == 'null' || hot6.getData()[row][shenfenleibie] == null){
                                //cellProperties.readOnly = true;
                                cellProperties.renderer = redRenderer;
                            }
                            return cellProperties;
                        }
                    })
                }
                else {
                    console.log('Save error');
                }
            },
            error: function () {
                console.log('Save error');
            }
        });

    });

    $("#save").click(function(){
        $('#modal-event1').modal({show:true});
    });
    var submit = 0;
    $("#salarySave").click(function () {
        if (submit == 1) {
            alert("不能重复提交！");
            return;
        }

        if($("#companySearch").val() == ""){
            return;
        }
        var data = hot6.getData();
        if (data.length < 0) {
            return;
        }
        submit = 1;
        var url = GLOBAL_CF.DOMAIN+"/salary/saveErSalary";
        var formData = {
            "data": JSON.stringify(data),
            company_id: $("#companySearch").val(),
            salaryDate: $("#salary_date").val(),
            mark:  $("#mark").val(),
            excelHead:  excelHead,
            excelMove : excelMove
        }

        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                submit = 0;
                if (res.code > 100000) {

                    alert(res.content);
                    return;
                }
                else {
                    alert(res.content);
                    window.location.reload();
                    //window.location.href = "index.php?action=Salary&mode=salarySearchList";
                }
            },
            error: function () {
                submit = 0;
                console.log('Save error');
            },
            complete:function(){
                submit = 0;
            }
        });
    });
    var removeEmpty  = function () {
        var i = 0;
        for (var row = 0,  rowLen = hot5.countRows(); row < rowLen; row ++) {
            if (hot5.isEmptyRow(row)) {
                hot5.alter('remove_row',row,1);
            }
        }
        var i = 0;
        for (var col = 0,  colLen = hot5.countCols(); col < colLen; col ++) {
            if (hot5.isEmptyCol(col)) {
                hot5.alter('remove_col',col,1);
            }
        }
    }
});/**
 * Created by zhangchao8189888 on 15-1-3.
 */
