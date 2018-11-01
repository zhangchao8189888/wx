/**
 * Created by zhangchao8189888 on 17/5/31.
 */
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
        data: [],//
        colHeaders: ['序号','年份','年终奖时间','单位名称','姓名','身份证号','年终奖',	'代扣税',	'应发合计',	'实发合计',	'交中企'],
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
        },
        columns: [
            {data: 'index'},//0
            {data: 'year'},//0
            {data: 'salaryTime'},//0
            {data: 'companyName'},//0
            {data: 'e_name'},//0
            {data: 'e_num'},//0
            {data: 'nianzhongjiang'},//0
            {data: 'nian_daikoushui'},//1
            {data: 'yingfaheji'},//1
            {data: 'shifajinka'},
            {data: 'jiaozhongqi'}
        ]
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
    $("#export").click(function () {
        var tableData = salaryGride.getData();
        var head = salaryGride.getColHeader();
        $("#excel_data").val(JSON.stringify(tableData));
        $("#head").val(JSON.stringify(head));
        $("#salForm").attr("action","/adminCompany/salaryExport");
        $("#salForm").submit();
    });
    $("#exportFirst").click(function () {
        var sal_date = $("#sal_date").val();
        $("#date").val(sal_date);
        $("#salForm").attr("action","/tax/getFirstComSumTax");
        $("#salForm").submit();
    });
    $("#exportEr").click(function () {
        var tableData = salaryGride.getData();
        var head = salaryGride.getColHeader();
        $("#excel_data").val(JSON.stringify(tableData));
        $("#head").val(JSON.stringify(head));
        $("#salForm").attr("action","/tax/getErComSumTax");
        $("#salForm").submit();
    });
    function createBigData() {
        var sal_date = $("#sal_date").val();
        $.ajax(
            {
                type: "get",
                url: "/tax/getNianTaxList",
                data: {
                    date : sal_date,
                    com_name: $("#com_name").val()

                },
                dataType: "json",
                success: function(data){
                    var jData = data.content;

                    salaryGride.loadData(jData);

                }
            }
        );
    }
    $("#search_btn").click(function (){
        createBigData();
    });
});