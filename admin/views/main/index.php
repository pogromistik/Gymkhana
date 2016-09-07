<?php
/**
 * @var MainPhoto[] $sliders
 * @var MainPhoto[] $leftMenu
 * @var MainPhoto[] $bottomMenu
 */
use yii\helpers\Html;
use common\models\MainPhoto;
use yii\helpers\Url;
use common\models\HelpModel;
?>
1. Слайдер
<?= \kato\DropZone::widget([
	'options' => [
		'maxFilesize' => '99',
		'acceptedFiles' => 'image/*',
		'dictDefaultMessage' => '<span class = "glyphicon glyphicon-download-alt"></span> Прикрепить файлы<br>
                        <small>перетащить сюда или <span style="color: #0000aa">выбрать</span> </small>',
		'url' => Url::to(['base/upload-pictures', 'type' => MainPhoto::PICTURES_SLIDER, 'modelName' => HelpModel::MODEL_MAIN_PHOTO]),
		'clientEvents' => [
			'complete' => "function(file){console.log(file)}",
			'removedfile' => "function(file){alert(file.name + ' is removed')}"
		]
	],
]);
?>
<?php
	foreach ($sliders as $picture) { ?>
		<?= Html::img(Yii::getAlias('@pictures').'/'.MainPhoto::$filePath[$picture->type].'/'.$picture->fileName) ?>
		<?= Html::a('Удалить', ['main/delete-picture', 'id' => $picture->id], [
			'data' => [
				'confirm' => 'Вы уверены, что хотите удалить это изображение?',
				'method' => 'post',
			]
		]) ?>
	<?php }
?>

2. Левое меню
<?= \kato\DropZone::widget([
	'options' => [
		'maxFilesize' => '99',
		'acceptedFiles' => 'image/*',
		'dictDefaultMessage' => '<span class = "glyphicon glyphicon-download-alt"></span> Прикрепить файлы<br>
                        <small>перетащить сюда или <span style="color: #0000aa">выбрать</span> </small>',
		'url' => Url::to(['base/upload-pictures', 'type' => MainPhoto::PICTURES_LEFT_MENU, 'modelName' => HelpModel::MODEL_MAIN_PHOTO]),
		'clientEvents' => [
			'complete' => "function(file){console.log(file)}",
			'removedfile' => "function(file){alert(file.name + ' is removed')}"
		]
	],
]);
?>
<?php
foreach ($leftMenu as $picture) { ?>
	<?= Html::img(Yii::getAlias('@pictures').'/'.MainPhoto::$filePath[$picture->type].'/'.$picture->fileName) ?>
	<?= Html::a('Удалить', ['main/delete-picture', 'id' => $picture->id], [
		'data' => [
			'confirm' => 'Вы уверены, что хотите удалить это изображение?',
			'method' => 'post',
		]
	]) ?>
<?php }
?>

3. Нижнее меню
<?= \kato\DropZone::widget([
	'options' => [
		'maxFilesize' => '99',
		'acceptedFiles' => 'image/*',
		'dictDefaultMessage' => '<span class = "glyphicon glyphicon-download-alt"></span> Прикрепить файлы<br>
                        <small>перетащить сюда или <span style="color: #0000aa">выбрать</span> </small>',
		'url' => Url::to(['base/upload-pictures', 'type' => MainPhoto::PICTURES_BOTTOM_MENU, 'modelName' => HelpModel::MODEL_MAIN_PHOTO]),
		'clientEvents' => [
			'complete' => "function(file){console.log(file)}",
			'removedfile' => "function(file){alert(file.name + ' is removed')}"
		]
	],
]);
?>
<?php
foreach ($bottomMenu as $picture) { ?>
	<?= Html::img(Yii::getAlias('@pictures').'/'.MainPhoto::$filePath[$picture->type].'/'.$picture->fileName) ?>
	<?= Html::a('Удалить', ['main/delete-picture', 'id' => $picture->id], [
		'data' => [
			'confirm' => 'Вы уверены, что хотите удалить это изображение?',
			'method' => 'post',
		]
	]) ?>
<?php }
?>


<h3>Ссылки на соцсети</h3>