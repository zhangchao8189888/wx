window.onload = function(){
	/*图片预览*/
	var pics_table_wapper = $('#pics_table_wapper'),/*所有案例图片的table容器*/
		container = $('#layer_tag'),/*弹层容器*/
		el_ul = $('ul', container);
		el_li = $('ul li', container),
		el_imgWapper = $('#img_wapper', container),
		el_close = $('#tag_close_btn'),
		(function ul_wapper_resize(){
			resize($(window));
			$(window).resize(function(){
			resize($(this));
		});
		function resize($_window) {
			var window_heght = $_window.height();
			el_ul.parent().height(window_heght>800? 750:window_heght - 80);
			}
		})();
		var data_array = [],/*数据源*/
		index = 0,
		threading = false;
		el_close.click(function(){container.hide();});
		function render() {
			$('#img_wapper').empty();
			var img_data = data_array.img;
			var title_data = data_array.title.title;
			//展示div
			var cur_html_div = $('<div class="layer_tag_l_n_div"></div>');
			cur_html_div.css({
			width:'100%',
			float:'left',
			margin:'5px auto',
			});
			//标题
			var cur_html_title = $('<div >'+title_data+'</div>');
			cur_html_title.css('font-family','Microsoft Yahei');
			cur_html_title.css('font-size',20);
			cur_html_title.css('color','#333');
			cur_html_div.append(cur_html_title);
			for (var i=0; i<img_data.length; i++) {
				var current_item = img_data[i];
				//图片内容
				var cur_html = $("<img>");
				cur_html.attr('src',current_item.img_url);
				cur_html.css('height',450);
				cur_html.css('width',480);
				cur_html.css('margin','5px');
				//简介
				var cur_html_desc = $('<div >'+current_item.desc+'</div>');
				cur_html_desc.css('display','block');
				cur_html_desc.css('background-color','#f7f7f7');
				cur_html_div.append(cur_html);
				cur_html_div.append(cur_html_desc);
				$('#img_wapper').append(cur_html_div);
			}
	};
	pics_table_wapper.delegate('td[data-array] img', 'click', function(){
		var data = $(this).parents('td').attr('data-array');
		data_array = (new Function('return' + data))();
		container.width(620);
		container.height(630);
		container.css({
		left: ((document.body.clientWidth || document.documentElement.clientWidth) - container.width())/2,
		}).show();
		$('.layer_tag_l').css('width','520px');
		$('.layer_tag_l').css('height','600px');
		$('#img_wapper').css('width',$('.layer_tag_l').css('width')-50);
		$('#img_wapper').css('overflow-y','auto');
		$('#img_wapper').css('overflow-x','hide');
		render();
	});
	$('[event_type=viewImg]').on('click',function(event){
		var data = $(this).parents('td').attr('data-array');
		data_array = (new Function('return' + data))();
		container.width(620);
		container.height(800);
		container.css({
		left: ((document.body.clientWidth || document.documentElement.clientWidth) - container.width())/2
		}).show();
		$('.layer_tag_l').css('width','520px');
		$('.layer_tag_l').css('height','600px');
		$('#img_wapper').css('width',$('.layer_tag_l').css('width')-50);//知道
		$('#img_wapper').css('overflow-y','auto');
		$('#img_wapper').css('overflow-x','hide');
		render();
	}); 
};