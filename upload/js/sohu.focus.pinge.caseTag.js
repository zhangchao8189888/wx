window.onload = function(){
	var pics_table_wapper = $('#pics_table_wapper'),/*所有案例图片的table容器*/
		container = $('#layer_tag'),/*弹层容器*/
		el_ul = $('ul', container);
		el_li = $('ul li', container),
		el_imgWapper = $('#img_wapper', container),
		el_prev = $('.prev', container),
		el_next = $('.next', container),
		el_close = $('#tag_close_btn'),
		el_msg = $('#tag_submit_msg');/*提交后操作成功/失败的显示*/


	(function ul_wapper_resize(){
		resize($(window));

		$(window).resize(function(){
			resize($(this));
		});

		function resize($_window) {
			var window_heght = $_window.height();
			el_ul.parent().height(window_heght>800? 750:window_heght - 80);
		}


		$(window).keydown(function(event){
			if (!container.is(':visible')) return;
			/*left*/
			if (event.keyCode == 37 && el_prev.is(':visible')) {
				el_prev.trigger('click');
			}
			/*right*/
			if (event.keyCode == 39 && el_next.is(':visible')) {
				el_next.trigger('click');
			}
		});

	})();
	

	var data_array = [],/*数据源*/
		index = 0,
		threading = false;

		el_prev.click(function(){
			index--;
			render(data_array[index].img_id);
		});
		el_next.click(function(){
			index++;
			render(data_array[index].img_id);
		});
		el_close.click(function(){container.hide();});

		function render(img_id, first) {
			el_msg.hide();
			if (first) {
				index = findIndexByImgId(img_id);
			}
			var current_item = data_array[index];
			container.find('.img_desc').text(data_array[index].desc);

			/*设置选中图片url*/
			var img = new Image();
			img.src = current_item.img_url;
			el_imgWapper.empty().append(img);


			el_li.removeClass('active');

			/**
			 * [设置选中图片对应的tag]
			 * [iterat all ul and set the same id with current_item.tag.key]
			 * @return {[type]} [description]
			 */
			el_ul.each(function(){
				var ul = $(this),
					li = ul.find('li');

				li.each(function(){
					var el = $(this);
						li_id = el.attr('id');
					//这里需要ul的ID与php渲染的current_item.tag属性中每一个item的ID一致
					if (li_id == current_item.tag[ul.attr('id')]) {
						el.addClass('active');
					}
				});

			});
			

			if (index >= data_array.length - 1) {
				el_next.hide();
			} else {
				el_next.show(); 
			};

			if (index == 0) {
				el_prev.hide();
			} else {
				el_prev.show();
			};
		};

		/*修改当前图片的tag值*/
		function setTag() {
			var current_item = data_array[index];
			var tagFiexd = {};

			/*遍历所有的Li,根据active设置tag属性*/
			el_li.each(function(){
				var el = $(this);
				if (el.hasClass('active')) {
					current_item.tag[el.parent().attr('id')] = el.attr('id');
					//如果li为fiexd类型的，则记录在临时的tagFiexd对象中
					if (el.parent().data('escape')) {
						tagFiexd[el.parent().attr('id')] = el.attr('id');
					};
				};
			});

			//遍历所有的data_array,设置所有item的fiexd类型的tag均一致
				for (var key in tagFiexd) {
					for (var i=0; i<data_array.length; i++) {
						data_array[i].tag[key] = tagFiexd[key];
					};
				};
			
		};



		$('#tag_submit_btn').click(function(){
			threading = true;
			$.ajax({
				type: 'post',
				dataType: 'json',
				url: '/pic/save',
				data: {
					caseTagInfo: data_array
				},
				success: function(data){
					threading == false;
					if (data.status == "100000") {
						container.hide();
						window.location.reload();
					} else {
						el_msg.text('失败').show();
					}
					
				}
			});
		});
		
		function findIndexByImgId(img_id) {
			for (var i=0; i<data_array.length; i++) {
				if (data_array[i].img_id == img_id) return i;
			}
		};


		el_li.click(function(event){
			if (!checkAddedColor($(this))) {
				el_msg.text('色系不能重复').show();
				return false;
			}

			el_msg.hide();
			$(this).parents('ul').find('li').removeClass('active');
			$(this).addClass('active');
			setTag();
		});


		/**
		 * [colorAddCheck 对于新增加的一行色系color2作验证，不能与原color重复]
		 * @return {[type]} [description]
		 */
		function checkAddedColor(li_el) {

			if (li_el.parent().attr('id') == 6 && li_el.attr('id') ==  $('ul[id="7"]', container).find('li.active').attr('id')) {
				return false;
			}

			if (li_el.parent().attr('id') == 7 && li_el.attr('id') ==  $('ul[id="6"]', container).find('li.active').attr('id')) {
				return false;
			}

			return true;
		}


		pics_table_wapper.delegate('td[data-array] img', 'click', function(){
			var data = $(this).parents('td').attr('data-array');
			data_array = (new Function('return' + data))();
			render($(this).attr('id'), 'first');
			container.css({
				left: ((document.body.clientWidth || document.documentElement.clientWidth) - container.width())/2
			}).show();
		});

};

