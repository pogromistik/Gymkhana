function showBackDrop() {
    $('<div class="modal-backdrop fade in"></div>').appendTo(document.body);
}
function hideBackDrop() {
    $(".modal-backdrop").remove();
}

//выпадающее меню при наведении
jQuery('ul.nav > li').hover(function () {
    var width=screen.width;
    if (width >= 625) {
        jQuery(this).find('.dropdown-menu').fadeIn(500);
    }
}, function () {
    jQuery(this).find('.dropdown-menu').stop(true, true).delay(200).hide();
})

//анимация списков
$('.list .item .toggle .title').click(function () {
    var elem = $(this);
    if (elem.hasClass('active')) {
        elem.parent().find('.background').first().hide("slide", { direction: "left" }, 500);
        elem.removeClass('active');
        setTimeout(function () {
            elem.css({'color': '#4b4e53'})
        }, 500);
    } else {
        elem.parent().find('.background').first().show("slide", { direction: "left" }, 300);
        elem.addClass('active');
        elem.css({'color': '#fff'});
    }
    elem.parent().find('.info').first().slideToggle();
});

/*------МЕНЮ ПРИ ПРОЛИСТЫВАНИИ-------*/
$(function () {
    $(window).scroll(function () {
        var width = screen.width;
        if (width >= 991) {
            var top = $(this).scrollTop();
            if (top == 0) {
                $(".header").css({"background": "none"});
            } else {
                $(".header").css({"background": "#f4f4f4"});
            }
        }
    });
});

//активный пункт меню
(function() {
    var current = '/' + window.location.pathname.split('/')[1] + '/' + window.location.pathname.split('/')[2];
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