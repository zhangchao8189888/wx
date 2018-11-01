/**
 * Created by zhangchao8189888 on 16/9/22.
 */
$(function (){

    var
        container = document.getElementById('excelGrid'),position_val,
        employGrid;
    function createBigData() {
        var e_name = $("#e_name").val();
        var e_num = $("#e_num").val();
        var company_id = $("#e_company_id").val();

        $.ajax(
            {
                type: "post",
                url:GLOBAL_CF.DOMAIN+"/employ/getEmployListAjax",
                data: {
                    company_id:company_id,
                    e_name:e_name,
                    e_num:e_num

                },
                dataType: "json",
                success: function(data){
                    if (data.status == 100001) {
                        //alert(data.content);
                        return;
                    }
                    var jData = data.content;
                    employGrid.loadData(jData);
                    /*employGrid.updateSettings({
                        contextMenu: {
                            callback: function (key, options) {
                                if (key === 'about') {
                                    setTimeout(function () {
                                        //timeout is used to make sure the menu collapsed before alert is shown
                                        alert("This is a context menu with default and custom options mixed");
                                    }, 100);
                                }
                            },
                            items: {
                                "remove_row": {
                                    name: '删除行'
                                },
                                "editEmploy": {name: '编辑'},
                                "addEmploy": {name: '添加'}
                            }
                        }
                    })*/

                }
            }
        );
    }

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
    var changeList = {};
    employGrid = Handsontable(container, {
        data: createBigData(),
        startRows: 5,
        startCols: 9,
        minSpareRows: 1,
        rowHeaders: true,
        contextMenu: {
            callback: function (key, options) {
                if (key === 'editEmploy') {
                    var row = options.start.row;
                    var oData = this.getSourceDataAtRow(row);
                    $("#row_id").val(oData.id);
                    $("#employ_name").val(oData.e_name);
                    $("#employ_num").val(oData.e_num);
                    $("#e_type_name").val();
                    var e_type = GLOBAL_DATA.E_TYPE_VAL_LIST[oData.e_type_name];
                    $("#e_type").val(e_type);
                    $("#bank_name").val(oData.bank_name);
                    $("#bank_num").val(oData.bank_num);
                    $("#shebaojishu").val(oData.shebaojishu);
                    $("#gongjijinjishu").val(oData.gongjijinjishu);
                    $("#laowufei").val(oData.laowufei);
                    $("#canbaojin").val(oData.canbaojin);
                    $("#danganfei").val(oData.danganfei);
                    $("#memo").val(oData.memo);
                    $("#employ_name").attr("readonly",true);
                    $("#employ_num").attr("readonly",true);
                    $('#modal-add-event').modal({show:true});
                } else if (key === 'addEmploy') {
                    $("#employ_name").removeAttr("readonly");
                    $("#employ_num").removeAttr("readonly");
                    $("#social_form_add")[0].reset();
                    $('#modal-add-event').modal({show:true});
                }
            },
            items: {
                "remove_row": {
                    name: '删除行'
                },
                "editEmploy": {name: '编辑'},
                "addEmploy": {name: '添加'}
            }
        },
        beforeRemoveRow:function (index,amount) {
            if(window.confirm('你确定要修改此数据吗？')){

            }else{

                return false;
            }
            var rowData = this.getSourceDataAtRow(index);
            if (!rowData.id) return false;
            var delId = rowData.id;
            //删除
            var url = GLOBAL_CF.DOMAIN+"/employ/delEmploy";
            var formData = {
                ids: delId
            }
            $.ajax({
                url: url,
                data: formData, //returns all cells' data
                dataType: 'json',
                type: 'POST',
                success: function (res) {
                    if (res.status == 100001) {
                        alert(res.content);
                    } else {
                        alert(res.content);
                    }
                },
                error: function () {
                    console.text('Save error');
                }
            });
        },
        comments: true,
        colHeaders: ['所属单位',	'员工姓名	','身份证号','户口性质','开户行','银行卡号','社保基数','公积金基数','劳务费','残保金','档案费','备注'],
        manualColumnResize: true,
        manualRowResize: true,
        columns: [
            {data: "e_company",readOnly:true},//0
            {data: "e_name"},//0
            {data: "e_num",readOnly:true},//2
            {data: "e_type_name",readOnly:true},//3
            {data: "bank_name"},//4
            {data: "bank_num"},//5
            {data: "shebaojishu",readOnly:true},//7
            {data: "gongjijinjishu",readOnly:true},//6
            {data: "laowufei"},//6
            {data: "canbaojin"},//6
            {data: "danganfei"},//6
            {data: "memo"}//6
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
                    changeList[row] = rowData;
                    if (!changeList.length) {
                        changeList.length = 0;
                    }
                    changeList.length++;
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
            var url = GLOBAL_CF.DOMAIN+"/employ/updateEmployList";
            $.ajax({
                url: url,
                data: formData, //returns all cells' data
                dataType: 'json',
                type: 'POST',
                success: function (res) {
                    if (res.code > 100000) {
                        //changeList = {};
                        alert('保存失败，请重试');
                        createBigData();
                    }
                },
                error: function () {
                    alert('保存失败，请重试');
                }
            });

        }

    });
    $("#e_company_id").change(function () {
        var company_id = $("#e_company_id").val();
        if (company_id == 0) {
         return [];
         }
        createBigData();
    });
    $("#search_btn").click(function () {
        createBigData();
    });
    $("#import_btn").click(function () {
        location.href = "";
    });

    $(".add_btn").click(function () {

        var formData = {
            row_id: $("#row_id").val(),
            e_company_id: $("#e_company_id").val(),
            e_num: $("#employ_num").val(),
            e_name: $("#employ_name").val(),
            e_company_id: $("#e_company_id").val(),
            employ_name: $("#employ_name").val(),
            employ_num: $("#employ_num").val(),
            e_type: $("#e_type").val(),
            bank_name: $("#bank_name").val(),
            bank_num: $("#bank_num").val(),
            shebaojishu: $("#shebaojishu").val(),
            gongjijinjishu: $("#gongjijinjishu").val(),
            laowufei: $("#laowufei").val(),
            canbaojin: $("#canbaojin").val(),
            danganfei: $("#danganfei").val(),
            memo:$("#memo").val()
        }
        var url = GLOBAL_CF.DOMAIN+"/employ/saveEmploy";
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',

            success: function(res){
                if (res.status == 100001) {
                    alert(res.content);
                } else {
                    alert(res.content);
                    createBigData();

                    $('#modal-add-event').modal("hide");
                }
            },
            error: function () {
                alert('保存失败，请重试');
            }
        });
    });
});