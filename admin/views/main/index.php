<?= \kato\DropZone::widget([
	'options' => [
		'maxFilesize' => '99',
		'acceptedFiles' => 'image/*,application/pdf,.psd,.doc,.docx,.csv,.txt, .rtf',
		'dictDefaultMessage' => '<span class = "glyphicon glyphicon-download-alt"></span> Прикрепить файлы<br>
                        <small>перетащить сюда или <span style="color: #0000aa">выбрать</span> </small>',
		'url' => '/base/upload/?type=1',
		'clientEvents' => [
			'complete' => "function(file){console.log(file)}",
			'removedfile' => "function(file){alert(file.name + ' is removed')}"
		]
	],
]);
?>