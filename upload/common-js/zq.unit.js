$(function() {

    // 全选
    $("#checkboxAll").click(function(){
        obj = document.getElementsByName("unitCheckbox");
        if ($("#checkboxAll").attr("checked")) {
            for(k in obj){
                if(!obj[k].checked)
                    obj[k].checked = true;
            }
        } else {
            for(k in obj){
                if(obj[k].checked)
                    obj[k].checked = false;
            }
        }
    });
    $("#unitCancelCheckbox").click(function(){
        obj = document.getElementsByName("unitCancelCheckbox");
        if ($("#unitCancelCheckbox").attr("checked")) {
            for(k in obj){
                if(!obj[k].checked)
                    obj[k].checked = true;
            }
        } else {
            for(k in obj){
                if(obj[k].checked)
                    obj[k].checked = false;
            }
        }
    });
    // 添加
    $("#addUnit").click(function(){
        //$("#cid").val('');
        //$('#custom_form_add')[0].reset();
        $("#modal-add-event").modal({show:true});
        $.ajax({
            url: GLOBAL_CF.DOMAIN+'/makeSalary/ajaxUnit',
            type: 'post',
            dataType: 'json',
            data: {
            },
            success: function (data) {
                if (data.status == 100000) {
                    $("#addUnitBody").html('');
                    var c_data = data.content;
                    if (c_data.length==0) {
                        $("#addUnitBody").html('<tr><td colspan="7">没有符合条件记录</td></tr>');
                    } else {
                        for (var i =0; i < c_data.length; i++) {
                            var val = c_data[i];
                            var html = '<tr><td><input type="checkbox" name="unitCheckbox" value="'+val.id+'"></td>';
                            html += '<td><div>'+val.id+'</div></td>';
                            html += '<td><div>'+val.name+'</div></td>';
                            html += '</tr>';
                            $("#addUnitBody").append(html);
                        }
                    }

                } else {
                    alert(data.content);
                }
            }
        });
    });

    // 添加
    $("#addCompanySearch").click(function(){
        $.ajax({
            url: GLOBAL_CF.DOMAIN+'/makeSalary/ajaxUnit',
            type: 'post',
            dataType: 'json',
            data: {
                'name' : $("#companySearch").val()
            },
            success: function (data) {
                if (data.status == 100000) {
                    $("#addUnitBody").html('');
                    var c_data = data.content;
                    if (c_data.length==0) {
                        $("#addUnitBody").html('<tr><td colspan="7">没有符合条件记录</td></tr>');
                    } else {
                        for (var i =0; i < c_data.length; i++) {
                            var val = c_data[i];
                            var html = '<tr><td><input type="checkbox" name="unitCheckbox" value="'+val.id+'"></td>';
                            html += '<td><div>'+val.id+'</div></td>';
                            html += '<td><div>'+val.name+'</div></td>';
                            html += '</tr>';
                            $("#addUnitBody").append(html);
                        }
                    }

                } else {
                    alert(data.content);
                }
            }
        });
    });

    // 添加管理
    $("#addUnitAjax").click(function(){
        obj = document.getElementsByName("unitCheckbox");
        check_val = [];
        for(var k in obj){
            if(obj[k].checked)
                check_val.push(obj[k].value);
        }
        var id = check_val;
        $.ajax({
            url: GLOBAL_CF.DOMAIN+'/makeSalary/ajaxAddUnit',
            type: 'post',
            dataType: 'json',
            data: {
                id : id
            },
            success: function (data) {
                if (data.status == 100000) {
                    alert(data.content);
                    window.location.reload();
                } else {
                    alert('未添加成功的记录编号：'+data.content);
                }
            }
        });
    });

    // 取消管理
    $("#cancelUnit").click(function(){
        obj = document.getElementsByName("unitCancelCheckbox");
        check_val = [];
        for(k in obj){
            if(obj[k].checked)
                check_val.push(obj[k].value);
        }
        var id = check_val;
        $.ajax({
            url: GLOBAL_CF.DOMAIN+'/makeSalary/cancelUnit',
            type: 'post',
            dataType: 'json',
            data: {
                id : id
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

});