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
                location.href = '/competitions/athlete/update?id=' + result['data'] + '#motorcycles';
            } else {
                hideBackDrop();
                $('.complete').html(result['data']);
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

var clickCount = 0;
$(document).on("submit", '.raceTimeForm', function (e) {
    clickCount++;
    e.preventDefault();
    if (clickCount === 1) {
        showBackDrop();
        var form = $(this);
        $.ajax({
            url: '/competitions/participants/add-time',
            type: "POST",
            data: form.serialize(),
            success: function (result) {
                clickCount = 0;
                if (result['success'] == true) {
                    form.find('.row').addClass('result-line');
                    var btn = form.find('.btn');
                    if (btn.hasClass('btn-green')) {
                        btn.removeClass('btn-green');
                        btn.addClass('btn-blue');
                    }
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
                clickCount = 0;
                hideBackDrop();
                BootboxError(result);
            }
        });
    }
});

$('.saveAllStageResult').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var attempt = elem.data('attempt');
    showBackDrop();
    var form = $('.form-' + attempt + ':first');
    AddAllResults(form);
});

function AddAllResults(form) {
    $.ajax({
        url: '/competitions/participants/add-time?checkTime=0',
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
    var status = elem.data('status');
    $.get('/competitions/participants/change-status', {
        id: id,
        status: status
    }).done(function (data) {
        if (data['success'] == true) {
            location.reload(true);
        } else if (data['needClarification'] == true) {
            hideBackDrop();
            bootbox.dialog({
                locale: 'ru',
                title: 'Регистрация спортсмена',
                message: data['text'],
                className: 'info',
                buttons: {
                    cancel: {
                        label: 'Отмена',
                        className: "btn-danger",
                        callback: function () {
                            return true;
                        }
                    },
                    confirm: {
                        label: 'Добавить',
                        className: "btn-success",
                        callback: function () {
                            showBackDrop();
                            $.get('/competitions/participants/change-status', {
                                id: id, status: status, confirmed: true
                            }).done(function (data) {
                                hideBackDrop();
                                if (data['success'] == true) {
                                    location.reload();
                                } else {
                                    alert(data['text']);
                                }
                            }).fail(function (error) {
                                alert(error.responseText);
                            });
                        }
                    }
                }
            });
        } else {
            hideBackDrop();
            BootboxError(data['text']);
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
    $('.alert:not(.required-alert-info):not(.help-alert)').hide();
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

$('.accruePoints').click(function (e) {
    e.preventDefault();
    if (confirm("Уверены, что хотите начислить баллы участникам? Лучше это делать после того, как переходы спортсмена в новую группу были подтверждены")) {
        var id = $(this).data('id');
        $.get('/competitions/stages/accrue-points', {
            stageId: id
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
            if (result['success'] == true) {
                location.href = '/competitions/figures/add-results?figureId=' + figureId +
                    '&date=' + date + '&success=true';
            } else if (result['confirm'] == true) {
                hideBackDrop();
                bootbox.dialog({
                    locale: 'ru',
                    title: 'Добавление времени',
                    message: result['text'],
                    className: 'info',
                    buttons: {
                        cancel: {
                            label: 'Отмена',
                            className: "btn-danger",
                            callback: function () {
                                location.href = '/competitions/figures/add-results?figureId=' + figureId +
                                    '&date=' + date;
                            }
                        },
                        confirm: {
                            label: 'Добавить',
                            className: "btn-success",
                            callback: function () {
                                showBackDrop();
                                $.ajax({
                                    url: '/competitions/figures/add-time?confirm=true',
                                    type: "POST",
                                    data: form.serialize(),
                                    success: function (result) {
                                        if (result['success'] == true) {
                                            location.href = '/competitions/figures/add-results?figureId=' + figureId +
                                                '&date=' + date + '&success=true';
                                        } else {
                                            alert(result['text']);
                                        }
                                    }
                                })
                            }
                        }
                    }
                });
            } else {
                hideBackDrop();
                BootboxError(result['text']);
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
            $('#length').removeClass('color-red');
            $('#length').addClass('color-green');
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

function cityForNewAthlete(id) {
    showBackDrop();
    $.ajax({
        url: '/competitions/tmp-athletes/save-new-city',
        type: "POST",
        data: $('#cityForNewAthlete' + id).serialize(),
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

function cityForNewParticipant(id) {
    showBackDrop();
    $.ajax({
        url: '/competitions/tmp-participant/save-new-city',
        type: "POST",
        data: $('#cityForNewParticipant' + id).serialize(),
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

$('#isClosedChamp').click(function () {
    $('#regionsForChamp').slideToggle();
});

$('.participantIsArrived').click(function () {
    var elem = $(this);
    var id = elem.data('id');
    showBackDrop();
    $.get('/competitions/participants/is-arrived', {
        id: id
    }).done(function (data) {
        if (data == true) {
            $('<div class="text-with-backdrop">Сохранено</div>').appendTo(document.body);
            setTimeout(function () {
                $('.text-with-backdrop').hide();
                hideBackDrop()
            }, 1000);
        } else {
            hideBackDrop();
            BootboxError(data);
        }
    }).fail(function (error) {
        hideBackDrop();
        BootboxError(error.responseText);
    });
});

$('.setFinalList').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var stageId = elem.data('id');
    bootbox.dialog({
        locale: 'ru',
        title: 'Формирование итогового списка',
        message: 'Все заявки, для которых не отмечен пункт "Участник приехал на этап", будут отменены. ' +
        'Для отмененных заявок невозможно установить время заездов. Уверены, что хотите совершить это действие? (' +
        'в дальнейшем отмененные заявки можно вернуть в работу на странице "участники")',
        className: 'info',
        buttons: {
            cancel: {
                label: 'Отмена',
                className: "btn-danger",
                callback: function () {
                    return true;
                }
            },
            confirm: {
                label: 'Сформировать список',
                className: "btn-success",
                callback: function () {
                    showBackDrop();
                    $.get('/competitions/participants/set-final-list', {
                        stageId: stageId
                    }).done(function (data) {
                        if (data['success'] == true) {
                            hideBackDrop();
                            $('<div class="text-with-backdrop">' + data['text'] + '</div>').appendTo(document.body);
                            setTimeout(function () {
                                $('.text-with-backdrop').hide();
                            }, 1500);
                        } else {
                            hideBackDrop();
                            alert(data['text']);
                        }
                    }).fail(function (error) {
                        hideBackDrop();
                        alert(error.responseText);
                    });
                }
            }
        }
    });
});

$(document).on("submit", '#changeAthleteClassForm', function (e) {
    e.preventDefault();
    var form = $(this);
    $('.alert-danger').hide();
    showBackDrop();
    $.ajax({
        url: "/competitions/athlete/change-athlete-class",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                location.href = '/competitions/athlete/change-class?success=true';
            } else {
                hideBackDrop();
                $('.alert-danger').text(result).show();
            }
        },
        error: function (result) {
            hideBackDrop();
            BootboxError(result.responseText);
        }
    });
});

$('.processClassRequest').click(function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var status = $(this).data('status');
    $('.alert:not(.required-alert-info):not(.help-alert)').hide();
    $('#id').val(id);
    $('#status').val(status);
    if (status == 1) {
        $('.modal-body h4').text('Укажите текст события для перехода в новый класс');
        $('.modal-header h3').text('Вы собираетесь ПОДТВЕРДИТЬ класс');
    } else {
        $('.modal-body h4').text('Укажите причину отказа');
        $('.modal-header h3').text('Вы собираетесь ОТКЛОНИТЬ класс');
    }
    $('#processClassRequest').modal('show')
});

$(document).on("submit", '#processClassRequestForm', function (e) {
    e.preventDefault();
    var form = $(this);
    $.ajax({
        url: '/competitions/classes-request/process',
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

$(document).on("submit", '#sendMessagesForm', function (e) {
    e.preventDefault();
    var form = $(this);
    $('.alert:not(.required-alert-info):not(.help-alert)').hide();
    $.ajax({
        url: '/competitions/additional/send-message',
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result['success'] == true) {
                $('.form').slideToggle();
                form.trigger('reset');
                $('.alert-success').text(result['text']).slideToggle();
            } else {
                $('.alert-danger').text(result['text']).show();
            }
        },
        error: function (result) {
            $('.alert-danger').text(result).show();
        }
    });
});

$('.freeNumbersList').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var id = elem.data('id');
    $.get('/competitions/stages/get-free-numbers', {
        stageId: id
    }).done(function (data) {
        if (data['success'] == true) {
            $('.free-numbers .list').html(data['numbers']);
            $('.free-numbers').slideToggle();
        } else {
            alert(data['error']);
        }
    }).fail(function (error) {
        alert(error.responseText);
    });
});

$('.deleteParticipant').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var id = elem.data('id');
    var name = elem.data('name');
    bootbox.dialog({
        locale: 'ru',
        title: 'Удаление заявки спортсмена',
        message: '<p>Уверены, что хотите полностью удалить из системы заявку спортсмена ' +
        '<span style="color: gray"> ' + name + '</span>? ' +
        'Совершайте это действие только в том случае, если спортсмен был ошибочно зарегистрирован на этап. ' +
        'Если спортсмен просто решил не участвовать - отмените его заявку, нажав на красную кнопку с крестиком.</p>' +
        '<p><span class="red">ОБРАТИТЕ ВНИМАНИЕ!</span> Это действие необратимо, заявка будет полностью удалена. В дальнейшем можно будет лишь заново' +
        ' зарегистрировать спортсмена.</p>',
        className: 'danger',
        buttons: {
            cancel: {
                label: 'Отмена',
                className: "btn-danger",
                callback: function () {
                    return true;
                }
            },
            confirm: {
                label: 'Удалить заявку',
                className: "btn-success",
                callback: function () {
                    showBackDrop();
                    $.get('/competitions/participants/delete', {
                        id: id
                    }).done(function (data) {
                        if (data == true) {
                            hideBackDrop();
                            $('<div class="text-with-backdrop">Заявка успешно удалена</div>').appendTo(document.body);
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        } else {
                            hideBackDrop();
                            alert(data);
                        }
                    }).fail(function (error) {
                        hideBackDrop();
                        alert(error.responseText);
                    });
                }
            }
        }
    });
});

$('#prepareParticipantsForImport').click(function (e) {
    e.preventDefault();
    showBackDrop();
    var elem = $(this);
    var stageId = elem.data('stage-id');
    $.get('/competitions/participants/prepare-list-for-import', {
        stageId: stageId
    }).done(function (data) {
        hideBackDrop();
        $('.modalList').html(data);
        $('#participantsList').modal('show')
    }).fail(function (error) {
        hideBackDrop();
        BootboxError(error.responseText);
    });
});

$(document).on("submit", '#importParticipants', function (e) {
    e.preventDefault();
    var form = $(this);
    var btn = form.find('button');
    var wait = form.find('.wait');
    btn.hide();
    wait.show();
    $.ajax({
        url: "/competitions/participants/import-for-stage",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                location.reload();
            } else {
                alert(result);
                wait.hide();
                btn.show();
            }
        },
        error: function (result) {
            alert(result);
            wait.hide();
            btn.show();
        }
    });
});

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

$(window).on('load', function() {
    if ($(document).width() >= 975) {
        equalizer($('.with-hr-border > div'));
    }
});

$('.closeHintBtn').click(function () {
    var elem = $(this);
    bootbox.dialog({
        locale: 'ru',
        title: 'Отключение подсказок',
        message: 'Отключить подсказки полностью? Они будут отключены на ВСЕХ страницах в админке. ' +
        'При необходимости вы сможете включить их в своём профиле. ',
        className: 'info',
        buttons: {
            cancel: {
                label: 'Скрыть эту подсказку',
                className: "btn-primary",
                callback: function () {
                    elem.parent().parent().hide();
                    return true;
                }
            },
            confirm: {
                label: 'Отключить все подсказки',
                className: "btn-warning",
                callback: function () {
                    $.get('/competitions/additional/close-hint').done(function (data) {
                        location.reload();
                    }).fail(function (error) {
                        alert(error.responseText);
                    });
                }
            }
        }
    });
});

function countDown(second, endMinute, endHour, endDay, endMonth) {
    var now = new Date();
    second = (arguments.length == 1) ? second + now.getSeconds() : second;
    endHour = typeof(endHour) != 'undefined' ? endHour : now.getHours();
    endMinute = typeof(endMinute) != 'undefined' ? endMinute : now.getMinutes();
    endDay = typeof(endDay) != 'undefined' ? endDay : now.getDate();
    endMonth = typeof(endMonth) != 'undefined' ? endMonth : now.getMonth();
//добавляем секунду к конечной дате (таймер показывает время уже спустя 1с.)
    var endDate = new Date(now.getFullYear(), endMonth, endDay, endHour, endMinute, second + 1);
    var interval = setInterval(function () { //запускаем таймер с интервалом 1 секунду
        var time = endDate.getTime() - now.getTime();
        if (time < 0) {                      //если конечная дата меньше текущей
            var seconds = 0;
            var hours = 0;
            var minutes = 0;
        } else {
            var hours = Math.floor(time / 36e5) % 24;
            var minutes = Math.floor(time / 6e4) % 60;
            var seconds = Math.floor(time / 1e3) % 60;
        }
        $('#hours').text(hours);
        $('#mins').text(minutes);
        $('#secs').text(seconds);
        if (!seconds && !minutes && !days && !hours) {
            clearInterval(interval);
            // alert("Время вышло!");
        }
        now.setSeconds(now.getSeconds() + 1); //увеличиваем текущее время на 1 секунду
    }, 1000);
}

$('.ajaxDelete').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var actionId = elem.data('action');
    var id = elem.data('id');
    var text = '';
    var action = '';
    switch (actionId) {
        case 'stage':
            text = 'Уверены, что хотите удалить этот этап? Действие необратимо.';
            action = '/competitions/stages/delete';
            break;
        case 'champ':
            text = 'Уверены, что хотите удалить этот чемпионат? Действие необратимо.';
            action = '/competitions/championships/delete';
            break;
        case 'special-champ':
            text = 'Уверены, что хотите удалить этот чемпионат? Действие необратимо.';
            action = '/competitions/special-champ/delete';
            break;
        case 'special-stage':
            text = 'Уверены, что хотите удалить этот этап? Действие необратимо.';
            action = '/competitions/special-champ/delete-stage';
            break;
    }
    if (confirm(text)) {
        showBackDrop();
        $.get(action, {
            id: id
        }).done(function (data) {
            hideBackDrop();
            if (data == true) {
                location.href = '/competitions/championships';
            } else {
                BootboxError(data);
            }
        }).fail(function (error) {
            hideBackDrop();
            alert(error.responseText);
        });
    }
});


$(document).on("submit", '#cancelRegForSpecStage', function (e) {
    e.preventDefault();
    var form = $(this);
    $.ajax({
        url: '/competitions/special-champ/cancel',
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                location.reload();
            } else {
                alert(data);
            }
        },
        error: function (result) {
            alert(result);
        }
    });
});

$('.approveSpecChampForAthlete').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var id = elem.data('id');
    var athleteId = elem.data('athlete-id');
    showBackDrop();
    $.get('/competitions/special-champ/approve-for-athlete', {
        id: id, athleteId: athleteId
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

$('.approveSpecChampForAthleteOnMotorcycle').click(function (e) {
    e.preventDefault();
    var elem = $(this);
    var id = elem.data('id');
    var athleteId = elem.data('athlete-id');
    var motorcycleId = elem.data('motorcycle-id');
    showBackDrop();
    $.get('/competitions/special-champ/approve-for-athlete-on-motorcycle', {
        id: id, athleteId: athleteId, motorcycleId: motorcycleId
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

function cityForNewRequest(id) {
    showBackDrop();
    $.ajax({
        url: '/competitions/special-champ/save-new-city',
        type: "POST",
        data: $('#cityForNewRequest' + id).serialize(),
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

$('.changeTmpMotorcycle').click(function (e) {
    e.preventDefault();
    showBackDrop();
    var elem = $(this);
    var id = elem.data('id');
    var mode = elem.data('mode');
    var motorcycleId = elem.data('motorcycle-id');
    $.get('/competitions/athlete/find-tmp-motorcycle', {
        id: id, motorcycleId: motorcycleId, mode: mode
    }).done(function (data) {
        hideBackDrop();
        $('.modalChangeTmpMotorcycle').html(data);
        $('#modalChangeTmpMotorcycle').modal('show')
    }).fail(function (error) {
        hideBackDrop();
        BootboxError(error.responseText);
    });
});

$(document).on("submit", '#changeTmpMotorcycleForm', function (e) {
    e.preventDefault();
    var form = $(this);
    var id = $('#tmp-id').val();
    var motorcycleId = $('#tmp-motorcycleId').val();
    $.ajax({
        url: "/competitions/athlete/change-tmp-motorcycle",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result['success'] == true) {
                $('#modalChangeTmpMotorcycle').modal('hide');
                $('#tmp-motorcycle-' + id + '-' + motorcycleId).html(result['data']);
            } else {
                alert(result['errors']);
            }
        },
        error: function (result) {
            alert(result);
        }
    });
});