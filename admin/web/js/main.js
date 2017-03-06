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