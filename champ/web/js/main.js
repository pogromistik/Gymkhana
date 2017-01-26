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
        elem.parent().find('.background').hide("slide", { direction: "left" }, 500);
        elem.removeClass('active');
        setTimeout(function () {
            elem.css({'color': '#4b4e53'})
        }, 500);
    } else {
        elem.parent().find('.background').show("slide", { direction: "left" }, 300);
        elem.addClass('active');
        elem.css({'color': '#fff'});
    }
    elem.parent().find('.info').slideToggle();
});

//активный пункт меню
(function() {
    var current = '/' + window.location.pathname.split('/')[1];
    $( "#nav a" ).each(function() {
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