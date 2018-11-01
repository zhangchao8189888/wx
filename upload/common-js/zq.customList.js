/**
 * Created by zhangchao8189888 on 16-3-24.
 */
$(function() {
    var custom_data = {};
    custom_data.dateRangs = [];
    $("#custom_form_add").validate({
        onsubmit:true,
        submitHandler:function(form){
            var obj = {};
            obj.customer_name = $("#customer_name").val();
            obj.customer_principal = $("#customer_principal").val();
            obj.customer_principal_phone = $("#customer_principal_phone").val();
            obj.customer_address = $("#customer_address").val();
            obj.canbaojin = $("#canbaojin").val();
            obj.service_fee = $("#service_fee").val();
            obj.remark = $("#remark").val();
            obj.date_rang_json = custom_data.dateRangs;
            obj.op_id = $("#op_id").val();;
            if ($("#cid").val()) {
                obj.id = $("#cid").val();
            }
            $.ajax(
                {
                    type: "POST",
                    url: GLOBAL_CF.DOMAIN+'/dispatch/addOrUpdateCustom',
                    data: obj,
                    dataType:'json',
                    success: function(data){
                        if (data.status > 100000) {
                            alert(data.content);
                            return;
                        }
                        window.location.reload();
                    }
                }
            );
        },
        rules: {
            customer_name: { required: true }
        },
        messages: {
            customer_name:
            {
                required: '必填'
            }
        },
        errorClass: "help-inline",
        errorElement: "span",
        highlight:function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('success');
            $(element).parents('.control-group').addClass('error');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).parents('.control-group').removeClass('error');
            $(element).parents('.control-group').addClass('success');
        }


    });
    // 添加
    $("#add_btn").click(function(){
        $("#cid").val('');
        $('#custom_form_add')[0].reset();
        $("#modal-add-event").modal({show:true});
    });
    $(".date_rang_add").click(function () {
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        if (start_date == '' || end_date == '') {
            return;
        }
        var val = start_date+' - '+end_date;
        if (custom_data.dateRangs.indexOf(val) >= 0) {
            return;
        }
        var html = '<div class="controls">'+start_date+' - '+end_date+'<a style="cursor:pointer" class="date_rang_del" data-val="'+val+'">删除</a></div>';
        $(".date-rang").append(html);
        $("#start_date").val('');
        $("#end_date").val('');
        custom_data.dateRangs.push(val);
    });
    $('.date_rang_del').live('click',function () {

        custom_data.dateRangs.remove($(this).attr("data-val"));
        $(this).parent('div').remove();
    });

    // 编辑
    $('.edit_btn').click(function () {
        $('#custom_form_add')[0].reset();

        $('#modal-add-event').modal({show:true});
        var id = $(this).attr('data-id');
        $('#cid').val(id);
        $.ajax({
            url: GLOBAL_CF.DOMAIN+'/dispatch/getCustom',
            type: 'post',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (data) {
                if (data.status == 100000) {
                    $(".date-rang").html('');
                    var c_data = data.content;
                    $("#customer_name").val(c_data.customer_name);
                    $("#customer_principal").val(c_data.customer_principal);
                    $("#customer_principal_phone").val(c_data.customer_principal_phone);
                    $("#customer_address").val(c_data.customer_address);
                    $("#canbaojin").val(c_data.canbaojin);
                    $("#service_fee").val(c_data.service_fee);
                    $("#remark").val(c_data.remark);
                    $("#op_id").val(c_data.op_id);
                    custom_data.dateRangs = [];
                    for (var i =0; i < c_data.date_rang_json.length; i++) {
                        var val = c_data.date_rang_json[i];
                        var html = '<div class="controls">'+val+'<a style="cursor:pointer" class="date_rang_del" data-val="'+val+'">删除</a></div>';
                        $(".date-rang").append(html);
                        custom_data.dateRangs.push(val);
                    }
                } else {
                    alert(data.content);
                }
            }
        });

    });
    $("#display").click(function () {
        $(".cus_detail").hide();
    });
    $('.check_btn').click(function () {
        var id = $(this).attr('data-id');
        $(".cus_detail").show();
        $('#cid').val(id);
        $.ajax({
            url: GLOBAL_CF.DOMAIN+'/dispatch/getCustom',
            type: 'post',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (data) {
                if (data.status == 100000) {
                    var c_data = data.content;
                    $("#customer_name_text").html(c_data.customer_name);
                    $("#customer_principal_text").html(c_data.customer_principal);
                    $("#customer_principal_phone_text").html(c_data.customer_principal_phone);
                    $("#customer_address_text").html(c_data.customer_address);
                    $("#canbaojin_text").html(c_data.canbaojin);
                    $("#service_fee_text").html(c_data.service_fee);
                    $("#remark_text").html('<p>'+c_data.remark+'</p>');
                    $("#date_rang_text").html('');
                    for (var i =0; i < c_data.date_rang_json.length; i++) {
                        var val = c_data.date_rang_json[i];

                        var text = '';
                        if ((i+1) == c_data.date_rang_json.length) {
                            text = '当前合同期限：';
                        } else {
                            text = '第'+parseInt(i+1)+'次合同期限：';
                        }
                        var html = '<div class="controls">'+text+val+'</div>';
                        $("#date_rang_text").append(html);
                    }
                } else {
                    alert(data.content);
                }
            }
        });
    });

});