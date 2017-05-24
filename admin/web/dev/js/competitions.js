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
                BootboxError(data);
            }
        }).fail(function (error) {
            BootboxError(error.responseText);
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
            } else if (result['warning'] == true) {
                hideBackDrop();
                $('html, body').animate({ scrollTop: $('.complete').offset().top }, 500);
                $('.complete').html(result['data']);
            } else {
                hideBackDrop();
                $('html, body').animate({ scrollTop: $('.complete').offset().top }, 500);
                $('.complete').append(result['error']);
            }
        },
        error: function (result) {
            hideBackDrop();
            BootboxError(result.responseText);
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
            BootboxError(data);
        }
    }).fail(function (error) {
        BootboxError(error.responseText);
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
                BootboxError(result);
            }
        },
        error: function (result) {
            hideBackDrop();
            BootboxError(result.responseText);
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
            BootboxError(data);
        }
    }).fail(function (error) {
        BootboxError(error.responseText);
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
            if (result['success'] == true) {
                form.find('.row').addClass('result-line');
                form.find('.timeId').val(result['id']);
                var next = form.next();
                next.find('input[name="Time[timeForHuman]"]').focus();
                hideBackDrop();
            } else {
                hideBackDrop();
                BootboxError(result['error']);
            }
        },
        error: function (result) {
            hideBackDrop();
            BootboxError(result);
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
            if (result['success'] == true) {
                form.find('.row').addClass('result-line');
                if (next.attr('id')) {
                    AddAllResults(next);
                } else {
                    hideBackDrop();
                    location.reload();
                }
            } else {
                alert(result['error']);
                if (next.attr('id')) {
                    AddAllResults(next);
                } else {
                    hideBackDrop();
                    location.reload();
                }
            }
        },
        error: function (result) {
            hideBackDrop();
            BootboxError(result.responseText);
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
            hideBackDrop();
            BootboxError(data);
        }
    }).fail(function (error) {
        hideBackDrop();
        BootboxError(error.responseText);
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
        hideBackDrop();
        BootboxError(error.responseText);
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
    showBackDrop();
    $.get('/competitions/participants/set-classes', {
        stageId: stageId
    }).done(function (data) {
        hideBackDrop();
        if (data == true) {
            alert('Классы успешно установлены');
            location.reload();
        } else {
            BootboxError(data);
            console.log(data);
        }
    }).fail(function (error) {
        hideBackDrop();
        BootboxError(error.responseText);
        console.log(error);
    });
});

$('.getRequest').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var action = elem.data('action');
    var id = elem.data('id');
    showBackDrop();
    $.get(action, {
        id: id
    }).done(function (data) {
        hideBackDrop();
        if (data == true) {
            location.reload();
        } else {
            BootboxError(data);
            console.log(data);
        }
    }).fail(function (error) {
        hideBackDrop();
        BootboxError(error.responseText);
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
        showBackDrop();
        $.get(action, {
            id: id
        }).done(function (data) {
            hideBackDrop();
            if (data == true) {
                location.reload();
            } else {
                BootboxError(data);
                console.log(data);
            }
        }).fail(function (error) {
            hideBackDrop();
            BootboxError(error);
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
                BootboxError(data);
            }
        }).fail(function (error) {
            BootboxError(error.responseText);
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
                BootboxError(data);
            }
        }).fail(function (error) {
            BootboxError(error.responseText);
        });
    }
});

$('.deleteCabinet').click(function (e) {
    e.preventDefault();
    if (confirm("Уверены, что хотите удалить кабинет этого спортсмена?")) {
        var id = $(this).data('id');
        $.get('/competitions/athlete/delete-cabinet', {
            athleteId: id
        }).done(function (data) {
            if (data == true) {
                location.reload();
            } else {
                BootboxError(data);
            }
        }).fail(function (error) {
            BootboxError(error.responseText);
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
                BootboxError(result);
            }
        },
        error: function (result) {
            hideBackDrop();
            BootboxError(result);
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
            BootboxError(data);
        }
    }).fail(function (error) {
        hideBackDrop();
        BootboxError(error.responseText);
    });
});

function BootboxError(text) {
    bootbox.dialog({
        locale: 'ru',
        title: 'Ошибка!',
        message: text,
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
}

$('.registrationOldAthlete').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var tmpId = elem.data('tmp-id');
    var athleteId = elem.data('athlete-id');
    var hasAllMotorcycles = elem.data('all-motorcycles');

    if (hasAllMotorcycles) {
        createLK(tmpId, athleteId);
    } else {
        changeMotorcycles(tmpId, athleteId);
    }
});

function createLK(tmpId, athleteId) {
    showBackDrop();
    $.get('/competitions/tmp-athletes/registration-old-athlete', {
        tmpId: tmpId, athleteId: athleteId
    }).done(function (data) {
        if (data == true) {
            location.reload();
        } else {
            hideBackDrop();
            BootboxError(data);
        }
    }).fail(function (error) {
        hideBackDrop();
        BootboxError(error.responseText);
    });
}

function changeMotorcycles(tmpId, athleteId) {
    showBackDrop();
    $.get('/competitions/tmp-athletes/change-motorcycles', {
        tmpId: tmpId, athleteId: athleteId
    }).done(function (data) {
        if (data['error']) {
            hideBackDrop();
            BootboxError(data['error']);
        } else {
            hideBackDrop();
            $('.modalMotorcycles').html(data['page']);
            $('#changeMotorcycles').modal('show')
        }
    }).fail(function (error) {
        hideBackDrop();
        BootboxError(error.responseText);
    });
}

$(document).on("submit", '.addMotorcycleAndRegistration', function (e) {
    e.preventDefault();
    var form = $(this);
    form.find('.button').hide();
    form.find('.wait-text').text('Пожалуйста, подождите...');
    form.find('.alert').hide();
    $.ajax({
        url: '/competitions/tmp-athletes/add-motorcycles-and-registration',
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                location.reload();
            } else {
                form.find('.button').show();
                form.find('.wait-text').text('');
                form.find('.alert-danger').text(result).show();
            }
        },
        error: function (result) {
            form.find('.button').hide();
            form.find('.wait-text').text('');
            form.find('.alert-danger').text(result.responseText).hide();
        }
    });
});

function cityForNewAthlete() {
    showBackDrop();
    $.ajax({
        url: '/competitions/tmp-athletes/save-new-city',
        type: "POST",
        data: $('#cityForNewAthlete').serialize(),
        success: function (result) {
            hideBackDrop();
            if (result == true) {
                alert('Город сохранен');
            } else {
                BootboxError(result);
            }
        },
        error: function (result) {
            hideBackDrop();
            BootboxError(result.responseText);
        }
    });
}

function cityForNewParticipant() {
    showBackDrop();
    $.ajax({
        url: '/competitions/tmp-participant/save-new-city',
        type: "POST",
        data: $('#cityForNewParticipant').serialize(),
        success: function (result) {
            hideBackDrop();
            if (result == true) {
                alert('Город сохранен');
            } else {
                BootboxError(result);
            }
        },
        error: function (result) {
            hideBackDrop();
            BootboxError(result.responseText);
        }
    });
}

$(document).on("submit", '#figureTimeForStage', function (e) {
    e.preventDefault();
    showBackDrop();
    var form = $(this);
    $.ajax({
        url: "/competitions/stages/check-figure-time",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            hideBackDrop();
            $('.calculate-class').html(result);
        },
        error: function (result) {
            hideBackDrop();
            alert(result);
        }
    });
});

$(document).on("submit", '#addFigureTimeForStage', function (e) {
    e.preventDefault();
    showBackDrop();
    var form = $(this);
    $.ajax({
        url: "/competitions/stages/add-figure-time",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            hideBackDrop();
            $('#figureTimeForStage').trigger('reset');
            $('.calculate-class').html(result);
        },
        error: function (result) {
            hideBackDrop();
            alert(result);
        }
    });
});