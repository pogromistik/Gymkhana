$('.file-upload').on('filebatchuploadcomplete', function(event, files, extra) {
    console.log('File batch upload complete');
    location.reload();
});