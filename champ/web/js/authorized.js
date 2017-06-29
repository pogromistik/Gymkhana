setTimeout(function () {
    checkNotifications();
}, 5000);

function checkNotifications() {
    $.ajax({
        url: "/notices/count",
        type: "POST",
        success: function (result) {
            if (result > 0) {
                $('#newNotices').html('<sup>(' + result + ')</sup>');
                $('#newNoticesMobile').html('<sup>(' + result + ')</sup>');
            }
            setTimeout(function () {
                checkNotifications();
            }, 5000);
        },
        error: function (e) {
            setTimeout(function () {
                checkNotifications();
            }, 5000);
        }
    });
}

$(document).ready(function () {
    checkNotifications();
});

/*$('.closeNotices').click(function () {
    $('.modal-notices').slideToggle('200', function () {
        $('.modal-notices').removeClass('show');
    });
});*/

$('html').click(function () {
    if ($('.modal-notices').hasClass('show')) {
        $('.modal-notices').slideToggle('200', function () {
            $('.modal-notices').removeClass('show');
        });
    }
});

$('.notices').click(function () {
    if ($('.modal-notices').hasClass('show')) {
        return true;
    } else {
        $.get('/notices/find-new-notices').done(function (data) {
            $('.modal-notices .text').html(data);
            $('.modal-notices').slideToggle(200, function () {
                $('.modal-notices').addClass('show');
            });
            $('#newNotices').html('');
            $('#newNoticesMobile').html('');
        }).fail(function (error) {
            alert(error.responseText);
        });
    }
});

$('.getRequest').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var action = elem.data('action');
    var id = elem.data('id');
    $.get(action, {
        id: id
    }).done(function (data) {
        hideBackDrop();
        if (data == true) {
            location.reload();
        } else {
            hideBackDrop();
            alert(data);
            console.log(data);
        }
    }).fail(function (error) {
        hideBackDrop();
        alert(error.responseText);
        console.log(error);
    });
});

$(document).on("submit", '#sendFigureResult', function (e) {
    e.preventDefault();
    var form = $(this);

    $('.alert').hide();
    showBackDrop();

    $.ajax({
        url: "/figures/send",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                $('.alert-success').text('Результат отправлен. После обработки запроса Вам придёт соответствующее уведомление. В случае подтверждения результата, он появится на сайте.').show();
                form.trigger('reset');
                hideBackDrop();
            } else {
                $('.alert-danger').text(result).show();
                hideBackDrop();
            }
        }
    });
});

$(document).on("submit", '#changeClassRequest', function (e) {
    e.preventDefault();
    var form = $(this);

    $('.alert').hide();
    showBackDrop();

    $.ajax({
        url: "/profile/send-class-request",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                $('.alert-success').text('Запрос отправлен. После его рассмотрения вам придёт уведомление в личный кабинет.').show();
                form.trigger('reset');
                hideBackDrop();
            } else {
                $('.alert-danger').text(result).show();
                hideBackDrop();
            }
        }
    });
});