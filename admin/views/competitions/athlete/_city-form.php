<?php
use yii\bootstrap\Html;
?>

<h3>Добавить город</h3>
<?= Html::beginForm(['/competitions/athlete/add-city'], 'post') ?>
<div class="form-group">
	<?= Html::input('text', 'city', null, [
		'class'       => 'form-control',
		'placeholder' => 'город'
	]) ?>
</div>
<?= Html::submitButton('Добавить город', ['class' => 'btn btn-primary']) ?>
<?= Html::endForm(); ?>