$(document).on("submit", '#newAthlete', function (e) {
    e.preventDefault();
    var form = $(this);
    newAthlete(form.serialize());
});

function newAthleteConfirm() {
    var form = $('#newAthlete');
    newAthlete(form.serialize()+'&confirm=1');
}

function newAthlete(data) {
    showBackDrop();
    $.ajax({
        url: "/competitions/athlete/add-athlete",
        type: "POST",
        data: data,
        success: function (result) {
            if (result['success'] == true) {
                location.href = '/competitions/athlete/update?id='+result['data'];
            } else {
                hideBackDrop();
                $('.complete').html(result['data']);
            }
        },
        error: function (result) {
            hideBackDrop();
            alert(result);
        }
    });
}

$('.changeMotorcycleStatus').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    showBackDrop();
    var id = elem.data('id');
    $.get('/competitions/motorcycles/change-status', {
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

$(document).on("submit", '#newRegionalGroup', function (e) {
    e.preventDefault();
    showBackDrop();
    var form = $(this);
    $.ajax({
        url: "/competitions/championships/add-group",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                location.reload();
            } else {
                hideBackDrop();
                alert(result);
            }
        },
        error: function (result) {
            hideBackDrop();
            alert(result);
        }
    });
});

$(document).on("submit", '#newCityForm', function (e) {
    e.preventDefault();
    showBackDrop();
    var form = $(this);
    var action = form.data('action');
    var actionType = form.data('action-type');
    $.ajax({
        url: "/competitions/help/add-city",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result['success'] == true) {
                switch (actionType) {
                    case 'withId':
                        location.href = action+'&success=1';
                        break;
                    case 'withoutId':
                        location.href = action+'?success=1';
                        break;
                }
            } else if (result['hasCity'] == true) {
                switch (actionType) {
                    case 'withId':
                        location.href = action+'&errorCity=1';
                        break;
                    case 'withoutId':
                        location.href = action+'?errorCity=1';
                        break;
                }
            } else {
                hideBackDrop();
                alert('Возникла ошибка при сохранении данных');
            }
        },
        error: function (result) {
            hideBackDrop();
            alert(result);
        }
    });
});

$(document).on("submit", '#ajaxForm', function (e) {
    e.preventDefault();
    showBackDrop();
    var form = $(this);
    var url = form.attr('action');
    $.ajax({
        url: url,
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                location.reload();
            } else {
                hideBackDrop();
                alert(result);
            }
        },
        error: function (result) {
            hideBackDrop();
            alert(result);
        }
    });
});

$('.change-status').click(function (e) {
    e.preventDefault();
    showBackDrop();
    var elem = $(this);
    var url = elem.data('action');
    var id = elem.data('id');
    var status = elem.data('status');
    $.get(url, {
        id: id, status: status
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

$(document).on("submit", '.raceTimeForm', function (e) {
    e.preventDefault();
    showBackDrop();
    var form = $(this);
    $.ajax({
        url: '/competitions/participants/add-time',
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                location.reload();
            } else {
                hideBackDrop();
                alert(result);
            }
        },
        error: function (result) {
            hideBackDrop();
            alert(result);
        }
    });
});

$('.changeParticipantStatus').click(function (e) {
    e.preventDefault();
    showBackDrop();
    var elem = $(this);
    var id = elem.data('id');
    $.get('/competitions/participants/change-status', {
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

$('.findByFirstName').click(function (e) {
    e.preventDefault();
    showBackDrop();
    var elem = $(this);
    var lastName = elem.data('last-name');
    $.get('/competitions/tmp-participant/find-athletes', {
        lastName: lastName
    }).done(function (data) {
        hideBackDrop();
        $('.modalList').html(data);
        $('#athletesList').modal('show')
    }).fail(function (error) {
        alert(error.responseText);
    });
});

$('.addAndRegistration').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var id = elem.data('id');
    bootbox.dialog({
        locale: 'ru',
        title: 'Создание и регистрация спортсмена',
        message: 'При подтверждении действия будет создан и зарегистрирован новый спортсмен. ' +
        'Пожалуйста, нажмите "отмена", если в системе уже есть данные об этом спортсмене.',
        className: 'info',
        buttons: {
            confirm: {
                label: 'Добавить',
                className: "btn-success",
                callback: function () {
                    showBackDrop();
                    $.get('/competitions/tmp-participant/add-and-registration', {
                        id: id
                    }).done(function (data) {
                        hideBackDrop();
                        if (data == true) {
                            location.reload();
                        } else {
                            alert(data);
                        }
                    }).fail(function (error) {
                        alert(error.responseText);
                    });
                }
            },
            cancel: {
                label: 'Отмена',
                className: "btn-translate-handbook btn-primary",
                callback: function () {
                    return true;
                }
            }
        }
    });
});

$('.cancelTmpParticipant').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var id = elem.data('id');
    bootbox.dialog({
        locale: 'ru',
        title: 'Отмена заявки',
        message: 'Этим действием вы подтверждаете, что данный участник уже зарегистрирован на этот этап.',
        className: 'warning',
        buttons: {
            confirm: {
                label: 'Да',
                className: "btn-success",
                callback: function () {
                    showBackDrop();
                    $.get('/competitions/tmp-participant/cancel', {
                        id: id
                    }).done(function (data) {
                        hideBackDrop();
                        location.reload();
                    }).fail(function (error) {
                        alert(error.responseText);
                    });
                }
            },
            cancel: {
                label: 'Нет',
                className: "btn-translate-handbook btn-primary",
                callback: function () {
                    return true;
                }
            }
        }
    });
});

$('.registrationAthlete').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var tmpParticipantId = elem.data('tmp-id');
    var athleteId = elem.data('athlete-id');
    var motorcycleId = elem.data('motorcycle-id');
    bootbox.dialog({
        locale: 'ru',
        title: 'Подтверждение регистрации',
        message: 'Вы уверены? Выбранный спортсмен будет зарегистрирован на этот этап на указанном мотоцикле.',
        className: 'warning',
        buttons: {
            confirm: {
                label: 'Да',
                className: "btn-success",
                callback: function () {
                    showBackDrop();
                    $.get('/competitions/tmp-participant/registration', {
                        tmpParticipantId: tmpParticipantId,
                        athleteId: athleteId,
                        motorcycleId: motorcycleId
                    }).done(function (data) {
                        hideBackDrop();
                        if (data == true) {
                            location.reload();
                        } else {
                            alert(data);
                        }
                    }).fail(function (error) {
                        alert(error.responseText);
                    });
                }
            },
            cancel: {
                label: 'Нет',
                className: "btn-translate-handbook btn-primary",
                callback: function () {
                    return true;
                }
            }
        }
    });
});

$('.addMotorcycleAndRegistration').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var tmpParticipantId = elem.data('tmp-id');
    var athleteId = elem.data('athlete-id');
    bootbox.dialog({
        locale: 'ru',
        title: 'Подтверждение регистрации',
        message: 'Выбранному спортсмену будет добавлен новый мотоцикл. На этом мотоцикле участник будет зарегистрирован ' +
        'на этап. Вы уверены в этом действии?',
        className: 'warning',
        buttons: {
            confirm: {
                label: 'Да',
                className: "btn-success",
                callback: function () {
                    showBackDrop();
                    $.get('/competitions/tmp-participant/add-motorcycle-and-registration', {
                        tmpParticipantId: tmpParticipantId,
                        athleteId: athleteId
                    }).done(function (data) {
                        hideBackDrop();
                        if (data == true) {
                            location.reload();
                        } else {
                            alert(data);
                        }
                    }).fail(function (error) {
                        alert(error.responseText);
                    });
                }
            },
            cancel: {
                label: 'Нет',
                className: "btn-translate-handbook btn-primary",
                callback: function () {
                    return true;
                }
            }
        }
    });
});