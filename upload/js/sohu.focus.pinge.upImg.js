var uploadPic = function(opt){
	this.box = $(opt.uploadBox);
	this.btn =$('[node_type=btn]');
	this.iframId = 'upload'+new Date().getTime();
	this.form = $(opt.uploadForm)[0]?$(opt.uploadForm) : $('[node_type=form]' ,$(opt.uploadBox))
	this.file = $('[node_type=file]' ,this.form)
	this.id = $(opt.id)
	this.init();
	}
uploadPic.prototype = {
    
    init:function(){
        var that = this,_lay = null;
        $(this).on('uploadSuc',function(evt,data){
        	
            that.btn.attr('isLoading','false');
			var _data = data.content[0];
			
			$('#uploadImg').attr('src',_data.url);
			$('#uploadImgId').val(_data.id);
			$('#uploadImgTitle').val(_data.title);
			
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
				ljs.mod.cAlert({txt:'图片数量超过限制，最多上传30张'})
				return
			}
			if(_lay){return}
			that.form[0].reset();
                that.file.click();
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


 var upload = new uploadPic({uploadBox:$('#uploadBox'),uploadForm : '[node_type="form"]'});
	//uploadSuc
	$(upload).on('uploadSuc',function(evt,data){
							    var _data = data.content[0]
								$('#uploadImg').attr('src',_data.url)
								$('#uploadImgId').val(_data.id)
								$('#uploadImgTitle').val(_data.title)
								$('#uploadNewError').hide()
								$('#editOk').show()
								
								$("#editOk").click(function(){
									var nowImg =$('#createSubjectBox').find('tbody').find('.pic').find('img');
									var nowInput =$('#createSubjectBox').find('tbody').find('.pic').find('input');
									nowImg[0].src = _data.url;
									nowImg[0].image_id = _data.id;
									nowInput.val(_data.title);
									$('#modal-event-pic,.modal-backdrop ').hide();
								});	 

									  });
	$(upload).on('uploadErr',function(evt,data){
							    $('#uploadNewError').show().find('span').html(data.msg)
								$('#uploadImg').attr('src',$('#uploadImg').attr('defSrc'))
								$('#uploadImgId').val('')
								$('#uploadImgTitle').val('')
								$('#uploadNewError').show()
								$('#editOk').hide()
									  });

	$('#submitBtn').on('click',function(){
										$('#createError').hide();
										if(!cFormTitle.check()){
											$('#createError').show();
											cFormTitle.showError()
											return
											}
										
										if(!cFormTextarea.check()){
											$('#createError').show();
											cFormTextarea.showError()
											return
											}
									    if(!cformUrl.check()){
											$('#createError').show();
											cformUrl.showError()
											return
											}
									    
										if($.trim($('#uploadImgId').val())==''){
											$('#createError').show();
											return
											}
										$('#createSubjectBox').submit();
										
										});