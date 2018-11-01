/**
 * Created by Administrator on 2015/4/14.
 */
$(function(){
    $(".search-mobile").click(function(){
        var uid = $(this).attr('data-id');
        var flag = $(this).attr('data-status');
        $.ajax({
            type: 'post',
            url: '/user/modifyUser',
            dataType: 'json',
            data: {
                uid : uid,
                flag : flag
            },
            success: function(data){
                if (data.status > 100000) {

                    alert('修改失败！');
                } else {
                    alert('修改成功！');
                    window.location.reload();
                }
            },

            error: function(XHR, textStatus, errorThrown){
                alert('服务器没有响应，请稍后重试');
            }
        });
    });


});
