Notify = function(text, callback, close_callback, style) {

	var time = '4000';
	var $container = $('#notifications');
	//var icon = '<i class="fa fa-info-circle "></i>';
	//var winheight = screen.height;
	//var winwidth = screen.width;
	//var setheight = (20/100)*winheight;
	//var setwidth = (20/100)*winheight;
	//$container.css('top',setheight);
 
	if (typeof style == 'undefined' ) style = 'warning'
  
	var html = $('<div class="alert alert-' + style + '  hide">' + text +'</div>');
  
	$('<a>',{
		text: "x",
		class: 'button close',
		style: 'padding-left: 10px;',
		href: '#',
		click: function(e){
			e.preventDefault()
			close_callback && close_callback()
			remove_notice()
		}
	}).prependTo(html)
	
	$container.prepend(html)
	html.removeClass('hide').hide().fadeIn('slow')

	function remove_notice() {
		html.stop().fadeOut('slow').remove()
	}
	
	var timer =  setInterval(remove_notice, time);

	$(html).hover(function(){
		clearInterval(timer);
	}, function(){
		timer = setInterval(remove_notice, time);
	});
	
	html.on('click', function () {
		clearInterval(timer)
		callback && callback()
		remove_notice()
	});
  
  
}
