function MathRand() {
    var Num = "p";
    for (var i = 0; i < 6; i++) {
        Num += Math.floor(Math.random() * 10);
    }
    document.getElementById("type_code").innerText = Num;
    return Num;
};

$(function(){
    $("#com_add").click(function(){
        $('#company_validate')[0].reset();
        $('#modal-event1').modal({show:true});
        var  num = MathRand();
        $('#type_code').val(num);
    });
    $(".type_sort").click(function(){
        $('#modal-event2').modal({show:true});
        var id = $(this).attr('data-id');
        var old_sort = $(this).attr('data-sort');
        $('#id').val(id);
        $('#new_sort').val(old_sort);
    });

    $(".rowDelete").click(function(){
    var tid = $(this).attr("data-id");
    $.ajax(
    {
        type: "post",
        url: "/product/delete",
        data: {
            tid:tid
        },
        dataType: "json",
        success: function(data){

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

    $('.btn-add').click(function(){
        var type_name = $('#type_name').val();
        var type_code = $('#type_code').val();
        var type_desc = $('#type_desc').val();
        var type_sort = $('#add_type_sort').val();
        $.ajax(
            {
                type : "post",
                url : "/product/add",
                data : {
                    type_name : type_name,
                    type_code : type_code,
                    type_desc : type_desc,
                    type_sort : type_sort
                },
                dataType : "json",
                success: function(data){
                    if (data.status > 100000) {

                        alert(data.content);
                    } else{
                        alert('添加成功！');
                        window.location.reload();
                    }
                }
            }
        );
    });

    /**
     * 排序 pro_type_sort
     */
    $(".btn_sort").click(function(){
        var id = $('#id').val();       //原纪录的id
        var new_sort = $('#new_sort').val();       //添加的sort值
        $.ajax(
            {
                type:"post",
                url: "/product/proTypeSort",
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

