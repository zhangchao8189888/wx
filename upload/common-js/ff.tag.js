/**
 * Created by zhangchao8189888 on 15-4-16.
 */
$(function(){

    $(".rowDel").click(function(){
        var tag_id = $(this).attr("data-id");
        $.ajax(
            {
                type: "post",
                url: "/tags/tagDelete",
                data: {id:tag_id},
                dataType: "json",

                success: function(data) {

                    if (data.status > 100000) {

                        alert('删除失败！');
                    } else {
                        alert('删除成功！');
                        window.location.reload();
                    }
                }
            }
        );
    });

    $('#tag_add').click(function(){
        $('#form_add_tag')[0].reset();
        $('#modal-add-event13').modal({show:true});
    });
    $(".btn-add").click(function(){
        var tag_name = $('#tag_name').val();
        var tag_val = $('#tag_val').val();
        var tag_type = $('#tag_type').val();
        var parent_id = $('#add_parent_id').val();
        var tag_sort = $('#add_tag_sort').val();
        $.ajax(
            {
                type:"post",
                url: "/tags/addTag",
                data:{
                    tag_name : tag_name,
                    parent_id : parent_id,
                    tag_type : tag_type,
                    tag_val : tag_val,
                    tag_sort : tag_sort
                },
                dataType : "json",
                success:function(data){
                    if(data.status > 100000){

                        alert(data.content);
                    } else{
                        alert('添加成功！');
                        window.location.reload();
                    }
                }
            }
        );
    });

    $(".tag_sort").click(function(){
        $('#modal-event2').modal({show:true});
        var id = $(this).attr('data-id');
        var old_sort = $(this).attr('data-old_sort');
        $('#id').val(id);
        $('#new_sort').val(old_sort);
    });

    $(".btn_sort").click(function(){
        var id = $('#id').val();       //原纪录的id
        var new_sort = $('#new_sort').val();       //添加的sort值
        $.ajax(
            {
                type:"post",
                url: "/tags/tagSort",
                data:{
                    id: id,
                    new_sort: new_sort
                },
                dataType : "json",
                success:function(data){
                    if(data.status > 100000){
                        alert(data.content);
                    } else{
                        alert('修改排序成功！');
                        window.location.reload();
                    }
                }
            }
        );
    });
});