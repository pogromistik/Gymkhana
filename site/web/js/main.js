/*“use strict” mode on*/
"use strict";

$(document).ready(function() {
    //Слайдер
    $("#main-slider").owlCarousel({
        navigation : false, // Show next and prev buttons
        slideSpeed : 300,
        paginationSpeed : 400,
        singleItem:true,
        autoPlay: 7000,
        pagination: true
    });

    //надписи на главной новости
    if($('*').is('.my-button')) {
  
        $('.my-button').on('scrollSpy:enter', function() {
          
          $(this).children(".my-button-top").css('left', '0'); 
          $(this).children(".my-button-bottom").css('right', '40px');
                      
        });

        $('.my-button').on('scrollSpy:exit', function() {
          
          $(this).children(".my-button-top").css('left', '-100%');
          $(this).children(".my-button-bottom").css('right', '-100%');
        });

        $('.my-button').scrollSpy();

    }

    //размеры для квадратных картинок
    var col3Width = $('.title-menu-table .col1-3').width();
    $('.title-menu-table .col1-3').outerHeight(col3Width);
    $('.title-menu-table .col2-3').outerHeight(2*col3Width);

    //правая часть
    $(".right-item").on({
        mouseenter: function () {
            $(this).children(".right-item-overlay").stop(true);
            $(this).children(".right-item-overlay").animate(
                { height: '100%' },
                200,
                function(){
                    $(this).children("div").css('border', '1px solid #f7fafb');
                    $(this).animate(
                        { padding: '20px' },
                        200,
                        function(){
                            $(this).children().children().children('a').css('display', 'inline-block');
                            $(this).children().children().children('a').css('visibility', 'visible');
                            $(this).children().children().children('a').addClass('animated');
                        }
                    );
                }
            );
        },
        mouseleave: function () {
            $(this).children(".right-item-overlay").stop(true);
            $(this).children().children().children().children('a').css('display', 'none');
            $(this).children().children().children().children('a').css('visibility', 'hidden');
            $(this).children(".right-item-overlay").animate(
                { padding: '0' },
                200,
                function(){
                    $(this).children("div").css('border', 'none');
                    $(this).animate(
                        { height: '60px' },
                        200
                    );

                }
            );
        }
    });


    //кнопка наверх (газуй)
    $('.top-list').on('click', function(e){
        $('html,body').stop().animate({ scrollTop: $('#header-2').offset().top }, 1000);
        e.preventDefault();
    });

});

$(window).on('load', function () {
    //анимация при загрузке	
	setTimeout(function() {

		$("#preloader").animate(
			{ right: '25%' },
			1000
		);
		$("#preloader").fadeOut(2000);

	}, 200)
	
	//для правой части
	setTimeout(function() {

		var windowHeight = $('#main-content').height();
		var rightItemNumber = $('.right-item').length;
        var headerHeight = $('#header-2').height();
        var itemHeight = (windowHeight-headerHeight)/(rightItemNumber-1);
        if(itemHeight/($('.right-item').width()) > 1.8) itemHeight = $('.right-item').width()*1.8;
        //$('.right-item-1').height(headerHeight + 'px');
        $('.right-item').height(itemHeight + 'px');
        $('.right-item-1').height(headerHeight + 'px');
		/*=== Stellar.js parallax plugin init ====*/
		$.stellar({
			horizontalScrolling: false,
			verticalOffset: 0
		});
		
	}, 300)

    $(".right-item-icons").css({"margin-top": $('#header-2').height()/2-144+'px'});
    $(".menu-bottom .col1-3").outerHeight($(".menu-bottom .height1").outerWidth());
    $('.menu-bottom .col1-3').outerWidth($(".menu-bottom .height1").outerWidth());
    $(".menu-bottom .height2").outerHeight($(".menu-bottom .height1").outerWidth()*2);
});


(function ($) {	
	
	$(window).on('resize', function() {			
		
		/*=== Rihgt items height at resize ====*/
		
		setTimeout(function() {

            var windowHeight = $('#main-content').height();
            var rightItemNumber = $('.right-item').length;
            var headerHeight = $('#header-2').height();
            var itemHeight = (windowHeight-headerHeight)/(rightItemNumber-1);
            if(itemHeight/($('.right-item').width()) > 1.8) itemHeight = $('.right-item').width()*1.8;
            //$('.right-item-1').height(headerHeight + 'px');
            $('.right-item').height(itemHeight + 'px');
            $('.right-item-1').height(headerHeight + 'px');

			/*=== Stellar.js parallax plugin init ====*/
			$.stellar({
				horizontalScrolling: false,
				verticalOffset: 0
			});
			
		}, 300)

        $(".right-item-icons").css({"margin-top": $('#header-2').height()/2-144+'px'});
        $(".menu-bottom .col1-3").outerHeight($(".menu-bottom .height1").outerWidth());
    $(".menu-bottom .height2").outerHeight($(".menu-bottom .height1").outerWidth()*2);
    $('.menu-bottom .col1-3').outerWidth($(".menu-bottom .height1").outerWidth());

    var col3Width = $('.title-menu-table .col1-3').width();
    $('.title-menu-table .col1-3').outerHeight(col3Width);
    $('.title-menu-table .col2-3').outerHeight(2*col3Width);
	
	});	
	
})(jQuery);

function countDown(second,endMinute,endHour,endDay,endMonth) {
    var now = new Date();
    second = (arguments.length == 1) ? second + now.getSeconds() : second;
    endHour = typeof(endHour) != 'undefined' ?  endHour : now.getHours();
    endMinute = typeof(endMinute) != 'undefined' ? endMinute : now.getMinutes();
    endDay = typeof(endDay) != 'undefined' ?  endDay : now.getDate();
    endMonth = typeof(endMonth) != 'undefined' ? endMonth : now.getMonth();
//добавляем секунду к конечной дате (таймер показывает время уже спустя 1с.)
    var endDate = new Date(now.getFullYear(),endMonth,endDay,endHour,endMinute,second+1);
    var interval = setInterval(function() { //запускаем таймер с интервалом 1 секунду
        var time = endDate.getTime() - now.getTime();
        if (time < 0) {                      //если конечная дата меньше текущей
            var seconds = 0;
            var hours = 0;
            var minutes = 0;
        } else {
            var hours = Math.floor(time / 36e5) % 24;
            var minutes = Math.floor(time / 6e4) % 60;
            var seconds = Math.floor(time / 1e3) % 60;
        }
        $('#hours').text(hours);
        $('#mins').text(minutes);
        $('#secs').text(seconds);
        if (!seconds && !minutes && !days && !hours) {
            clearInterval(interval);
            // alert("Время вышло!");
        }
        now.setSeconds(now.getSeconds() + 1); //увеличиваем текущее время на 1 секунду
    }, 1000);
}