<?php
/**
 * @var MainPhoto[] $sliders
 */
use yii\helpers\Html;
use common\models\MainPhoto;
use yii\helpers\Url;
?>
1. Слайдер
<?= \kato\DropZone::widget([
	'options' => [
		'maxFilesize' => '99',
		'acceptedFiles' => 'image/*,application/pdf,.psd,.doc,.docx,.csv,.txt, .rtf',
		'dictDefaultMessage' => '<span class = "glyphicon glyphicon-download-alt"></span> Прикрепить файлы<br>
                        <small>перетащить сюда или <span style="color: #0000aa">выбрать</span> </small>',
		'url' => Url::to(['base/upload', 'type' => MainPhoto::PICTURES_SLIDER]),
		'clientEvents' => [
			'complete' => "function(file){console.log(file)}",
			'removedfile' => "function(file){alert(file.name + ' is removed')}"
		]
	],
]);
?>
<?php
	foreach ($sliders as $slider) { ?>
		<?= Html::img(Yii::getAlias('@pictures').'/'.MainPhoto::$filePath[$slider->type].'/'.$slider->fileName) ?>
		<?= Html::a('Удалить', ['main/delete-picture', 'id' => $slider->id], [
			'data' => [
				'confirm' => 'Вы уверены, что хотите удалить это изображение?',
				'method' => 'post',
			]
		]) ?>
	<?php }
?>
