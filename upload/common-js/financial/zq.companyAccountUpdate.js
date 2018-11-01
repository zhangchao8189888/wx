$(function () {
    var container = document.getElementById("excelGrid"),hot5;
    hot5 = Handsontable(container, {
        data: [],
        startRows: 5,
        startCols: 7,
        rowHeaders: true,
        minSpareRows: 10,
        contextMenu: true,
        comments: true,
        colHeaders: ['公司名称','公司余额','修改结果'],
        manualColumnResize: true,
        manualRowResize: true,
        autoWrapRow: true,
        columns: [
            {   data: "com_name",
                type: 'text'
            },
            {   data: "account_val",
                type: 'text'
            },
            {   data: "result_msg",
                type: 'text',
                renderer:customRenderer
            }
        ]
    });
    function customRenderer(instance, td, row, column, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);

        if (value == '修改成功') {
            td.style.color = 'green';
        } else if (value == '余额非数字类型' ||value == '公司名称未查询到' ) {
            td.style.color = 'red';
        } else if (value == '修改值和原来一致') {
            td.style.color = 'yellow';
        }
        else {
            td.style.color = 'withe';
        }

        return td;
    }
    var submit = 0;
    $("#batchUpdate").click(function () {
        if (submit == 1) {
            alert("不能重复提交！");
            return;
        }
        var data = hot5.getData();
        if (data.length < 0) {
            return;
        }
        submit = 1;
        var url = GLOBAL_CF.DOMAIN + "/financial/updateAccountValAjax";
        var formData = {
            isUpdate: $("#isUpdate").prop("checked"),
            "data": JSON.stringify(data)
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
                    var res_content = res.content;
                    if (res_content.errorList && res_content.errorList.length > 0) {
                        res_content = res_content.errorList;
                        for (var val = 0; val < res_content.length; val++) {
                            var succ = res_content[val];
                            hot5.setDataAtRowProp(val, 'result_msg', succ, "updateData");


                        }
                    }
                }
            },
            error: function () {
                submit = 0;
                console.log('Save error');
            },
            complete: function () {
                submit = 0;
            }
        });
    });
});