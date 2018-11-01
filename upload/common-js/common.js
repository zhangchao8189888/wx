/**
 * Created by zhangchao8189888 on 15-5-21.
 */
function checkRate(input)
{
    var re = /^[0-9]+.?[0-9]*$/;   //判断字符串是否为数字     //判断正整数 /^[1-9]+[0-9]*]*$/
    var nubmer = document.getElementById(input).value;

    if (!re.test(nubmer))
    {
        alert("请输入数字");
        document.getElementById(input).value = "";
        return false;
    }
}
Array.prototype.remove=function(obj){
    for(var i =0;i <this.length;i++){
        var temp = this[i];
        if(!isNaN(obj)){
            temp=i;
        }
        if(temp == obj){
            for(var j = i;j <this.length;j++){
                this[j]=this[j+1];
            }
            this.length = this.length-1;
        }
    }
}
var UTIL = {
    extend : function(oTarget, oSource, fOverwrite) {
        if (!oTarget) {
            oTarget = {};
        }

        if (!oSource) {
            return oTarget;
        }

        for (var k in oSource) {
            v = oSource[k];

            if (util.isDef(v) && (fOverwrite || !util.isDef(oTarget[k]))) {
                oTarget[k] = v;
            }
        }

        return oTarget;
    },
    isDef : function(o) {
        return typeof o != 'undefined';
    },
    isNum : function(o) {
        return typeof o == 'number' && o != null;
    },
    isArray : function(o) {
        return o && (typeof(o) == 'object') && (o instanceof Array);
    },
    isStr : function(o) {
        return o && (typeof o == 'string' || o.substring);
    },
    isWinActive : function() {
        return util.STORE.__bWinActive;
    },
    wait : function(fnCond, fnCb, nTime) {
        function waitFn() {
            if (fnCond()) {
                fnCb();
            } else {
                W.setTimeout(waitFn, util.isNum(nTime) ? nTime : 100);
            }
        };

        waitFn();
    },
    delay : function(iTime) {
        var t, arg;

        if ($.isFunction(iTime)) {
            arg = [].slice.call(arguments, 0);
            t = 10;
        } else {
            arg = [].slice.call(arguments, 1);
            t = iTime;
        }

        if (arg.length > 0) {
            var fn = arg[0], obj = arg.length > 1 ? arg[1] : null, inputArg = arg.length > 2 ? [].slice.call(arg, 2) : [];

            return W.setTimeout(function() {
                fn.apply(obj || W, inputArg);
            }, t);
        }
    },
    clearDelay : function(n) {
        W.clearTimeout(n);
    },
    formatStr : function(str) {
        str=str.replace(/,/ig,"");
        return str;
    },
    checkRate : function (val)
    {
        var re = /^[-+]?[0-9]+.?[0-9]*$/;   //判断字符串是否为数字     //判断正整数 /^[1-9]+[0-9]*]*$/
        var nubmer = val;

        if (!re.test(nubmer))
        {
            return false;
        }
        return true;
    },
    uploadValidate : function () {
        var em = null;
        if (arguments.length > 0 ) {
            em = arguments[0];
        }
        if (!em) {
            return false;
        }
        var filepath = $(em).val();
        var extStart = filepath.lastIndexOf(".");
        var ext = filepath.substring(extStart, filepath.length).toUpperCase();
        if (ext != ".PNG" && ext != ".GIF" && ext != ".JPG" && ext != ".JPEG") {
            layer.alert("图片限于png,gif,jpeg,jpg格式");
            return false;
        } else { $("#fileType").text(ext) }
        var file_size = 0;
        if ($.browser.msie) {
            var img = new Image();
            img.src = filepath;
            if(img.readyState=="complete"){//已经load完毕，直接打印文件大小
                if (img.fileSize > 0) {
                    if (img.fileSize > 2 * 1024 * 1024) {
                        layer.alert("上传的图片大小不能超过2M。");
                        return false;
                    }
                }
            }else{
                img.onreadystatechange=function(){
                    if(img.readyState=='complete'){//当图片load完毕
                        if (img.fileSize > 0) {
                            if (img.fileSize > 2 * 1024 * 1024) {
                                layer.alert("上传的图片大小不能超过2M。");
                                return false;
                            }
                        }
                    }
                }
            }
        } else {
            file_size = this.files[0].size;
            var size = file_size / 1024;
            if (size > 2048) {
                layer.alert("上传的图片大小不能超过2M。");
                return false;
            }
        }
        return true;
    }
};
(function ($){
    jQuery.dialog={

        //弹出框显示
        show:function(elem,mask,tit){
            //获取显示器窗口的宽度与高度
            var _width=$(window).width();
            var _height=$(document).height();
            //弹出框据页面顶部与左边距离
            var _top=$(document).scrollTop()+($(window).height()-$(elem).height())/2;
            if (_top < 0) {
                _top = 0;
            }
            var _left=(_width-$(elem).width())/2;

            $(mask).css({
                width:_width,
                height:_height
            });

            $(elem).css({
                top:_top,
                left:_left
            });

            $(mask).show();
            $(elem).show();

            //设置弹出层标题
            $(elem).find(".titlebar>h1").text(tit);

        },

        //弹出框关闭
        close:function(elem,mask){
            //弹出框隐藏
            $(elem).parents(".dialog").hide();
            $("#jobPop").hide();
            //遮罩层隐藏
            $(mask).hide();
            $("#scbtn_flash").css("display","block");
            var id = $(elem).attr("data-id");
            if (id == "cancel_job") {
                $("#sel_result").html('');
            }
        }
    };

})(jQuery);
