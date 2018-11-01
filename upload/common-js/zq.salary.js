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
    var container = document.getElementById("exampleGrid");
    var hot5 = Handsontable(container, {
        //data: [],
        stretchH: 'last',
        startRows: 5,
        minSpareRows: 1,
        colHeaders: [],
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
        minSpareRows: 1,
        colWidths: [], //can also be a number or a function
        rowHeaders: true,
        colHeaders: [],
        stretchH: 'last',
        manualColumnResize: true,
        manualRowResize: true,
        readOnly:true,
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
    $('.sumFirst').click(function () {
        var type=$(this).attr("data-type");
        if(type==1){
           var url="/sumSalary";
            var objData={
                shenfenzheng : $("#shenfenzheng").val(),
                add : $("#add").val(),
                del : $("#del").val(),
                freeTex : $("#freeTex").val(),
                data: hot5.getData()
            };
        }else if(type==3){
            var url="/nianSalary";
            var objData={
                shenfenzheng_nian:$("#shenfenzheng_nian").val(),
                salaryTime_nian:$("#salaryTime_nian").val(),
                nian:$("#nian_type").val(),
                isFirst:$("#isFirst").val(),
                data: hot5.getData()
            };
        }
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
    $('#sumSecond').click(function () {
        $.ajax({
            url: GLOBAL_CF.DOMAIN+"/salary/sumErSalary",
            data: {
                shenfenzheng_er : $("#shenfenzheng_er").val(),
                salaryTime_er : $("#salaryTime_er").val(),
                add_er : $("#add_er").val(),
                data: hot5.getData()}, //returns all cells' data
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
        $("#company_id").val($("e_company_id").val());
    });
    $("#salarySave").click(function () {
        if ($("#company_id").val() == 0) {
            alert("请选择单位！");
            return;
        }
        var data = hot6.getData();
        if (data.length < 0) {
            return;
        }
        var formData = {};
        if ($("#change").val() == 1) {
            var url = GLOBAL_CF.DOMAIN+"/salary/saveSalary";
            formData = {
                "data": data,
                company_id: $("#company_id").val(),
                salaryDate: $("#salary_date").val(),
                mark:  $("#mark").val(),
                excelHead:  excelHead,
                excelMove : excelMove
            }
        } else if ($("#change").val() == 3) {
            var url = GLOBAL_CF.DOMAIN+"/salary/saveErSalary";
            formData = {
                "data": data,
                company_id: $("#company_id").val(),
                salaryDate: $("#salary_date").val(),
                mark:  $("#mark").val(),
                excelHead:  excelHead,
                excelMove : excelMove
            }
        }
        $.ajax({
            url: url,
            data: formData, //returns all cells' data
            dataType: 'json',
            type: 'POST',
            success: function (res) {
                if (res.code > 100000) {
                    console.text('Data saved');
                    alert(res.mess);
                    return;
                }
                else {
                    alert(res.content);
                    //window.location.href = "index.php?action=Salary&mode=salarySearchList";
                }
            },
            error: function () {
                console.text('Save error');
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
    //createBigData();
});/**
 * Created by zhangchao8189888 on 15-1-3.
 */
