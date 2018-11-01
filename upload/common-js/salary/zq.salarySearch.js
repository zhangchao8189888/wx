/**
 * Created by zhangchao8189888 on 16/8/21.
 */
$(function () {
    $(".check").click(function () {

    });
    $(".rowDel").click(function () {
        var objData = {
            salaryTimeId : $(this).attr('data-id'),
            salaryType : $(this).attr('data-type')
        };
        $.ajax({
            url  : GLOBAL_CF.DOMAIN+"/salary/delSalaryAjax",
            data : objData,
            type : 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.status == 100000) {

                    alert(res.content);
                    location.reload();
                }
                else {
                    alert(res.content);
                }
            }
        });
    });
});