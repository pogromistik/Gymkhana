$('.changeMotorcycleStatus').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    showBackDrop();
    var id = elem.data('id');
    $.get('/profile/change-status', {
        id: id
    }).done(function (data) {
        if (data == true) {
            location.reload(true);
        } else {
            alert(data);
        }
    }).fail(function (error) {
        alert(error.responseText);
    });
});

$(document).on("submit", '.newQuestion', function (e) {
    e.preventDefault();
    var form = $(this);

    form.find('.form-text').text('Пожалуйста, подождите...');
    form.find('.button').hide();
    form.find('.alert').hide();

    $.ajax({
        url: "/site/add-feedback",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                form.find('.alert-success').text('Ваш запрос успешно отправлен.').show();
                form.trigger('reset');
            } else {
                form.find('.alert-danger').text(result).show();
            }
            form.find('.form-text').hide();
            form.find('.button').show();
        }
    });
});

$(document).on("submit", '.newRegistration', function (e) {
    e.preventDefault();
    var form = $(this);
    var action = form.data('action');

    form.find('.form-text').text('Пожалуйста, подождите...');
    form.find('.button').hide();
    form.find('.alert').hide();

    $.ajax({
        url: "/competitions/" + action,
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                form.trigger('reset');
                form.find('.alert-success').text('Вы успешно зарегистрированы на этап.').show();
                $('.enrollForm').slideToggle();
                $('.enrollForm-success').html('<div class="alert alert-success">' +
                    'Вы успешно зарегистрированы на этап.</div>');
                $('#enrollFormHref').show();
                if ($('.alerts').hasClass('no-scroll')) {
                } else {
                    $('body').animate({scrollTop: $('#enrollFormHref').offset().top-20}, 500);
                }
            } else {
                form.find('.alert-danger').text(result).show();
                if ($('.alerts').hasClass('no-scroll')) {
                } else {
                    $('body').animate({scrollTop: $('.alerts').offset().top}, 500);
                }
            }
            form.find('.form-text').hide();
            form.find('.button').show();
        }
    });
});

$('#enrollFormHref').click(function (e) {
    e.preventDefault();
    $('.enrollForm').slideToggle();
    $('body').animate({scrollTop: $('.enrollForm').offset().top}, 500);
    $(this).hide();
});

$('.freeNumbersList').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var id = elem.data('id');
    $.get('/competitions/get-free-numbers', {
        stageId: id
    }).done(function (data) {
        if (data['success'] == true) {
            $('#enrollForm').animate({scrollTop: $('.button').offset().top + 100}, 500);
            $('.free-numbers .list').html(data['numbers']);
            $('.free-numbers').show();
        } else {
            alert(data['error']);
        }
    }).fail(function (error) {
        alert(error.responseText);
    });
});

$(document).on("submit", '#compareWith', function (e) {
    e.preventDefault();
    var form = $(this);

    $('.alert').hide();
    showBackDrop();

    $.ajax({
        url: "/profile/check-compare-with",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result['error']) {
                $('.alert-danger').text(result['error']).show();
                hideBackDrop();
            } else {
                $('.result').html(result['data']);
                hideBackDrop();
            }
        }
    });
});

function figureFilters() {
    var form = $('#figureFilterForm');
    var id = form.data('id');

    $('.alert-danger').hide();

    showBackDrop();
    $.ajax({
        url: '/competitions/figure-results-with-filters',
        type: 'POST',
        data: form.serialize(),
        success: function (result) {
            if (result['error']) {
                $('.alert-danger').text(result['error']).show();
            } else {
                $('.results').html(result['data']);
            }
            hideBackDrop();
        }
    });
}

$('.showAll').click(function (e) {
    e.preventDefault();
    $('#showAll').val(true);
    figureFilters();
});

$('.deletePhoto').click(function (e) {
    e.preventDefault();
    showBackDrop();
    $.get('/profile/delete-photo').done(function (data) {
        if (data == true) {
            location.reload();
        } else {
            hideBackDrop();
            alert(data);
        }
    }).fail(function (error) {
        hideBackDrop();
        alert(error.responseText);
    });
});
