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

$('.closeNotices').click(function () {
    $('.modal-notices').slideToggle('200', function () {
        $('.modal-notices').removeClass('show');
    });
});

$('.notices').click(function () {
    if ($('.modal-notices').hasClass('show')) {
        $('.modal-notices').slideToggle(200, function () {
            $('.modal-notices').removeClass('show');
        });
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