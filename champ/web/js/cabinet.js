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
            } else {
                form.find('.alert-danger').text(result).show();
            }
            form.find('.form-text').hide();
            form.find('.button').show();
        }
    });
});