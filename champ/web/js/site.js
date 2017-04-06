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
/*
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
*/

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

var equalizer = function (equalizer) {
    var maxHeight = 0;

    equalizer.each(function () {
        console.log($(this).height());
        if ($(this).height() > maxHeight) {
            maxHeight = $(this).height()
        }
    });

    equalizer.height(maxHeight);
};

function initAffixCheck() {
    "use strict";
    var e = $(".header");
    var windowT = $(window);
    e.affix({offset: {top: 1}}), windowT.width() < 1025 && (windowT.off(".affix"), e.removeData("bs.affix").removeClass("affix affix-top affix-bottom"))
}

$(document).ready(function () {
    equalizer($('.athletes .item .card'));
    $('ul.dropdown-menu [data-toggle=dropdown]').on('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).parent().siblings().removeClass('open');
        $(this).parent().toggleClass('open');
    });
    initAffixCheck();
});

$('.toggle .title').click(function () {
    $(this).parent().find('.toggle-content').slideToggle();
});

$('#cityNotFound').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    $('#city-list').slideToggle();
    $('#city-text').slideToggle();
    if (elem.hasClass('list')) {
        elem.removeClass('list');
        elem.addClass('text');
        elem.text('Вернуть список городов');
    } else {
        $('#city-text-input').val(null);
        elem.removeClass('text');
        elem.addClass('list');
        elem.text('Нажмите, если вашего города нет в списке');
    }
});

$(document).on("submit", '.registrationAthlete', function (e) {
    e.preventDefault();
    var form = $(this);
    showBackDrop();
    $('.alert').hide();

    $.ajax({
        url: "/site/add-registration",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            $('html, body').animate({ scrollTop: $('.modal-footer').offset().top }, 500);
            if (result == true) {
                form.find('.alert-success').text('Ваша заявка успешно отправлена. Пароль для доступа в личный кабинет будет' +
                    'отправлен на указанную почту в течение 24 часов (если письма нет - проверьте папку спам). Если этого не произойдёт - пожалуйста, сообщите нам.').show();
                form.trigger('reset');
            } else {
                form.find('.alert-danger').text(result).show();
            }
            hideBackDrop();
        },
        error: function (error) {
            alert(error.responseText);
            hideBackDrop();
        }
    });
});

$('.appendMotorcycle').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var i = elem.data('i');
    if (i > 1) {
        $('.alert-danger').text('Нельзя добавить больше 3 мотоциклов. При необходимости, вы можете добавить их потом в личном кабинете').show();
        return false;
    }
    $.get('/site/append-motorcycle', {
        i: i
    }).done(function (data) {
        elem.data('i', i+1);
        $('.motorcycles').append(data);
    }).fail(function (error) {
        alert(error.responseText);
    });
});

$('.change-result-scheme').click(function (e) {
    e.preventDefault();
    $('.result-scheme').slideToggle();
});

$(document).on("submit", '#resetPasswordForm', function (e) {
    e.preventDefault();
    var form = $(this);
    showBackDrop();
    $('.alert').hide();

    $.ajax({
        url: "/site/send-mail-for-reset-password",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                form.find('.alert-success').text('На email, указанный в вашем профиле отправлено письмо для восстановления пароля.').show();
                form.trigger('reset');
            } else {
                form.find('.alert-danger').text(result).show();
            }
            hideBackDrop();
        },
        error: function (error) {
            alert(error.responseText);
            hideBackDrop();
        }
    });
});