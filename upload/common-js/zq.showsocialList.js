/**
 * Created by zhangchao8189888 on 16-4-9.
 */
$(function () {
    var fontWeight = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        //td.style.fontWeight = 'bold';
        td.style.color = '#7057FD';
    };
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
        var time = $("#search_time").val()+"-01";
        $.ajax(
            {
                type: "post",
                url:GLOBAL_CF.DOMAIN+"/social/getData",
                data: {
                    isReduce:"add",
                    time:time,
                    type : "social"

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
    var changeList = {},
    hot5 = Handsontable(container, {
        data: createBigData(),
        startRows: 5,
        startCols: 9,
        rowHeaders: true,
        colHeaders: true,
        minSpareRows: 1,
        comments: true,
        colHeaders: ['所属单位',	'单位名称	','员工姓名	','身份证号','户口性质','	社保基数','是否新参',
            '户口所在地','备注','增员时间'],
        manualColumnResize: true,
        manualRowResize: true,
        columns: [
            {data: "belong_company_name",readOnly:true},//0
            {data: "company_name",readOnly:true},//0
            {data: "e_name",readOnly:true},//1
            {data: "e_num",readOnly:true},//2
            {data: "e_type",readOnly:true},//3
            {data: "e_social_base",renderer:fontWeight},//4
            {data: "is_new_social",renderer:fontWeight},//5
            {data: "e_address",readOnly:true},//7
            {data: "e_memo",renderer:fontWeight},//6
            {data: "date",readOnly:true}//6
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
                }
            }
        }
    });
    $("#add_btn").click(function () {
        var data = hot5.getData();
        var date = $("#produce_date").val();
        var formData = {
            data: data,
            date:date
        };
        var url = GLOBAL_CF.DOMAIN+"/social/saveSocialList";
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                console.log(res);
            },
            error: function () {
                alert('保存失败，请重试');
            }
        });
    });
    //按公司和时间查询
    $("#search_btn").click(function () {
        var companyID = $("#e_company_id").val();
        if($("#noTime").parent().attr("class")=="checked"){
            var time = "";
        }else{
            var time = $("#search_time").val()+"-01";
        }
        $.ajax({
            url: GLOBAL_CF.DOMAIN+"/social/getData",
            data: {
                isReduce:"add",
                time:time,
                companyID:companyID,
                type : "social"
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
    /**
     * 保存数据用的
     */
    $("#save_btn").click(function () {
        var hasProp = false;
        if(changeList) {
            //
            for (var prop in changeList){
                hasProp = true;
                break;
            }
            if (!hasProp){
                alert("没有要保存的");
                return;
            }

        }
        var formData = {
            type : "social",
            data: changeList
        };
        var url = GLOBAL_CF.DOMAIN+"/social/ajaxUpdateSocial";
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.status > 100000) {
                    var error_list = res.content.error_list;
                    if (error_list && error_list.length > 0) {
                        alert(error_list[0].message);
                    }
                } else {
                    alert(res.content);

                }

                changeList = {};
                createBigData();
            },
            error: function () {
                alert('保存失败，请重试');
            }
        });
    });
});