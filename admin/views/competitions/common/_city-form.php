<?php
/**
 * @var integer $success
 * @var integer $errorCity
 * @var string  $actionType
 */
use yii\bootstrap\Html;
use yii\helpers\Url;

if (!isset($url)) {
    $url = Url::current();
}
?>
<h3>Добавить город</h3>
<?= Html::beginForm('', 'post', [
	'id'               => 'newCityForm',
	'data-action'      => $url,
	'data-action-type' => $actionType
]) ?>
<div class="form-group">
	<?= Html::input('text', 'city', null, [
		'class'       => 'form-control',
		'placeholder' => 'город'
	]) ?>
</div>
<?= Html::submitButton('Добавить город', ['class' => 'btn btn-primary']) ?>
<?= Html::endForm(); ?>

<?php if ($success) { ?>
    <br>
    <div class="alert alert-success">Город добавлен</div>
<?php } ?>

<?php if ($errorCity) { ?>
    <br>
    <div class="alert alert-warning">Город уже существует</div>
<?php } ?>
