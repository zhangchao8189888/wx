/**
 * Created by zhangchao8189888 on 15-4-21.
 */
$(function (){
    $("#pro_add").click(function(){
        $('#modal-add-event').modal({show:true});
        $("#pro_form")[0].reset();
    });
    $(".btn_publish").click(function(){
        var id = $("#product_id").val();
        $.ajax(
            {
                type: "post",
                url: "/product/productPublish",
                data: {
                    id:id
                },
                dataType: "json",
                success: function(data){

                    if (data.status > 100000) {

                        alert('发布失败！');
                    } else {
                        alert('发布成功！');
                        window.location.reload();
                    }
                }
            }
        );
    });
    // 改变状态
    $(".pub_modify").click(function(){
        var id = $(this).attr('data-id');
        var publish_status = $(this).attr('data-status');
        $.ajax({
            type: 'post',
            url: '/product/modifyProduct',
            dataType: 'json',
            data: {
                id : id,
                publish_status : publish_status
            },
            success: function(data){
                if (data.status > 100000) {

                    alert('修改状态失败！');
                } else {
                    alert('修改状态成功！');
                    window.location.reload();
                }
            },

            error: function(XHR, textStatus, errorThrown){
                alert('服务器没有响应，请稍后重试');
            }
        });
    });
    //产品推送
    $('.pub_push').click(function(){
        $('#modal-push-event').modal({show:true});
        var id = $(this).attr('data-id');
        $('#push_id').val(id);
        $.ajax({
            type: 'post',
            url: '/product/getPush',
            dataType: 'json',
            data: {
                id : id
            },
            success: function(data){
                if(data.status > 100000){
                    alert(data.content);
                } else{
                    var push_list = data.content;
                    var publish_personal = push_list.publish_personal;
                    var publish_index = push_list.publish_index;
                    if(publish_personal == 1){
                        var one =$("#checkbox_per_id").attr("checked",true);
                        $.uniform.update(one);
                    } else {
                        var one =$("#checkbox_per_id").attr("checked",false);
                        $.uniform.update(one);
                    }
                    if(publish_index == 1){
                        var tow = $("#checkbox_index_id").attr("checked",true);
                        $.uniform.update(tow);
                    }else{
                        var tow = $("#checkbox_index_id").attr("checked",false);
                        $.uniform.update(tow)
                    }
                }
            },
            error:function(){
                alert('error123!');
            }

        });
    });
    $('.btn_push').click(function(){
        var id = $('#push_id').val();
        if($('#checkbox_per_id').is(':checked')){
            var personal = $('#checkbox_per_id').val();
        }else{
            var personal = 0;
        }
        if($('#checkbox_index_id').is(':checked')){
            var index = $('#checkbox_index_id').val();
        }else{
            var index = 0;
        }
        $.ajax({
                type: 'post',
                url: '/product/pushProduct',
                dataType: 'json',
                data: {
                    id : id,
                    publish_personal : personal,
                    publish_index : index
                },
                success: function(data){
                    if (data.status > 100000) {

                        alert('推送失败！');
                    } else {
                        alert('推送成功！');
                        window.location.reload();
                    }
                },

                error: function(XHR, textStatus, errorThrown){
                    alert('服务器没有响应，请稍后重试');
                }
        });
    });
});
