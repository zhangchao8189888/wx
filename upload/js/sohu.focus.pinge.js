$(function(){
	var html,optHtml;	
	$('#allDel').click(function(){
		var url = $(this).attr('date_adress');
		var arr = [];
		$tmp = $('input[form-type=check]:checkbox');
		var $tmp_length = $tmp.filter(':checked').length;
		$.each($tmp, function(i, ele){
            if (ele.checked) {
                ele.checked = true;
                arr.push(ele.getAttribute('form-data'));
            }
        });
		if($tmp_length == 0) {
			html =  '<div class="tips red">请选择删除项</div>';
            optHtml =  '<a href="#" class="btn" data-dismiss="modal">确定</a>';
		}else{
			html = '<div class="tips">您确定要删除这'+$tmp_length+'项吗？<br/><span class="red">删除后将不可恢复~</span></div>';
			if($(this).hasClass('dis')){
				optHtml =  '<a href="#" id="add-event-submit" class="btn btn-primary" onclick="deleteUser({ids:\''+arr+'\',action:\'-1\'},\''+url+'\')">确定</a>';
			}else{
				optHtml =  '<a href="#" id="add-event-submit" class="btn btn-primary" onclick="deleteUser({ids:\''+arr+'\'},\''+url+'\')">确定</a>';
			}
			optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
		}
		$('.pop').html(html);
		$('.modal_operate').html(optHtml);
	})
	$('#groupAdd').click(function(){
		var arr = [];
		$tmp = $('input[form-type=check]:checkbox');
		var $tmp_length = $tmp.filter(':checked').length;
		$.each($tmp, function(i, ele){
            if (ele.checked) {
                ele.checked = true;
                arr.push(ele.getAttribute('form-data'));
            }
        });
		if($tmp_length == 0) {
			html =  '<div class="tips red">没有选中项</div>';
            optHtml =  '<a href="#" class="btn" data-dismiss="modal">确定</a>';
		}else{
			var str = ['<select node_type="select0">']
			$.each(JAJU.userGroup,function(i,a){
					str.push('<option value="'+a.id+'">'+a.group_name+'</option>')
				});
			str.push('</select>')
			html = '<div class="groupCount">已选择'+$tmp_length+'人，添加到：';
			html += str.join('');
			html += '</div>';
			optHtml =  '<a href="#" id="add-event-submit" class="btn btn-primary" onclick="addGroup(\''+arr+'\')">确定</a>';
			optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
		}
		$('.pop').html(html);
		$('.modal_operate').html(optHtml);
	})
	$('a[event_type=addGroup]').click(function(){
		var data = $(this).attr('id');
		var str = ['<select node_type="select0">']
		$.each(JAJU.userGroup,function(i,a){
				str.push('<option value="'+a.id+'">'+a.group_name+'</option>')
			});
		str.push('</select>')
		html = '<div class="groupCount">已选择1人，添加到：';
		html += str.join('');
		html += '</div>';
		optHtml =  '<a href="#" id="add-event-submit" class="btn btn-primary" onclick="addGroup('+data+')">确定</a>';
		optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
		$('.pop').html(html);
		$('.modal_operate').html(optHtml);
	})
	$('a[event_type=forbidden]').click(function(){
		var id = $(this).attr('id');
	    var text = $(this).text();
	    var action = '';
	    if ('禁用' == text) {
		   action = '-2';
	    } else if ('启用' == text) {
		   action = 1;
	    }
		if($(this).html() == '禁用'){
			html = '<div class="tips">您确定要禁用该用户吗？</div>';
			
		}else{
			html = '<div class="tips">您确定要启用该用户吗？</div>';
		}
		optHtml =  '<a href="#" id="add-event-submit" class="btn btn-primary" onclick="forUse('+id+','+action+')">确定</a>';
		optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
		$('.pop').html(html);
		$('.modal_operate').html(optHtml);
	})
	$('a[button-type=del]').click(function(){
		var url = $(this).attr('date_adress');
		var data = $(this).attr('data');
		html = '<div class="tips">您确定要删除该项吗？<br/><span class="red">删除后将不可恢复~</span></div>';
		// optHtml =  '<a href="#" id="add-event-submit" class="btn btn-primary" onclick="deleteUser({ids:'+data+'},\''+url+'\')">确定</a>';
		if($(this).hasClass('dis')){
				optHtml =  '<a href="#" id="add-event-submit" class="btn btn-primary" onclick="deleteUser({ids:\''+data+'\',action:\'-1\'},\''+url+'\')">确定</a>';
			}else{
				optHtml =  '<a href="#" id="add-event-submit" class="btn btn-primary" onclick="deleteUser({ids:\''+data+'\'},\''+url+'\')">确定</a>';
			}
		optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
		$('.pop').html(html);
		$('.modal_operate').html(optHtml);
	});
	/*编辑*/
	$('a[event_type=edGroup]').click(function(){
		$('.colorRem').html('');
		var id = $(this).attr('id');
		$('.confirm').attr('attr_id',id);
		var nodeName = $(this).attr('node_name');
		$('.modal_name').html(nodeName);
		var resource_ids = $(this).attr('resource_ids');
		var ids = resource_ids.split(','),str=[];
		$.each(JAJU.groups,function(i,a){
			var checked = '';
			$.each(ids,function(j,b){
				if(i==b){
					checked = 'checked="checked"'
					return false
					}
				})
			str.push('<div><span><input type="checkbox" node_type="checkBoxs" value="'+i+'" '+checked+'></span><font>'+a.resource+'</font></div>')
		});
		$('.efill').html(str.join(''));
	});
	$('#allCheck').click(function(){
		var action = 1,type="designer";
		var arr = [];
		$tmp = $('input[form-type=check]:checkbox');
		var $tmp_length = $tmp.filter(':checked').length;
		$.each($tmp, function(i, ele){
            if (ele.checked) {
                ele.checked = true;
                arr.push(ele.getAttribute('form-data'));
            }
        });
		if($tmp_length == 0) {
			html =  '<div class="tips red">请选择删除项</div>';
            optHtml =  '<a href="#" class="btn" data-dismiss="modal">确定</a>';
		}else{
			html = '<div class="tips">您确定要通过这'+$tmp_length+'项吗？<br/><span class="red">通过后将不可恢复~</span></div>';
			optHtml =  '<a href="#" id="add-event-submit" class="btn btn-primary" onclick="setPass(\'upstatus\',{action:\''+action+'\',type:\''+type+'\',ids:\''+arr+'\'})">确定</a>';
			optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
		}
		$('.pop').html(html);
		$('.modal_set').html(optHtml);
	})
	$('a[event_type=recommend]').click(function(){
		var uid = $(this).attr('id');
		$('#sort_confirm').attr('data_id',uid);
		var url = $(this).attr('data_adress');
		$('#sort_confirm').attr('data_adress',url);
		var type = $(this).attr('type');
		$('#sort_confirm').attr('data_type',type);
		var rec = $(this).attr('rec');

		var str = [];
		var str = ['<select name="parent" node_type="select0" id="recommend">']
		$.each(JAJU.recommend,function(i,a){
			var selected = (i == rec)?'selected':'';
			str.push('<option value="'+i+'"'+selected+'>'+a+'</option>')
		});
		str.push('</select>')
		html = '推荐到：';
		html += str.join('');

		$('.designer_win').html(html)
	})
	$('#sort_confirm').click(function(){
		var uid = $(this).attr('data_id');
		var value = $('#recommend option:selected').val();
		var url = $(this).attr('data_adress');
		var type = $(this).attr('data_type');
		if($(this).hasClass('designer_uid')){
			var data = {uid:uid,recommend:value}
		}else if($(this).hasClass('debris')){
			var data = {id: uid,type: type,recommend:value}
		}else{
			var data = {special_id:uid,recommend:value}
		}
		sort(url,data);
	})
	$('a[event_type=check]').click(function(){
		var html,optHtml;
		var url = $(this).attr('data-adress');
		var id = $(this).attr('id');
		var action = '';
		var type = 'designer';
		if($(this).html() == '通过'){
			 action = '1';
			 html = '<div class="tips">您确定要通过申请吗？</div>';
			 if($(this).hasClass('workPass')){
			 	optHtml =  '<a href="#" class="btn btn-primary" onclick="setPass(\''+url+'\',{id:\''+id+'\',refuseReason:\'\',action:\''+action+'\'})">确定</a>';
			 	optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
			 }else{
			 	optHtml =  '<a href="#" class="btn btn-primary" onclick="setPass(\''+url+'\',{id:\''+id+'\',type:\''+type+'\',action:\''+action+'\'})">确定</a>';
			 	optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
			 }
		}else{
			
			html = '<div class="tips">拒绝理由：<input type="text" maxlength="20" id="reason" name="reason" node_type="reason"></div>';
			if($(this).hasClass('workPass')){
				optHtml =  '<a href="#" class="btn btn-primary refuse rwp" data-id="'+id+'" data-adress="'+url+'">确定</a>';
				optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
			}else if ($(this).hasClass('designer-refuse')) {
                optHtml =  '<a href="#" class="btn btn-primary refuse" data-action="-3" data-id="'+id+'" data-adress="'+url+'">确定</a>';
                optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
            } else{
				optHtml =  '<a href="#" class="btn btn-primary refuse" data-id="'+id+'" data-adress="'+url+'">确定</a>';
				optHtml += '<a href="#" class="btn" data-dismiss="modal">取消</a>';
			}
		}
		$('.pop').html(html);
		$('.modal_set').html(optHtml)
	})
	$('.refuse').live('click',function(){
		if($('#reason').val() == ''){alert('拒绝理由不能为空');return false}
		var action = '-2',type = 'designer';
        if ($(this).attr('data-action')) {
            action = $(this).attr('data-action');
        }
		var id = $(this).attr('data-id');
		var reason = $('#reason').val();
		var url = $(this).attr('data-adress');
		
		if($(this).hasClass('rwp')){
			var data = {id:id,refuseReason:reason,action:action};
		}else{
			var data = {id:id,reason:reason,type:type,action:action};
		}
		setPass(url,data);
	})

	$('[node_type="userZH"]').on('blur',function(e){
		var  reg = /^(\w{5,30})$/; 
		if (!reg.test($.trim(this.value)) && "" != this.value) {
			$('.colorRem').html('请正确填写账号信息');
		} else {
			$('.colorRem').html('')
		}
	})
	$('[node_type="userName"]').on('blur',function(e){
		var  reg = /^[\u0391-\uFFE5\w]+$/;
		if (!reg.test($.trim(this.value))) {
			$('.colorRem').html('姓名不合法');
		} else {
			$('.colorRem').html('')
		}
	})
	$('[node_type="phonoNum"]').on('blur',function(e){
		var  reg = /^1\d{10}$/;
		if (!reg.test($.trim(this.value)) && "" != this.value) {
			$('.colorRem').html('手机号不合法');
		} else {
			$('[node_type="error3"]',$(that.lay.win)).hide();
			$('.colorRem').html('')
		}
	})
	//管理员搜索
	$('#searchBtn').click(function(){
		if ('请通过帐号搜索' != $('#searchInput').val() ) {
            window.location.href='?g=' + $("#userGroups").val() + '&k=' + $('#searchInput').val() ;
		}else{
            window.location.href='?g=' + $("#userGroups").val() + '&k=' ;
        }
	})
	$('[event_type=editImg]').on('click',function(event){
		$('#uploadNewError').hide();
		var now_id = $(this).attr('id');
		var now_img = $(this).parent().parent().find('.pic').find('img');
		if(now_img[0].src == ''){
			var image_title = $("#old_img_src").val();
			$("#uploadImg").attr('src',image_title);
		}else{
			$("#uploadImg").attr('src',now_img[0].src);
			$("#now_img_id").val(now_img.attr('img_id'));
			$("#old_img_id").val(now_img.attr('image_id'));
			$("#old_img_title").val(now_img.attr('image_title'));
		}
	})
	
	/*添加、删除*/
	$('a[data-action=add]').click(function(){
		var newElement = $(this).parents('.controls').find('.person_case_input').clone();
		$(this).parents('.controls').append(newElement)
	})
	$('a[data-action=delte]').live('click',function(){
		$(this).parent().remove();
	})
});
function addGroup(ids){
	ids = typeof ids === "object" ? ids.join(',') : ids;
	var data = $('[node_type="select0"] option:selected').val();
	$.ajax({
	  type: "post",
	  url: "/user/addgroup/",
	  dataType: "json",
	  data:{ids:ids,group_id:data},
	  cache:false,
	  success:function(json){
		  if(json.status=='100000'){
			  window.location.reload();
		  }else{
			  $('[node_type="error"]',$(that.lay.win)).show().find('.colorRem').html('添加失败')
              return 
		    }
	  }
	});
}
function forUse(id,action){
	$.ajax({
		  type: "post",
		  url: "upstatus",
		  dataType: "json",
		  data:{id:id, action:action},
		  cache:false,
		  success:function(json){
			  if(json.status == '100000'){
				  window.location.reload();
			  }else{
				  alert({txt: text + '失败'})
			  }
		  }
	});
}
function addUser(){
	var  userZH_reg = /^(\w{5,30})$/; 
    var  userName_reg = /^[\u0391-\uFFE5\w]+$/;
    var  phonoNum_reg = /^1\d{10}$/;

	var userZH = $.trim($('[node_type="userZH"]').val());
    var userName = $.trim($('[node_type="userName"]').val());
    var phonoNum = $.trim($('[node_type="phonoNum"]').val());

	if(userZH == ''){
		$('.colorRem').html('请填写账号信息');
		return false;
	}else if(!userZH_reg.test($.trim(userZH))){
		$('.colorRem').html('请正确填写账号信息');
		return false;
	}
	if(userName == ''){
		$('.colorRem').html('请填写用户信息');
		return false;
	}else if(!userName_reg.test($.trim(userName))){
		$('.colorRem').html('请正确填写用户信息');
		return false;
	}
	if(phonoNum == ''){
		$('.colorRem').html('请填写手机信息');
		return false;
	}else if(!phonoNum_reg.test($.trim(phonoNum))){
		$('.colorRem').html('请正确填写手机信息');
		return false;
	}
	var data = {
		user:userZH,
		userName : userName,
		phonoNum : phonoNum
		}
	if(!data){ 
		$('.colorRem').html('请正确填写用户信息')
		return 
	}
	$.ajax({
	  type: "post",
	  url: "add",  
	  dataType: "json",
	  data:data,
	  cache:false,  
	  success:function(json){ 
		  if(json.status=='100000'){
			  window.location.reload();
		  }else if (json.status=='100004') {
			  if ("undefined" != typeof(json.content.passport)) {
				  $('[node_type="error1"]',$(that.lay.win)).show().find('#err_user').html(json.content.passport[0])
			  }
			  if ("undefined" != typeof(json.content.nickname)) {
				  $('[node_type="error2"]',$(that.lay.win)).show().find('#err_name').html("姓名过长，最多不能超过10个汉字.")
			  }
			  if ("undefined" != typeof(json.content.mobile)) {
				  $('[node_type="error3"]',$(that.lay.win)).show().find('#err_mobile').html(json.content.mobile[0])
			  }
			  return;
		  }
	  }
	});
}
function deleteUser(data,url){
		//data = typeof data === "object" ? data.join(',') : data;
	    $.ajax({
	        url: url,
	        // data: {
	        //     ids: data
	        // },
	        data: data,
			type:'GET',
	        dataType: 'json',
	        cache: false,
	        success: function(json){
	            if (json.status == '100000') {
						window.location.reload();
	            } else if(json.status=='100003'){
	            	window.location.href = json.content.url;
	            } else if(json.status=='100004'){
	            	alert("删除失败")
	            }
	            else {
	            	alert(json.msg)
	            }
	        },
	        error: function(){
	            alert('数据加载失败');
	        }
	    });
	}
	/*创建组*/
	function createGroup(){
		var arr = [];
		$tmp = $('.cfill input[node_type=checkBoxs]:checkbox');
		var $tmp_length = $tmp.filter(':checked').length;
		$.each($tmp, function(i, ele){
            if (ele.checked) {
                ele.checked = true;
                arr.push(ele.getAttribute('value'));
            }
        });
        var opt = arr.join(','); // 以逗号分隔的被选中的id
		var name = $.trim($('[node_type="groupName"]').val());
		if(/[@\/'\\"#$%&\^*!]/.test(name))
		{
			$('.colorRem').html('组名不能包含@/\'\"#$%&^*!等特殊字符');
			$('[node_type="groupName"]').focus();
			return false;
		}else if ("" == name) {
			$('.colorRem').html('请完整填写组名！');
			$('[node_type="groupName"]').focus();
			return false;
		} else if (name.length > 20) {
			$('.colorRem').html('组名过长，不能超过20个英文字符！');
			$('[node_type="groupName"]').focus();
			return false;
		}
		var data = {ids:opt, role_name:name, action:'add'};
		$.ajax({
		  type: "post",
		  url: "/power/add/url, settings, settings",
		  dataType: "json",
		  data:data,
		  cache:false,
		  success:function(json){
			  if(json.status=='100000'){
				  window.location.reload();
			  } else if (json.status=='100004') {
				  $('.colorRem').html('添加失败！');
				   $('[node_type="groupName"]').focus();
				  return false;
			  } else{
					$('.colorRem').html('添加失败！');
				  return false;
			  }
		  }
		});
	}
	$(function(){
	$('.confirm').click(function(){
	// function editGroup(){
		$(this).parents('.modal').find('.colorRem').html('');
			var arr = [];
			$tmp = $(this).parents('.modal').find('.efill').find('input[node_type=checkBoxs]:checkbox');
			var $tmp_length = $tmp.filter(':checked').length;
			$.each($tmp, function(i, ele){
		        if (ele.checked) {
		            ele.checked = true;
		            arr.push(ele.getAttribute('value'));
		        }
		    });
		    var resource_ids = arr.join(','); // 以逗号分隔的被选中的id
		    var id = $('.confirm').attr('attr_id');
			var data = {id:id, resource_ids:resource_ids,action:'edit_group'};
			console.log(resource_ids == '')
			if(resource_ids == ''){
				$(this).parents('.modal').find('.colorRem').html('编辑失败!');
				return false;
			}
			$.ajax({
			  type: "post",
			  url: "edit",
			  dataType: "json",
			  data:data,
			  cache:false,
			  success:function(json){
				  if(json.status=='100000'){
					  window.location.reload();
				  } else if (json.status=='100004') {
					  $(this).parents('.modal').find('.colorRem').html('编辑失败!');
					  
				} else {
				 $(this).parents('.modal').find('.colorRem').html('编辑失败!');
				}
			  }
			});
	})
	})
	/*排序*/
	function sort(url,data){
		$.ajax({
		  type: "post",
		  url: url,
		  dataType: "json",
		  data:data,
		  cache:false,
		  success:function(json){
				  if(json.status=='100000'){
						window.location.reload();
				  }else{
					  alert('推荐失败')
	                  return 
				    }
			}
		});
	}
	function setPass(url,data){
		$.ajax({
   			  type: "post",
   			  url: url,
   			  dataType: "json",
   			  data:data,
   			  cache:false,
   			  success:function(json){
   				  if(json.status == '100000'){
						window.location.reload();
   				  } else if (json.status == '100006') {//设计师大赛设计师审核未通过说明
                      alert('该用户未上传三个及以上案例不能审核通过');
                  }else{
   					  alert('失败');
   					  }
   			      }
   			});
	}