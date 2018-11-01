/**
 * Created by zhangchao8189888 on 16/8/6.
 */
Array.prototype.remove=function(obj){
    for(var i =0;i <this.length;i++){
        var temp = this[i];
        if(!isNaN(obj)){
            temp=i;
        }
        if(temp == obj){
            for(var j = i;j <this.length;j++){
                this[j]=this[j+1];
            }
            this.length = this.length-1;
        }
    }
}
$(document).ready(function () {
    $("#shenfenzheng").tagsinput({
        itemValue: 'id',
        itemText: 'text'
    });
    $("#add").tagsinput({
        itemValue: 'id',
        itemText: 'text'
    });
    $("#del").tagsinput({
        itemValue: 'id',
        itemText: 'text'
    });
    $("#freeTex").tagsinput({
        itemValue: 'id',
        itemText: 'text'
    });
    function createBigData() {
        var fileName = $('#fileName').val(),rows;
        var company_id = $("#company_id").val();
        var salaryDate = $("#salaryDate").val();
        $.ajax(
            {
                type: "get",
                url: "index.php?action=Salary&mode=getFileContentJson",
                data: {
                    fileName : fileName,
                    company_id : company_id,
                    salaryDate : salaryDate

                },
                dataType: "json",
                success: function(data){
                    var jData = data.data;
                    var head = data.head;
                    var header = [];
                    for(var i = 1;i <= jData[0].length; i++){
                        header.push(i);
                    }
                    hot5.updateSettings({
                        colHeaders: header
                    });
                    var sumWith = 100;

                    for (i =0;i < head.length;i++) {
                        sumWith+= head[i];
                    }
                    $('#exampleGrid').css('width',sumWith);
                    hot5.updateSettings({
                        colWidths: head
                    });
                    hot5.loadData(jData);

                }
            }
        );
    }
    var position_val;
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
        minSpareRows: 0,
        contextMenu: true,
        colHeaders: [],
        afterChange: function (change, source) {
            if (source === 'loadData' || source === 'updateData' ) {
                return; //don't save this change
            }
            /*var row = parseInt(change[val][0]);
            var col = change[val][1];
            if (hot5.isEmptyRow(row)) {
                console.log(row+"是空行");
            }*/


        },
        rowHeights: function(){return 25;}
    });
    bindColumnSortingAfterClick.call(hot5);
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders'),
        reload = document.getElementById('reload');
    Handsontable.Dom.addEvent(reload,'click', function (){
        createBigData();
    });
    var redRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        Handsontable.renderers.TextRenderer.apply(this, arguments);
        td.style.backgroundColor = 'red';

    };
    var sumGrid = document.getElementById("sumGrid");
    var hot6 = Handsontable(sumGrid, {
        data: [],
        startRows: 5,
        startCols: 4,
        autoWrapRow: true,
        //minSpareRows: 1,
        //colWidths: [], //can also be a number or a function
        rowHeaders: true,
        colHeaders: [],
        //manualColumnResize: true,
        //manualRowResize: true,
        readOnly:true
        //contextMenu: true
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
    $('.sumFirst').click(function () {
        if (!$("#add").val() || !$("#shenfenzheng").val()) {
            alert("请选择添加项和身份证号！");
            return;
        }

        $.handsonTableFn.clearEmptyRowOrCol(hot5);
        var type=$(this).attr("data-type");
        var url="/sumSalary";
        var objData={
            shenfenzheng : $("#shenfenzheng").val(),
            add : $("#add").val(),
            del : $("#del").val(),
            freeTex : $("#freeTex").val(),
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
                    /*var colWidths = [];
                    for(var i = 0;i < excelHead.length; i++){
                        if (i == shenfenleibie) colWidths.push(160);
                        else if (i == excelHead.length-1) {colWidths.push(160);}
                        else {
                            colWidths.push(80);
                        }
                    }*/
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
                    /*hot6.updateSettings({
                        colWidths: colWidths
                    });*/
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
        $("#company_id").val($("#e_company_id").val());
    });
    $("#salarySave").click(function () {
        if ($("#companySearch").val() == '') {
            alert("请选择单位！");
            return;
        }
        var data = hot6.getData();
        if (data.length < 0) {
            return;
        }

        var url = GLOBAL_CF.DOMAIN+"/salary/saveSalary";
        var formData = {
            "data": JSON.stringify(data),
            companySearch: $("#companySearch").val(),
            salaryDate: $("#salary_date").val(),
            mark:  $("#mark").val(),
            excelHead:  excelHead,
            excelMove : JSON.stringify(excelMove)
        }
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.status > 100000) {
                    
                    alert(res.content);
                    return false;
                }
                else {
                    alert(res.content);
                    window.location.reload();
                    //window.location.href = "index.php?action=Salary&mode=salarySearchList";
                }
            },
            error: function () {
                console.log('Save error');
            }
        });
    });
    $("#e_company").on("click",function(){
        var input;
        var inputVal;
        var suggestWrap = $('#custor_search_suggest');
        var oSearchSelect = BaseWidget.UI.SearchSelect;
        oSearchSelect.fnInt();

        input = $(this);
        oSearchSelect.leftPlus = -185;
        oSearchSelect.topPlus = 64;
        oSearchSelect.inputWith = 314;
        oSearchSelect.url = 'index.php?action=Company&mode=getCompanyListJson';
        var fnHideSuggest = function(){
            var that = BaseWidget.UI.SearchSelect;
            that.inputVal = '';
            that.targetSuggestWrap.hide();
        }
        oSearchSelect.targetSuggestWrap = suggestWrap;
        oSearchSelect.fnHideSuggest = fnHideSuggest;
        oSearchSelect.fnMousedown = function (that,obj) {
            if (that.inputVal == obj.name) {
                that.fnHideSuggest();
            } else {
                //Customer.oCustomer.fnGetCustomerInfo(obj);
                //得到用户信息
                $("#e_company").val(obj.name);
                $("#company_id").val(obj.id);
            }
        }
        oSearchSelect.targetInput = input;
        input.click(function(e){
            oSearchSelect.fnSendKeyWord(e);
        });
        input.keyup(
            function (e) {
                oSearchSelect.fnSendKeyWord(e);
            }
        );
        input.blur(oSearchSelect.fnHideSuggest);
        if (input.val() == '') {
            oSearchSelect.fnSendKeyWord({});
        } else {
            inputVal = input.val();
        }
        oSearchSelect.inputVal = inputVal;
    });
    $("#use_last_month").click(function () {
        if ($("#companySelect").val() == "") {
            alert("请选择单位");
            return;
        }
        $.ajax({
            url: GLOBAL_CF.DOMAIN+"/salary/getLastMonthSalary",
            data: {
                company_id: $("#companySelect").val()
            },
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.status == '100000') {
                    hot5.loadData(res.content);
                }
                else {
                    alert(res.content);
                }
            },
            error: function () {
                console.log('Save error');
            }
        });
    });
    //createBigData();
});/**
 * Created by zhangchao8189888 on 15-1-3.
 */
