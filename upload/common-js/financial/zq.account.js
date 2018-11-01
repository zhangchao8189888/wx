/**
 * Created by zhangchao8189888 on 16/10/7.
 */

$(function(){
    $(".toDetail").click(function () {
        var id = $(this).attr("data-id");
        var name = $(this).attr("data-name");
        location.href = "/financial/salaryAccountDetail?companyId="+id+"&companyName="+name;
    });
});