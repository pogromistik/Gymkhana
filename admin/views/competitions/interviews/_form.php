<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use kartik\widgets\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Interview */
?>

<div class="interview-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'dateStartHuman',
		['inputTemplate' => '<div class="input-with-description"><div class="text">Обратите внимание - время Московское!</div>{input}</div>'])
		->widget(DateTimePicker::class, [
			'options'       => ['placeholder' => 'Введите дату и время начала голосования'],
			'removeButton'  => false,
			'language'      => 'ru',
			'pluginOptions' => [
				'autoclose' => true,
				'format'    => 'dd.mm.yyyy, hh:ii',
			]
		]) ?>
	
	<?= $form->field($model, 'dateEndHuman',
		['inputTemplate' => '<div class="input-with-description"><div class="text">Обратите внимание - время Московское!</div>{input}</div>'])
		->widget(DateTimePicker::class, [
			'options'       => ['placeholder' => 'Введите дату и время завершения голосования'],
			'removeButton'  => false,
			'language'      => 'ru',
			'pluginOptions' => [
				'autoclose' => true,
				'format'    => 'dd.mm.yyyy, hh:ii',
			]
		]) ?>
	
	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'description')->widget(CKEditor::class, [
		'preset' => 'full', 'clientOptions' => ['allowedContent' => true, 'height' => 150]
	]) ?>

    <a href="#" class="btn btn-my-style btn-gray small" id="enInfo">Добавить информацию на английском</a>
    <div class="en_info">
        <small><b>Внимание! Скрытие этого блока не удаляет введённую информацию, т.е. если вы заполните поля, потом
                скроете блок и нажмёте "сохранить" - информация сохранится</b></small>
		<?= $form->field($model, 'titleEn')->textInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'descriptionEn')->widget(CKEditor::class, [
			'preset' => 'full', 'clientOptions' => ['allowedContent' => true, 'height' => 150]
		]) ?>
    </div>
	
	
	<?= $form->field($model, 'onlyPictures')->checkbox() ?>
	
	<?= $form->field($model, 'showResults')->checkbox() ?>

    <div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-my-style btn-green' : 'btn btn-my-style btn-blue']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
