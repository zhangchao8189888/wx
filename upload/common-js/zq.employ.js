/**
 * Created by zhangchao8189888 on 15-6-12.
 */
    $(function(){

        var qc = {

            /*打开新窗口*/
            open: function (url, width, height, showscroll) {
                var s = "yes";
                if (showscroll == false)
                    s = "no";
                var l = Math.ceil((window.screen.width - width) / 2);
                var t = Math.ceil((window.screen.height - height) / 2); //确定网页的坐标
                return window.open(url, "_blank", "left=" + l + ",top=" + t + ",height=" + height + ",width=" + width + ",toolbar=no,status=no,resizable=yes,location=no,scrollbars=" + s);
            },
            /*打开url,自动居中,url的传入格式:/common/test.aspx,2011.4.9全部统一使用该方法*/
            openWindow: function (url, width, height, showscroll, addUrl) {
                if (!width) width = 900;
                if (!height) height = 600;
                if (!showscroll) showscroll = true;
                return qc.open(url, width, height, showscroll);
            }

        }
        $(".checkInfo").click(function(){
            var eid = $(this).attr("data-id");
            var url = GLOBAL_CF.DOMAIN+"/e/"+eid;
            qc.openWindow(url, 1000, 600, true);
        });
        $("#emp_modify").click(function(){
            var str = [];
            $('input[name="check_emp"]:checked').each(function(){
                str.push($(this).val());
            });
            if (str.length < 1) {
                alert("请选择员工");
                return;
            }
            $("#type").val(3);
            var eid = str.join("e");
            var url = GLOBAL_CF.DOMAIN+"/emp/getEmployByIds/"+eid;
            qc.openWindow(url, 1000, 600, true);

        })
    });
