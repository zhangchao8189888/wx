/**
 * Created by zhangchao8189888 on 17/1/17.
 */
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
        minSpareRows: 10,
        comments: true,
        colHeaders: ['姓名','身份证号','身份类别','社保基数','公积金基数','修改结果(非填项)'],
        manualColumnResize: true,
        manualRowResize: true,
        columns: [
            {data: "e_name"},//0
            {data: "e_num"},//2
            {data: "e_type"},//7
            {data: "shebaojishu"},//7
            {data: "gongjijinjishu"},
            {data: "memo",readOnly:true}
        ]


    });
    $("#employSave").click(function () {


        var formData = {
            "data": hot6.getData(),
            produce_date: $("#produce_date").val()
        }
        var url = GLOBAL_CF.DOMAIN+"/employ/baseNumUpdate";
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                var jData = res.content;
                if ($.util.isArray(jData)) {

                    hot6.loadData(jData);
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