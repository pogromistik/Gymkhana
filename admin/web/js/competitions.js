$(document).on("submit", '#newAthlete', function (e) {
    e.preventDefault();
    var form = $(this);
    newAthlete(form.serialize());
});

$('.removeOverallFile').click(function (e) {
    e.preventDefault();
    if (confirm("Уверены, что хотите удалить этот файл?")) {
        var id = $(this).data('id');
        $.get('/competitions/documents/remove-file', {
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
    }
});

function newAthleteConfirm() {
    var form = $('#newAthlete');
    newAthlete(form.serialize() + '&confirm=1');
}

function newAthlete(data) {
    showBackDrop();
    $.ajax({
        url: "/competitions/athlete/add-athlete",
        type: "POST",
        data: data,
        success: function (result) {
            if (result['success'] == true) {
                location.href = '/competitions/athlete/update?id=' + result['data'];
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
    $('.alert-danger').hide();
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
                        location.href = action + '&success=1';
                        break;
                    case 'withoutId':
                        location.href = action + '?success=1';
                        break;
                }
            } else if (result['hasCity'] == true) {
                switch (actionType) {
                    case 'withId':
                        location.href = action + '&errorCity=1';
                        break;
                    case 'withoutId':
                        location.href = action + '?errorCity=1';
                        break;
                }
            } else if (result['error']) {
                hideBackDrop();
                $('.alert-danger').text(result['error']).show();
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

$(document).on("submit", '#newRegionForm', function (e) {
    e.preventDefault();
    showBackDrop();
    $('.alert-danger').hide();
    var form = $(this);
    var action = form.data('action');
    var actionType = form.data('action-type');
    $.ajax({
        url: "/competitions/help/add-region",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result['success'] == true) {
                switch (actionType) {
                    case 'withId':
                        location.href = action + '&success=1';
                        break;
                    case 'withoutId':
                        location.href = action + '?success=1';
                        break;
                }
            } else if (result['hasRegion'] == true) {
                switch (actionType) {
                    case 'withId':
                        location.href = action + '&errorCity=2';
                        break;
                    case 'withoutId':
                        location.href = action + '?errorCity=2';
                        break;
                }
            } else if (result['error']) {
                hideBackDrop();
                $('.alert-danger').text(result['error']).show();
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
                form.find('.row').addClass('result-line');
                var next = form.next();
                next.find('input[name="Time[timeForHuman]"]').focus();
                hideBackDrop();
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

$('.saveAllStageResult').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var attempt = elem.data('attempt');
    showBackDrop();
    var form = $('.form-'+attempt+':first');
    AddAllResults(form);
});

function AddAllResults(form) {
    $.ajax({
        url: '/competitions/participants/add-time',
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            var next = form.next();
            if (result == true) {
                form.find('.row').addClass('result-line');
                if (next.attr('id')) {
                    AddAllResults(next);
                } else {
                    hideBackDrop();
                }
            } else {
                alert(result);
                if (next.attr('id')) {
                    AddAllResults(next);
                } else {
                    hideBackDrop();
                }
            }
        },
        error: function (result) {
            hideBackDrop();
            alert(result);
        }
    });
}
/*
$('.saveAllStageResult').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var attempt = elem.data('attempt');
    var count = elem.data('count');
    showBackDrop();
    var i = 1;
    $(".form-"+attempt).each(function() {
        var form = $(this);
        AddAllResults(form, i, count);
        i++;
    });
});

function AddAllResults(form, i, count) {
    $.ajax({
        url: '/competitions/participants/add-time',
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                form.find('.row').addClass('result-line');
                if (i == count) {
                    hideBackDrop();
                }
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
}
*/
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

$('.setParticipantsClasses').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var stageId = elem.data('id');
    $.get('/competitions/participants/set-classes', {
        stageId: stageId
    }).done(function (data) {
        hideBackDrop();
        if (data == true) {
            alert('Классы успешно установлены');
            location.reload();
        } else {
            alert(data);
            console.log(data);
        }
    }).fail(function (error) {
        alert(error.responseText);
        console.log(error);
    });
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
            bootbox.dialog({
                locale: 'ru',
                title: 'Ошибка!',
                message: data,
                className: 'danger',
                buttons: {
                    confirm: {
                        label: 'ОК',
                        className: "btn-primary",
                        callback: function () {
                            return true;
                        }
                    }
                }
            });
            console.log(data);
        }
    }).fail(function (error) {
        bootbox.dialog({
            locale: 'ru',
            title: 'Ошибка!',
            message: error.responseText,
            className: 'danger',
            buttons: {
                confirm: {
                    label: 'ОК',
                    className: "btn-primary",
                    callback: function () {
                        return true;
                    }
                }
            }
        });
        console.log(error);
    });
});

$('.getRequestWithConfirm').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var text = elem.data('text');
    if (confirm(text)) {
        var action = elem.data('action');
        var id = elem.data('id');
        $.get(action, {
            id: id
        }).done(function (data) {
            hideBackDrop();
            if (data == true) {
                location.reload();
            } else {
                bootbox.dialog({
                    locale: 'ru',
                    title: 'Ошибка!',
                    message: data,
                    className: 'danger',
                    buttons: {
                        confirm: {
                            label: 'ОК',
                            className: "btn-primary",
                            callback: function () {
                                return true;
                            }
                        }
                    }
                });
                console.log(data);
            }
        }).fail(function (error) {
            bootbox.dialog({
                locale: 'ru',
                title: 'Ошибка!',
                message: error.responseText,
                className: 'danger',
                buttons: {
                    confirm: {
                        label: 'ОК',
                        className: "btn-primary",
                        callback: function () {
                            return true;
                        }
                    }
                }
            });
            console.log(error);
        });
    }
});

$('.cancelFigureResult').click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('.alert').hide();
    $('#id').val(id);
    $('#cancelFigureResult').modal('show')
});

$(document).on("submit", '#cancelFigureResultForm', function (e) {
    e.preventDefault();
    var form = $(this);
    var id = form.data('id');
    $.ajax({
        url: '/competitions/tmp-figures/cancel-result',
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                location.reload();
            } else {
                $('.alert-danger').text(result).show();
            }
        },
        error: function (result) {
            $('.alert-danger').text(result).show();
        }
    });
});

$('.stageCalcResult').click(function (e) {
    e.preventDefault();
    if (confirm("Уверены, что хотите пересчитать результаты этого этапа?")) {
        var id = $(this).data('id');
        $.get('/competitions/stages/calculation-result', {
            id: id
        }).done(function (data) {
            if (data == true) {
                location.href = '/competitions/stages/result?stageId=' + id;
            } else {
                alert(data);
            }
        }).fail(function (error) {
            alert(error.responseText);
        });
    }
});

$('.createCabinet').click(function (e) {
    e.preventDefault();
    if (confirm("Уверены, что хотите создать кабинет этому спортсмену?")) {
        var id = $(this).data('id');
        $.get('/competitions/athlete/create-cabinet', {
            athleteId: id
        }).done(function (data) {
            if (data == true) {
                location.reload();
            } else {
                alert(data);
            }
        }).fail(function (error) {
            alert(error.responseText);
        });
    }
});

$(document).on("submit", '#figureTimeForm', function (e) {
    e.preventDefault();
    showBackDrop();
    var form = $(this);
    var figureId = form.data('id');
    var date = form.data('date');
    $.ajax({
        url: '/competitions/figures/add-time',
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                location.href = '/competitions/figures/add-results?figureId=' + figureId +
                    '&date=' + date + '&success=true';
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

//количество слов
$(document).ready(function () {
    $("#smallText").keyup(function () {
        var box = $(this).val();
        var count = 255 - box.length;

        if (count >= 0) {
            $('#length').html('осталось символов: ' + count);
        } else {
            $('#length').removeClass('color-green');
            $('#length').addClass('color-red');
            $('#length').html('количество символов превышено на ' + count + 'шт.');
        }
        return false;
    });
});

$('.deletePhoto').click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var model = $(this).data('model');
    showBackDrop();
    $.get('/competitions/help/delete-photo', {
        id: id,
        modelId: model
    }).done(function (data) {
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