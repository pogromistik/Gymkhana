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
                location.href = '/competitions/athlete/view?id='+result['data'];
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