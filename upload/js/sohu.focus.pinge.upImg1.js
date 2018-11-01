var uploadPic = function(opt){
        this.box = $(opt.uploadBox)
        this.btn =$('[node_type=btn]' ,$(opt.uploadBox));
        this.iframId = 'upload'+new Date().getTime();
        this.form = $(opt.uploadForm)[0]?$(opt.uploadForm) : $('[node_type=form]' ,$(opt.uploadBox))
        this.file = $('[node_type=file]' ,this.form)
        this.init();
        }
    uploadPic.prototype = {
        
        init:function(){
            var that = this,_lay = null;
            $(this).on('uploadSuc',function(){
                that.btn.attr('isLoading','false');
            });
            $(this).on('uploadErr',function(){
                that.btn.attr('isLoading','false')
            })
            
            // 上传
            that.btn.on('click',function(){
                
                if(that.btn.attr('isLoading')=='true'){
                        return
                }
                if(that.box.attr('isfulled')=='true'){
                    alert('图片数量超过限制，最多上传30张');
                    return
                }
                if(_lay){return}
                that.form[0].reset();
               /* if(!$.browser.mozilla){
                    _lay = ljs.mod.cAlert({
                        tpl : '<div class="infor inforNew inforJoin"><div class="title"><span class="txt">上传图片</span><span class="btnExit"><a event_type="del2" href="javascript:void(0)"></a></span></div> <div class="fillList"></div>    <div style="padding-left:75px; padding-top:30px;" class="btnEn"></div></div>'
                    })
                    $('.nopadding', _lay.win).append(that.form);
                    
                    that.form.show();
                    
                    $('[event_type="del2"]',_lay.win).on('click',function(){
                        if(_lay){
                            $(document.body).append(that.form);
                            _lay.del();
                            _lay = null;
                        }
                    })
                    
                }else{*/
                    that.file.click();
                // }
            })
            that.file.on('change',function(){
                that.btn.attr('isLoading','true');
                that.form.attr({
                    target: that.iframId
                   // action: './pbg_set.action?roomId=' + t.opt.roomId
                });
                that.upload();
                if(_lay){
                    $(document.body).append(that.form)
                    _lay.del();
                    _lay = null;
                }
            })
            
        },
        upload: function(){
            var that = this;
            if ($('#'+that.iframId).length !== 0) 
                return that.btn.attr('isLoading', 'true');
            if (!$('#'+that.iframId)[0]) {
                $(document.body).append('<iframe name="'+that.iframId+'" id="'+that.iframId+'" style="display:none"></iframe>');
                this.form.submit();
                $('#'+that.iframId).on('load', function(){
                    try {
                        var _val = $(this).contents().find('textarea').val();
                        _val = eval('(' + _val + ')');
                        if (!_val) {
                            return;
                        };
                                        } 
                    catch (e) {
                        setTimeout(function(){
                            $('#'+that.iframId).remove();
                        }, 100)
                        return;
                    }
                    
                    if (_val.status == '100000') {
                        // 成功
                        $(that).trigger('uploadSuc',_val)
                    }
                    else {
                        $(that).trigger('uploadErr',_val)
                    }
                    
                    setTimeout(function(){
                        $('#'+that.iframId).remove();
                    }, 200)
                    
                });
            }
            
        }
        
    }


var upload = new uploadPic({uploadBox:$('#basic_validate'),uploadForm : '[node_type="form"]'});
    $(upload).on('uploadSuc',function(evt,data){
                                var _data = data.content[0]
                                $('#uploadImg').attr('src',_data.url)
                                $('#uploadImgId').val(_data.id)
                                $('#uploadImgTitle').val(_data.title)
                                $('#uploadError').hide()
                                      })
    $(upload).on('uploadErr',function(evt,data){
                                $('#uploadError').show().find('span').html(data.msg)
                                $('#uploadImg').attr('src',$('#uploadImg').attr('defSrc'))
                                $('#uploadImgId').val('')
                                $('#uploadImgTitle').val('')
                                  })
    $('#submitBtn').on('click',function(){
        var type = $('#type').val();
        $('#createError').hide();

        if(type == '1' || type == '7'){
            $('.help-inline').remove();
            if($('.error').length > 0){
                $('#createError').show();
                return false;
            }
            if($('input[form_type=title]').val().length == 0){
                $('#createError').show();
                return false;
                }
            if($('input[form_type=url]').val().length == 0){
                $('#createError').show();
               return false;
                }
            if($.trim($('#uploadImgId').val())==''){
                $('#createError').show();
               return false;
                }
            if($.trim($('#uploadImgTitle').val())==''){
                $('#createError').show();
              return false;
                }
               
        }else if(type == '2'){
            $('.help-inline').remove();
            if($('.error').length > 0){
                $('#createError').show();
                return false;
            }
            if($('input[form_type=title]').val().length == 0){
                $('#createError').show();
                return false;
                }
            if($('input[form_type=url]').val().length == 0){
                $('#createError').show();
               return false;
                }
            if($('textarea[form_type=textarea]').val().length == 0){
                $('#createError').show();
                return false;
                }
        }else if(type == '3'){
             if($('.error').length > 0){
                $('#createError').show();
                return false;
            }
            if($('input[form_type=title]').val().length == 0){
                $('#createError').show();
                return false;
                }
            var img_total = $('#img_total').val();
            if(img_total == ''){
                $('#createError').show();
               return false;
            }
            if($('textarea[form_type=textarea]').val().length == 0){
                $('#createError').show();
                return false;
                }
            if($('input[form_type=url]').val().length == 0){
                $('#createError').show();
                return false;
                }
            
            if($.trim($('#uploadImgId').val())==''){
                $('#createError').show();
                var returnFalse = true;
                }
            if($.trim($('#uploadImgTitle').val())==''){
                $('#createError').show();
                var returnFalse = true;
                }
        }else if(type == '4'){
            var name = $("#name").val();
            if (name == ''){
                alert('名字不能为空');
                return false;
            }
            if($('input[form_type=url]').val().length == 0){
                $('#createError').show();
                return false;
                }
            var city_url = $("#city_url").val();
            str = city_url.match(/http:\/\/.+/);
            if (str == null){
                alert('请填写正确的省市URL!');
                return false;
            }
            var cases = formationParam();
            if(cases == true){
                var returnFalse = true;
            }
            if($('#name').val() == '' || $('#city').val() == '' || $('#name').val() == '' || $('#cases').val() == '' || $('#uploadImgId').val() == '' || $('#uploadImgTitle').val() == '' ){
                $('#createError').show();
                return false;
            }
        }else if(type == '5'){
            $('.help-inline').remove();
            if($('.error').length > 0){
                $('#createError').show();
                return false;
            }
            if($('input[form_type=title]').val().length == 0){
                $('#createError').show();
                return false;
                }
            if($('input[form_type=url]').val().length == 0){
                $('#createError').show();
               return false;
                }
            if($('#uploadImgId').val() == '' ){
                $('#createError').show();
                cFormTextarea.showError()
                var returnFalse = true;
            }
            if($('#uploadImgTitle').val() == '' ){
                $('#createError').show();
                cFormTextarea.showError()
                var returnFalse = true;
            }
        }else if(type == '6'){
            $('.help-inline').remove();
            if($('.error').length > 0){
                $('#createError').show();
                return false;
            }
            if($('input[form_type=title]').val().length == 0){
                $('#createError').show();
                return false;
                }
            if($('input[form_type=url]').val().length == 0){
                $('#createError').show();
               return false;
                }
        }
        
            $('#createSubjectBox').submit();
        
        })
$('#submitBtn1').on('click',function(){
        var type = $('#type').val();
        $('#createError').hide();

            $('.help-inline').remove();
            if($('.error').length > 0){
                $('#createError').show();
                return false;
            }
            if($('#required').val().length == 0){
                $('#createError').show();
                return false;
                }
            if($('textarea[form_type=textarea]').val().length == 0){
                $('#createError').show();
                return false;
            }
            if($('#url').val().length == 0){
                $('#createError').show();
               return false;
                }
            if($.trim($('#uploadImgId').val())==''){
                $('#createError').show();
               return false;
                }
            if($.trim($('#uploadImgTitle').val())==''){
                $('#createError').show();
              return false;
                }
               
            $('#createSubjectBox').submit();
        
        })

$(function(){
    $('input[form_type=title]').focus(function(){
        $(this).parent().find('.colorRem').remove();
        var msg = '<span class="colorRem error">最多不能超过20个汉字，不能包含英文的双引号</span>';
        $(this).parent().append(msg)
    }).blur(function(){
        tstr = this.value.match(/\"/);
        if (tstr == null && this.value.length != 0){
            $(this).parent().find('.colorRem').remove();
        }else{
            return false;
        }
    })
    $('input[form_type=url]').focus(function(){
        $(this).parent().find('.colorRem').remove();
        var msg = '<span class="colorRem error">请正确填写url</span>';
        $(this).parent().append(msg)
    }).blur(function(){
        $('.help-inline').remove();
        var _regPara = /^(https|http|ftp|rtsp|mms):\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/i;
        tstr = _regPara.test(this.value);
        if (tstr && this.vlaue != ''){
            $(this).parent().find('.colorRem').remove();
        }else{
            return false;
        }
    })
    $('textarea[form_type=textarea]').focus(function(){
        $(this).parent().find('.colorRem').remove();
        var msg = '<span class="colorRem error">只支持纯文本，最多不超过140个汉字</span>';
        $(this).parent().append(msg)
    }).blur(function(){
        $('.help-inline').remove();
        tstr = this.value.match(/\"/);
        if (this.value.length != 0){
            $(this).parent().find('.colorRem').remove();
        }else{
            return false;
        }
    })
})