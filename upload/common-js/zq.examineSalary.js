$(function(){
    // 处理审核
    $(".dealExamine").click(function(){
        var status = $(this).attr("data-status");
        if (status == 1) {
            $("#modal-deal-event").modal({show:true});
            $("#dealActId").val($(this).attr("data-id"));
        } else {
            return false;
        }
    });
    $(".dealAct").click(function(){
        var val = $(this).attr("data-val");
        var type = $(this).attr("data-type");
        $.ajax({
            url: GLOBAL_CF.DOMAIN+'/financial/dealExamine',
            type: 'post',
            dataType: 'json',
            data: {
                id : $("#dealActId").val(),
                type : type,
                val: val
            },
            success: function (data) {
                if (data.status == 100000) {
                    alert(data.content);
                    window.location.reload();
                } else {
                    alert(data.content);
                }
            }
        });
    });
    $(".toDetail").click(function () {
        var id = $(this).attr("data-id");
        var name = $(this).attr("data-name");
        location.href = "/financial/salaryAccountDetail?companyId="+id+"&companyName="+name;
    });
    $("#sortByStatus").change(function () {
        var sortVal = $(this).val();
        var type = $(this).attr("date-type");
        var url = "";
        if (type == 'first') {
            url = "examineSalary";
        } else if (type == 'er') {
            url = "examineErSalary";
        } else if (type == 'nian') {
            url = "examineNianSalary";
        }
        location.href = "/financial/"+url+"?sort="+sortVal;
    });
});