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