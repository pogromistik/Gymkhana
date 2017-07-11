$('.file-upload').on('filebatchuploadcomplete', function (event, files, extra) {
    console.log('File batch upload complete');
    location.reload();
});

$('.delete-album-photo').click(function () {
    var id = $(this).data('id');
    $.get('/album/delete-photo', {
        photoId: id
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

function showBackDrop() {
    $('<div class="modal-backdrop fade in"></div>').appendTo(document.body);
}
function hideBackDrop() {
    $(".modal-backdrop").remove();
}
$(document).on("submit", '#pageForm', function (e) {
    e.preventDefault();
    showBackDrop();
    var form = $(this);
    $.ajax({
        url: "/pages/ajax-update",
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

$('.removeFile').click(function () {
    if (confirm("Уверены, что хотите удалить этот файл?")) {
        var id = $(this).data('id');
        $.get('/additional/remove-file', {
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

$(document).ready(function () {
    //$('li.competitions').addClass('active');
});

(function () {
    var current = '/' + window.location.pathname.split('/')[1] + '/' + window.location.pathname.split('/')[2];
    $(".nav a").each(function () {
        var elem = $(this);
        if (elem.data('addr') == current) {
            var ul = elem.closest('ul');
            if (ul.hasClass('dropdown-menu')) {
                ul.parent().find('.dropdown-toggle').addClass('active');
            } else {
                elem.addClass('active');
            }
        }
    });
})();

$(document).on("submit", '.messageForTranslateForm', function (e) {
    e.preventDefault();
    showBackDrop();
    var form = $(this);

    $.ajax({
        url: "/competitions/translate-messages/update",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                var tr = form.closest('.order-row');
                tr.addClass('handbook-green');
                hideBackDrop();
            }
            else {
                hideBackDrop();
                alert(result);
            }
        }
    });
});

function changeMessageStatus(id) {
    showBackDrop();
    $.get("/competitions/translate-messages/change-status", {id: id}).done(function (data) {
        if (data == true) {
            location.reload(true);
        }
        else {
            hideBackDrop();
            alert(data);
        }
    }).fail(function (error) {
        hideBackDrop();
        alert(error.responseText);
    });
}

$(document).on("submit", '.TranslateMessagesForm', function (e) {
    e.preventDefault();
    showBackDrop();
    var form = $(this);
    var btn = $(document.activeElement);
    $.ajax({
        url: "/competitions/translate-messages/add-translate",
        type: "POST",
        data: form.serialize(),
        success: function (result) {
            if (result == true) {
                btn.removeClass('btn-primary');
                btn.addClass('btn-success');
                hideBackDrop();
            }
            else {
                hideBackDrop();
                alert(result);
            }
        }
    });
});