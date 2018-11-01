$(function() {
    $(".edit_btn").click(function(){
        var id = $(this).attr('data-id');
        $.ajax({
            type: "post",
            url:GLOBAL_CF.DOMAIN+"/power/ajaxGetGroup",
            data:{
              id:id
            },
            dataType: "json",
            success: function(data){
                if (data.status && data.status == 100001) {
                    alert(data.content);
                    return;
                }else{
                    var group=data.content;
                    var r="<select id='checkGroup'>";
                    $.each(group,function (i,row) {
                        var select="";
                        if(row["id"]==row['group_id']){
                            var select="selected";
                        }
                        r+="<option "+select+" value="+row["id"]+">";
                        r+=row["title"];
                        r+='</option>';
                    });
                    r+="</select>";
                    r+='<input type="hidden" id="personID" value="'+id+'">';
                    $(".controls").html(r);
                }
            }
        });

        $("#modal-edit-event").modal({show:true});
    });
    //保存
    $(".btn_edit ").click(function () {
        var personID=$("#personID").val();
        var groupID=$("#checkGroup").val();
        $.ajax({
            type: "post",
            url:GLOBAL_CF.DOMAIN+"/power/saveGroup",
            data:{
                personID:personID,
                groupID:groupID
            },
            dataType: "json",
            success: function(data){
                if (data.status && data.status == 100001) {
                    alert(data.content);
                    return;
                }else{
                    alert(data.content);
                    window.location.reload();
                }
            }
        });
    });
});