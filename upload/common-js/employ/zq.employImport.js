/**
 * Created by zhangchao8189888 on 16/9/22.
 */
$(function () {
    var sumGrid = document.getElementById("sumGrid");
    var hot6 = Handsontable(sumGrid, {
        data: [],
        startRows: 5,
        startCols: 9,
        rowHeaders: true,
        minSpareRows: 1,
        comments: true,
        colHeaders: ['企业','姓名','身份证号','开户行','银行卡号','身份类别','社保基数	','公积金基数','劳务费','残保金','档案费','备注','起始合同日期','合同年限'],
        manualColumnResize: true,
        manualRowResize: true,
        columns: [
            {data: "e_company"},//0
            {data: "e_name"},//0
            {data: "e_num"},//2
            {data: "bank_name"},//4
            {data: "bank_num"},//5
            {data: "e_type_name"},//3
            {data: "shebaojishu"},//7
            {data: "gongjijinjishu"},//6
            {data: "laowufei"},//6
            {data: "canbaojin"},//6
            {data: "danganfei"},//6
            {data: "memo"},//6
            {data: "e_hetong_date"},//6
            {data: "e_hetongnian"}//6
        ]


    });
    $("#employSave").click(function () {


        var formData = {
            "data": hot6.getData(),
            produce_date: $("#produce_date").val()
        }
        var url = GLOBAL_CF.DOMAIN+"/employ/employImportAjax";
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                var successList = res.success;
                var errorList = res.error;
                $("#success").html("成功导入"+successList.length+"个");
                $("#error").html(errorList.length+"个错误");
                $("#successInfo").html("<tobdy></tobdy>");
                for(var i =0 ; i < successList.length; i++){
                    $("#successInfo").append("<tr><td>"+successList[i]+"</td></tr>");
                }
                $("#errorInfo").html("<tobdy></tobdy>");
                for(var i =0 ; i < errorList.length; i++){
                    $("#errorInfo").append("<tr><td>"+errorList[i]+"</td></tr>");
                }
            },
            error: function () {
                console.log('Save error');
            }
        });
    });
    $('#myTab a').click(function (e) {
        e.preventDefault();//阻止a链接的跳转行为
        $(this).tab('show');//显示当前选中的链接及关联的content
    })

});