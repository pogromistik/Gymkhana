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