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
                    isReduce:"reduce",
                    time:time,
                    type : "gjjin"

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
    hot5 = Handsontable(container, {
        data: createBigData(),
        startRows: 5,
        startCols: 9,
        rowHeaders: true,
        colHeaders: true,
        minSpareRows: 1,
        comments: true,
        colHeaders: ['所属单位',	'单位名称	','员工姓名	','身份证号','户口性质','	社保基数','是否新参',
            '户口所在地','备注','减员时间'],
        manualColumnResize: true,
        manualRowResize: true,
        columns: [
            {data: "belong_company_name",readOnly:true},//0
            {data: "company_name",readOnly:true},//0
            {data: "e_name",readOnly:true},//1
            {data: "e_num",readOnly:true},//2
            {data: "e_type",readOnly:true},//3
            {data: "e_social_base",readOnly:true},//4
            {data: "is_new_social",readOnly:true},//5
            {data: "e_address",readOnly:true},//7
            {data: "e_memo",readOnly:true},//6
            {data: "date",readOnly:true}//6
        ]

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
                isReduce:"reduce",
                time:time,
                companyID:companyID,
                type : "gjjin"
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
});