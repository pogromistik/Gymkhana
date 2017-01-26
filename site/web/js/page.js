/*“use strict” mode on*/
"use strict";

//выпадающее меню при наведении
jQuery('ul.nav > li').hover(function () {
    var width = screen.width;
    if (width >= 625) {
        jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).slideToggle(200);
    }
}, function () {
    jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).hide();
});


/*------МЕНЮ ПРИ ПРОЛИСТЫВАНИИ-------*/
$(function () {
    $(window).scroll(function () {
        var width = screen.width;
        if (width >= 991) {
            var top = $(this).scrollTop();
            if ($('#content').hasClass('opacity-menu')) {
                if (top == 0) {
                    $(".nav").css({"background": "none"});
                } else {
                    $(".nav").css({"background": "#242423"});
                }
            } else {
                if (top == 0) {
                    $(".header").css({"background": "none"});
                } else {
                    $(".header").css({"background": "#242423"});
                }
            }
        }
    });
});

$(document).ready(function () {
    if ($('#content').hasClass('small-height')) {
        var width = screen.width;
        if (width >= 800) {
            var margin = $('.footer').outerHeight();
            $('.footer').css({'margin-top': '-' + margin + 'px'});
        }
    }

    $(".top_nav.logo img")
        .mouseover(function () {
            $(".logo2").hide();
            $(".logo1").show();
        })
        .mouseout(function () {
            $(".logo1").hide();
            $(".logo2").show();
        });

    /*скролл в россии*/
    $(".bottom-list").on("click", "a", function (event) {
        //отменяем стандартную обработку нажатия по ссылке
        event.preventDefault();
        //узнаем высоту от начала страницы до блока на который ссылается якорь
        var top = $("#list").offset().top - 50;
        //анимируем переход на расстояние - top за 1500 мс
        $('body,html').animate({scrollTop: top}, 1500);
        $(".bottom-list").hide();
    });

    //слайдеры
    $(".owl-slider").owlCarousel({
        navigation: false,
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem: true,
        autoPlay: 7000,
        pagination: true
    });

    equalizer($('.regular_img .img'));
    equalizer($('.big-show .item'));
    equalizer($('.figure .description .item'));

    //Страница в разработке и страница не найдена
    var SPHeight = $('.system-page').height() - $('.footer').height() - 10;
    if (SPHeight < 270) {
        SPHeight = 270;
    }
    $('.system-page').height(SPHeight);

    $('.help_project .text .item').outerHeight($('.help_project .col-sm-1').outerHeight() - 20);

    //Высота текста у превьюшек
    $('.full-preview .item').each(function (i) {
        $(this).find('.title').height($(this).height());
    });


    /*
     var ww = document.body.clientWidth;
     if(ww <= 1600 && ww > 1500){
     $(".left-video .video-responsive").css({"padding-bottom": "52%"});
     }

     $(function() {
     window.prettyPrint && prettyPrint()
     $(document).on('click', '.yamm .dropdown-menu', function(e) {
     e.stopPropagation()
     })
     })
     */

    /*-------------АНИМИРОВАННЫЕ ПОЛОСКИ----------*/
    if ($('*').is('.my-button')) {

        $('.my-button').on('scrollSpy:enter', function () {

            $(this).children(".my-button-border-top").css('left', '0');
            $(this).children(".my-button-border-right").css('top', '0');
            $(this).children(".my-button-border-bottom").css('right', '0');
            $(this).children(".my-button-border-left").css('bottom', '0');
            $(this).children(".my-button-text").css('opacity', '1');

        });

        $('.my-button').on('scrollSpy:exit', function () {

            $(this).children(".my-button-border-top").css('left', '-100%');
            $(this).children(".my-button-border-right").css('top', '-100%');
            $(this).children(".my-button-border-bottom").css('right', '-100%');
            $(this).children(".my-button-border-left").css('bottom', '-100%')
            $(this).children(".my-button-text").css('opacity', '0');

        });

        $('.my-button').scrollSpy();

    }
    /*-------ЗАГРУЗКА КАРТЫ-------*/
    $(function () {
        $('#resizable .positionDiv').each(function (i) {
            $(this).delay((i++) * 500).fadeTo(700, 1);
            $(this).find('a').delay((i - 1) * 500).fadeOut(700);
        });
        setTimeout(function () {
            $('.bottom-list').fadeTo(700, 1);
        }, 12000);
    });
});


var equalizer = function (equalizer) {
    var maxHeight = 0;

    equalizer.each(function () {
        console.log($(this).height());
        if ($(this).height() > maxHeight) {
            maxHeight = $(this).height()
        }
        ;
    });

    equalizer.height(maxHeight);
}


$(window).on('load', function () {

    setTimeout(function () {
        $("#preloader").fadeOut(300);
    }, 1000);
    /*
     equalizer($('.one_figure .item'));

     $(".track-text").height($(".track-img").height());

     $(".marshal .item").height($(".marshal .all").height());


     $(".al-1").innerHeight($(".galleries .item:first").height());
     $(".al-2").innerHeight($(".galleries .item:first").height());

     $(".video_galery .video-menu .menu").height($(".video_galery .item:last-child").height()/3-0.8);
     $(".video_galery .video-menu .menu a").css({"line-height": $(".video_galery .item:last-child").height()/3-0.8+'px'});
     $(".video_galery .video-menu").height($(".video_galery .item:last-child").height());
     $(".video_galery .item").height($(".video_galery .item:last-child").height());

     $(".help .item").height($(".help .item").parent().parent().find(".col-xs-1").height()-42);
     $(".contact_text").height($(window).height()-58);
     $(".regular-table td .row").css({"min-height":$(window).height()-60});
     $(".regular-table .two .my-button").css({"min-height":$(window).height()-60-$(".regular-table .one").innerHeight()-$(".reg_img").height()});
     $(".one_news .pagetitle").css({"max-height": $(window).height()-60});
     $(".one_competition .pagetitle").css({"max-height": $(window).height()});
     $(".one_project .slider").css({"max-height": $(window).height()-60});
     $(".one_project .slider .item").css({"max-height": $(window).height()-60});
     $(".one_news .slider").css({"max-height": $(window).height()-60});
     $(".one_news .slider .item").css({"max-height": $(window).height()-60});
     $(".galery .left-item-icons").height($(window).height());
     $(".one_figure iframe").height($(".one_figure .time").height()-20);


     $(".video_galery .top").on({
     mouseenter: function () {
     $(".video-responsive iframe").addClass("grayscale");
     },
     mouseleave: function () {
     $(".video-responsive iframe").removeClass("grayscale");
     }
     });

     $(".video_galery .two").css({"padding-top": $(".video_galery .top img").height()});


     /*------СПИСОК ГОДОВ В ГАЛЕРЕЕ ПРИ НАВЕДЕНИИ--------*/

    $(".album h3").click(function () {
        $(".al-bg-none").fadeIn(400);
    });
    /*------СПИСОК ВСЕХ АЛЬБОМОВ В ГАЛЕРЕЕ ФИГУР------*/
    $(".album-title").click(function () {
        $(".al-bg-none").fadeIn(400);
        $('body').css({'overflow': 'hidden'});
    });
    /*------СПИСОК ВСЕХ ФИГУР------*/
    $(".figure h3").click(function () {
        $(".al-bg-none").fadeIn(400);
        $('body').css({'overflow': 'hidden'});
    });
    $(".al-bg-none").click(function () {
        $(this).fadeOut(400);
        $('body').css({'overflow': 'auto'});
    });
    $(".al-bg-none").height($("html").height());
    $(".al-bg-none").width($("html").width());

    /*-------ПОЯВЛЕНИЕ КАРТИНКИ С МЕДВЕДЕМ-------*/
    $(".regular_img .img2").click(function () {
        $(this).fadeOut(2400);
    });

    /*------ПАСХАЛКА С МОТОЦИКЛОМ----------*/
    $("#37").click(function () {
        var now = Math.round(new Date().getTime() / 1000);
        ;
        $(".k-gif").html('<img src="/img/003.gif?' + now + '">');
        $(".k-gif").offset($("#37").offset());
        $(".k-gif img").height($("#37").height());
        $(this).hide();
    });

    /*
     $(".al-bg-none").height($("html").height());
     $(".al-bg-none").width($("html").width());

     $(".figure .name").height($(".figure .item").height()); */

});

$(window).resize(function () {
    $('.full-preview .item').each(function (i) {
        $(this).find('.title').height($(this).height());
    });
    $(".al-bg-none").height($("html").height());
    $(".al-bg-none").width($("html").width());
    $('.help_project .text .item').outerHeight($('.help_project .col-sm-1').outerHeight() - 20);
    /*
     $(".help .item").height($(".help .item").parent().parent().find(".col-xs-1").height()-42);
     $(".contact_text").height($(window).height()-58);
     $(".track-text").height($(".track-img").height());
     $(".marshal .item").height($(".marshal .all").height());
     $(".regular-table td .row").css({"min-height":$(window).height()-60});
     $(".regular-table .two .my-button").css({"min-height":$(window).height()-60-$(".regular-table .one").innerHeight()-$(".reg_img").height()});

     $(".al-1").innerHeight($(".galleries .item:first").height());
     $(".al-2").innerHeight($(".galleries .item:first").height());
     $(".figure .name").height($(".figure .item").height());
     $(".video_galery .two").css({"padding-top": $(".video_galery .top img").height()});

     $(".one_news .pagetitle").css({"max-height": $(window).height()-60});
     $(".one_competition .pagetitle").css({"max-height": $(window).height()});
     $(".one_project .slider").css({"max-height": $(window).height()-60});
     $(".one_project .slider .item").css({"max-height": $(window).height()-60});
     $(".one_news .slider").css({"max-height": $(window).height()-60});
     $(".one_news .slider .item").css({"max-height": $(window).height()-60});
     $(".galery .left-item-icons").height($(window).height());
     $(".one_figure iframe").height($(".one_figure .time").height()-20);
     $("#left-menu").css({"min-width": $(window).width()-$(".left-item").height()});

     $(".video_galery .video-menu .menu").height($(".video_galery .item:last-child").height()/3-0.8);
     $(".video_galery .video-menu .menu a").css({"line-height": $(".video_galery .item:last-child").height()/3-0.8+'px'});
     $(".video_galery .video-menu").height($(".video_galery .item:last-child").height());
     $(".video_galery .item").height($(".video_galery .item:last-child").height());
     */
});

function init() {
    var myMap = new ymaps.Map("map", {
            center: [55.17385743155975, 61.28335570092765],
            zoom: 12
        }),

        // Создаем геообъект с типом геометрии "Точка".
        myGeoObject = new ymaps.GeoObject({
            // Описание геометрии.
            geometry: {
                type: "Point",
                coordinates: [55.17385743155975, 61.28335570092765]
            },
            // Свойства.
            properties: {
                // Контент метки.
                iconContent: 'МЫ',
                balloonContent: 'Меня можно перемещать'
            }
        }, {
            // Опции.
            // Иконка метки будет растягиваться под размер ее содержимого.
            preset: 'twirl#redStretchyIcon',
            // Метку можно перемещать.
            draggable: true
        });

    myMap.controls.add('smallZoomControl');
    // Добавляем все метки на карту.
    myMap.geoObjects
        .add(myGeoObject);
}

//активный пункт меню
(function() {
    var current = '/' + window.location.pathname.split('/')[1];
    $( ".nav a" ).each(function() {
        var elem = $(this);
        if (elem.attr('href') == current) {
            var ul = elem.closest('ul');
            if (ul.hasClass('dropdown-menu')) {
                ul.parent().find('.dropdown-toggle').addClass('active');
            } else {
                elem.addClass('active');
            }
        }
    });
})();