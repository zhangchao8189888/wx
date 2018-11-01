/**
 * Created by zhangchao8189888 on 16-3-25.
 */
$(function(){
    var util = {
        isDef : function(o) {
            return typeof o != 'undefined';
        },
        isNum : function(o) {
            return typeof o == 'number' && o != null;
        },
        isArray : function (o){
            return o && typeof o==='object' &&
                Array == o.constructor;
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

                return window.setTimeout(function() {
                    fn.apply(obj || window, inputArg);
                }, t);
            }
        },
        remove : function (e) {

            /*var navigatorName = "Microsoft Internet Explorer";
             var name = navigator.appName;
             if(e.removeNode && typeof(e.removeNode)=="function"){
             //if(navigator.appName == navigatorName || navigator.appName == "Netscape"){
             e.removeNode(true);
             }else{
             e.remove();

             }*/
            try{
                e.remove();
            } catch(err)
            {
                e.removeNode(true);
            }
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
        },

        formatStr : function(str) {
            str=str.replace(/,/ig,"");
            return str;
        }
    }
    util.extend(Array.prototype, {
        clone : function() {
            return this.slice(0);
        },
        contains : function (v) {
            return this.indexOf(v) >= 0;
        },
        indexOf : function(v) {
            for (var i = 0; i < this.length; i++) {
                if (this[i] && this[i] === v) {
                    return i;
                }
            }

            return -1;
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
        addSingle : function(o) {
            if (o && this.indexOf(o) < 0) {
                this.add(o);
            }
        },
        add : function() {
            var i = -1, v = null;

            if (arguments.length == 1) {
                v = arguments[0];
            } else if (arguments.length > 1) {
                i = arguments[0];
                v = arguments[1];
            } else {
                return;
            }

            if (i < 0) {
                this.push(v);
            } else {
                this.splice(i, 0, v);
            }

            return this;
        },
        removeAt : function(iIdx) {
            this.splice(iIdx, 1);
            return this;
        },
        remove : function(v) {
            var i = this.indexOf(v);

            if (i >= 0) {
                this.removeAt(i);
                return true;
            } else {
                return false;
            }
        },
        splitBlock : function (nBlockCount) {
            var len = this.length;
            if (len == 0)return [];

            var size = parseInt(len / nBlockCount);

            if (len % nBlockCount > 0) {
                size++;
            }

            var retArray = [];
            for (var i = 0; i < size; i++) {
                retArray.push([]);
            }

            for (var j = 0; j < len; j++) {
                var index = parseInt(j / nBlockCount);
                retArray[index].push(this[j]);
            }

            return retArray;
        }
    });
    var handsonTableFn = {
        clearEmptyRowOrCol : function (obj){

            var bDelRow = false;
            for (var i = 0;i < obj.countRows(); i++) {
                if (bDelRow) {
                    i--;
                }
                if (obj.isEmptyRow(i)) {
                    obj.alter('remove_row',i);
                    bDelRow = true;
                } else {
                    bDelRow = false;
                }

            }
            var bDelCol = false;
            for (var j = 0;j < obj.countCols(); j++) {
                if (bDelCol) {
                    j--;
                }
                if (obj.isEmptyCol(j)) {
                    obj.alter('remove_col',j);
                    bDelCol = true;
                    if (j == obj.countCols()-1) {
                        bDelCol = false;
                        j--;
                    }
                } else {
                    bDelCol = false;
                }

            }
        }
    }
    $.util = util;
    $.handsonTableFn = handsonTableFn;
});