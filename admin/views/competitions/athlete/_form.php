<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Athlete */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="athlete-form">
	<?php $form = ActiveForm::begin(['options' => ['id' => $model->isNewRecord ? 'newAthlete' : 'updateAthlete']]); ?>
	<?= $form->field($model, 'cityId')->widget(Select2::classname(), [
		'name'    => 'kv-type-01',
		'data'    => \common\models\City::getAll(true),
		'options' => [
			'placeholder' => 'Выберите город...',
		],
	]) ?>
	
	<?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
	
	<?php
	$startClass = \common\models\AthletesClass::getStartClass();
	$text = '';
	if ($startClass) {
		$text = 'По умолчанию будет установлен класс ' . $startClass->title;
	}
	?>
	<?= $form->field($model, 'athleteClassId',
		['inputTemplate' => '<div class="input-with-description"><div class="text">
 ' . $text . '
</div>{input}</div>'])->dropDownList(\yii\helpers\ArrayHelper::map(
		\common\models\AthletesClass::find()->andWhere(['status' => \common\models\AthletesClass::STATUS_ACTIVE])->orderBy(['sort' => SORT_ASC])->all(), 'id', 'title'
	), ['prompt' => 'Укажите класс спортсмена']) ?>
	
	<?= $form->field($model, 'number')->textInput() ?>

    <div class="form-group complete">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
