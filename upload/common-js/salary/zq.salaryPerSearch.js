/**
 * Created by zhangchao8189888 on 16/11/13.
 */
$(document).ready(function () {
    var first = document.getElementById("firstGrid");
    var er = document.getElementById("erGrid");
    var nian = document.getElementById("nianGrid");
    var firstGride = Handsontable(first, {
        data: [],
        rowHeaders: true,
        colHeaders: true,
        manualColumnResize: true,
        manualRowResize: true,
        readOnly:true,
        minSpareRows: 1,
        contextMenu: true
    });
    var erGride = Handsontable(er, {
        data: [],
        rowHeaders: true,
        colHeaders: true,
        manualColumnResize: true,
        manualRowResize: true,
        readOnly:true,
        minSpareRows: 1,
        contextMenu: true
    });
    var nianGride = Handsontable(nian, {
        data: [],
        rowHeaders: true,
        colHeaders: true,
        manualColumnResize: true,
        manualRowResize: true,
        readOnly:true,
        minSpareRows: 1,
        contextMenu: true
    });
    var selectFirst = document.getElementById('selectFirst'),
        rowHeaders = document.getElementById('rowHeaders'),
        colHeaders = document.getElementById('colHeaders');

    Handsontable.Dom.addEvent(colHeaders, 'click', function () {
        if (this.checked) {
            firstGride.updateSettings({
                fixedColumnsLeft: 2
            });
        } else {
            firstGride.updateSettings({
                fixedColumnsLeft: 0
            });
        }

    });

    $("#search_btn").click(function () {
        createBigData();
    });
    $(".checkPerson").live("click",function () {
        var e_num=$(this).find("td").eq(3).text();
        $(".checkPerson").removeAttr("style");
        $(this).css("color","red");
        $("#e_num_search").val(e_num);
    });
    function createBigData() {
        var e_name = $("#e_name").val();
        var e_num = $("#e_num").val();
        var company_id = $("#e_company_id").val();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();

        $.ajax(
            {
                type: "post",
                url:GLOBAL_CF.DOMAIN+"/makeSalary/getPerSalaryAjax",
                data: {
                    company_id:company_id,
                    e_name:e_name,
                    start_date:start_date,
                    end_date:end_date,
                    e_num:e_num

                },
                dataType: "json",
                success: function(data){


                    var status=data.status;
                    if(status==200001){
                        var res=data.content;
                        if(res=="" || res==null){
                            alert("暂无结果");
                            return false;
                        }
                        $('#modal-add-event2').modal({show:true});
                        var tr='<table border="1" borderColor="grey" cellpadding="5" width="98%">';
                        tr+="<tr>";
                        tr+=" <td>公司id</td>";
                        tr+=" <td>公司名称</td>";
                        tr+="<td>姓名</td>";
                        tr+="<td>身份证</td>";
                        tr+="</tr>";
                        $.each(res,function (i,row) {
                            tr+="<tr class='checkPerson'>";
                            tr+="<td>"+row['e_company_id']+"</td>";
                            tr+="<td>"+row['e_company']+"</td>";
                            tr+="<td>"+row['e_name']+"</td>";
                            tr+="<td>"+row['bank_num']+"</td>";
                            tr+="</tr>";
                        });

                        tr+="</table>";
                        $("#check").html(tr);
                    }else{
                        var jData = data.content;
                        var head = jData.header;
                        var firstList = jData.firstSalaryList;
                        var erList = jData.erSalaryList;
                        var nianList = jData.nianSalaryList;

                        firstGride.updateSettings(
                            {
                                colHeaders:head.first
                            }
                        );
                        firstGride.loadData(firstList);
                        erGride.updateSettings(
                            {
                                colHeaders:head.er
                            }
                        );
                        erGride.loadData(erList);
                        nianGride.updateSettings(
                            {
                                colHeaders:head.nian
                            }
                        );
                        nianGride.loadData(nianList);
                    }

                }
            }
        );
    }
});