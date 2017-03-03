<?php
/**
 * @var integer $success
 * @var integer $errorCity
 * @var string  $actionType
 */
use yii\bootstrap\Html;
use yii\helpers\Url;
use kartik\widgets\Select2;

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

<div class="form-group">
	<?= Select2::widget([
		'name'    => 'regionId',
		'data'    => \yii\helpers\ArrayHelper::map(\common\models\Region::find()->all(), 'id', 'title'),
		'options' => [
			'placeholder' => 'Выберите регион...',
		],
		'pluginOptions' => [
			'allowClear' => true
		],
	]) ?>
</div>

<?= Html::submitButton('Добавить город', ['class' => 'btn btn-primary']) ?>
<?= Html::endForm(); ?>

<?php if ($success) { ?>
    <br>
    <div class="alert alert-success">Город добавлен</div>
<?php } ?>

<h3>Добавить регион</h3>
<?= Html::beginForm('', 'post', [
	'id'               => 'newRegionForm',
	'data-action'      => $url,
	'data-action-type' => $actionType
]) ?>
<div class="form-group">
	<?= Html::input('text', 'region', null, [
		'class'       => 'form-control',
		'placeholder' => 'регион'
	]) ?>
</div>

<?= Html::submitButton('Добавить регион', ['class' => 'btn btn-primary']) ?>
<?= Html::endForm(); ?>

<?php if ($errorCity && (int)$errorCity === 1) { ?>
    <br>
    <div class="alert alert-warning">Город уже существует</div>
<?php } ?>

<br>
<div class="alert alert-danger" style="display: none"></div>


<?php if ($errorCity && (int)$errorCity === 2) { ?>
    <br>
    <div class="alert alert-warning">Регион уже существует</div>
<?php } ?>
