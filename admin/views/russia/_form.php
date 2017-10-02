<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\City */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alert alert-info">
    <b>Для отображения города на карте требуются параметры top и left. Берутся они с сайта
        <a href="//card.gymkhana74.ru/" target="_blank">card.gymkhana74.ru</a>. Необходимо перейти на сайт, поставить точку в нужном месте
    на карте и навести курсор на эту точку. Первая цифра - это параметр left, вторая цифра - параметр top.</b>
</div>

<div class="russia-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>
	
	<?= $form->field($model, 'regionId')->widget(Select2::classname(), [
		'name'    => 'kv-type-01',
		'data'    => \common\models\Region::getAll(true),
		'options' => [
			'placeholder' => 'Выберите регион...',
		],
	]) ?>

    <?= $form->field($model, 'link')->textInput() ?>
	
	<?= $form->field($model, 'left')->textInput() ?>
	
	<?= $form->field($model, 'top')->textInput() ?>
	
	<?= $form->field($model, 'timezone')->textInput() ?>
	
	<?= $form->field($model, 'utc')->textInput() ?>
	
	<?= $form->field($model, 'showInRussiaPage')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
