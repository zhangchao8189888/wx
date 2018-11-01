/**
 * Created by zhangchao8189888 on 16/8/21.
 */
$(document).ready(function () {
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders');

    Handsontable.Dom.addEvent(colHeaders, 'click', function () {
        if (this.checked) {
            salaryGride.updateSettings({
                fixedColumnsLeft: 2
            });
        } else {
            salaryGride.updateSettings({
                fixedColumnsLeft: 0
            });
        }

    });
    var container = document.getElementById("exampleGrid");
    var salaryGride = Handsontable(container, {
        data: [],
        rowHeaders: true,
        colHeaders: true,
        manualColumnResize: true,
        manualRowResize: true,
        readOnly:true,
        minSpareRows: 1,
        contextMenu: true,
        afterSelectionEndByProp : function (r,p,r2,p2) {
            if (p != p2) {
                return;
            }
            var sum = 0;
            var hang = 0;
            for (var i = r; i <= r2; i++) {
                var cellDate =this.getDataAtCell(i,p);
                if (!parseFloat(cellDate)) cellDate = 0;
                sum += parseFloat(cellDate);
                hang ++;
            }
            $("#p_num").text(hang);
            $("#p_sum").text(sum.toFixed(2));
        }
    });
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders');

    Handsontable.Dom.addEvent(colHeaders, 'click', function () {
        if (this.checked) {
            salaryGride.updateSettings({
                fixedColumnsLeft: 2
            });
        } else {
            salaryGride.updateSettings({
                fixedColumnsLeft: 0
            });
        }

    });
    var colWidths = [];
    /*for(var i = 0;i < head.length; i++){
        colWidths.push(80);
    }
*/
    salaryGride.updateSettings({
        colHeaders: head
    });
    salaryGride.loadData(content);
    $("#export").click(function () {
        var tableData = salaryGride.getData();
        var head = salaryGride.getColHeader();
        $("#excel_data").val(JSON.stringify(tableData));
        $("#head").val(JSON.stringify(head));
        $("#salForm").attr("action","/adminCompany/salaryExport");
        $("#salForm").submit();
    });

});