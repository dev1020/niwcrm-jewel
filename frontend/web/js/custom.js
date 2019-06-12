
$( document ).ready(function() {
	$.fn.modal.Constructor.prototype.enforceFocus = function() {};
		
		$("#sidebar").mCustomScrollbar({
        theme: "minimal"
		});
		$("document").on("click","#abc",function(e){
			alert(1);
		});
		$('#sidebarCollapse').on('click', function () {
			// open sidebar

			$('#sidebar').addClass('active');
			// fade in the overlay
			$('.overlay').fadeIn();
			$('.collapse.in').toggleClass('in');
			$('a[aria-expanded=true]').attr('aria-expanded', 'false');
		});

   
		// if dismiss or overlay was clicked
		$('#dismiss, .overlay').on('click', function () {
		  // hide the sidebar
		  $('#sidebar').removeClass('active');
		  // fade out the overlay
		  $('.overlay').fadeOut();
		});
		
		
		
		
		$('#image-gallery').lightSlider({
                gallery:true,
                item:1,
                thumbItem:9,
                slideMargin: 0,
                speed:500,
                auto:false,
                loop:true,
                onSliderLoad: function() {
					
                    $('#image-gallery').removeClass('cS-hidden');
                }  
            });
		
		  $("#loginbutton").on('click',function(event){
			  event.preventDefault();
			  $('#loginModal').modal('show').find('#modalContent').load($(this).attr('href'));
			 
		  });
		  
		 $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
			
			
			
		  $("#moviecarousel").owlCarousel({
			  loop: true,
			  autoplay: true,
			  items: 1,
			  lazyLoad:true,
			  nav: false,
			  autoplayHoverPause: true,
			  animateOut: 'slideOutUp',
			  animateIn: 'slideInUp'
			});
		 
		  $(".search").click(function(){
			if($("#searchform").attr("data-hide") == "hide"){
				$("#searchform").removeClass("hidden animated slideOutRight").addClass("animated slideInLeft").attr("data-hide", "show");
			}else if($("#searchform").attr("data-hide") == "show"){
				$("#searchform").removeClass("animated slideInLeft").addClass("animated slideOutRight").attr("data-hide", "hide");
			}
			
		  });
		  $("#browsecategory").click(function(){
			//alert(1);
			if($(".browser").attr("data-hide") == "hide"){
				$(".browser").removeClass("hidden animated slideOutUp").addClass("animated slideInDown").attr("data-hide", "show");
			}else if($(".browser").attr("data-hide") == "show"){
				$(".browser").removeClass("animated slideInDown").addClass("animated slideOutUp").attr("data-hide", "hide");
			}
			
		  });

		    

});
			
	
	$(window).load(function() {
		 // $('.flexslider').flexslider({
		//	animation: "slide"
		 // });
		  $('.flexslider').flexslider({
    touch: true,
    slideshow: true,
    controlNav: true,
    slideshowSpeed: 7000,
    animationSpeed: 600,
    initDelay: 0,
    start: function(slider) { // Fires when the slider loads the first slide
	//alert(1);
      var slide_count = slider.count - 1;

      $(slider)
        .find('img.lazy:eq(0)')
        .each(function() {
          var src = $(this).attr('data-src');
          $(this).attr('src', src).removeAttr('data-src');
        });
    },
    before: function(slider) { // Fires asynchronously with each slider animation
	//alert(2);
      var slides     = slider.slides,
          index      = slider.animatingTo,
          $slide     = $(slides[index]),
          $img       = $slide.find('img[data-src]'),
          current    = index,
          nxt_slide  = current + 1,
          prev_slide = current - 1;

      $slide
        .parent()
        .find('img.lazy:eq(' + current + '), img.lazy:eq(' + prev_slide + '), img.lazy:eq(' + nxt_slide + ')')
        .each(function() {
          var src = $(this).attr('data-src');
          $(this).attr('src', src).removeAttr('data-src');
        });
    }
  });
			
		  
			$("#featured-slider").owlCarousel({
            items:3,
            nav:true,
            autoplay:true,
            dots:true,
			autoplayHoverPause:true,
			nav:true,
			navText: [
			  "<i class='fa fa-angle-left '></i>",
			  "<i class='fa fa-angle-right'></i>"
			],
            responsive: {
                0: {
                    items: 1,
                    slideBy:1
                },
                500: {
                    items: 2,
                    slideBy:1
                },
                991: {
                    items: 2,
                    slideBy:1
                },
                1200: {
                    items: 3,
                    slideBy:1
                },
            }            

        });
		
		
	});
	(function(){
			//alert(1);
			$.scrollUp();
			
			 $(".testimonial-carousel").owlCarousel({
            items:1,
            autoplay:true,
            autoplayHoverPause:true
			});
			 $(".car-slider").owlCarousel({
            items:1,
            autoplay:true,
			autoplayHoverPause:true
			});
			
			
			$('.collapse').on('show.bs.collapse', function() {
                var id = $(this).attr('id');
                $('a[href="#' + id + '"]').closest('.panel-heading').addClass('active-faq');
                $('a[href="#' + id + '"] .panel-title span').html('<i class="fa fa-minus"></i>');
            });

            $('.collapse').on('hide.bs.collapse', function() {
                var id = $(this).attr('id');
                $('a[href="#' + id + '"]').closest('.panel-heading').removeClass('active-faq');
                $('a[href="#' + id + '"] .panel-title span').html('<i class="fa fa-plus"></i>');
            });
			
			$('.select-category.post-option ul li a').on('click', function() {
				$('.select-category.post-option ul li.link-active').removeClass('link-active');
				$(this).closest('li').addClass('link-active');
			});

			$('.subcategory.post-option ul li a').on('click', function() {
				$('.subcategory.post-option ul li.link-active').removeClass('link-active');
				$(this).closest('li').addClass('link-active');
			});
			
			$('[data-toggle="tooltip"]').tooltip();
			
			$('.show-number').on('click', function() {
            $('.hide-text').fadeIn(500, function() {
              $(this).addClass('hide');
            });  
			$('.hide-number').fadeIn(500, function() {
              $(this).addClass('show');
            }); 			
			});
		}());
		
		$(function () {
        $(".newsticker").bootstrapNews({
            newsPerPage: 2,
            autoplay: true,
    		pauseOnHover:true,
            direction: 'up',
            newsTickerInterval: 4000,
            onToDo: function () {
                //console.log(this);
            }
        });
		
		
    });
	
	
