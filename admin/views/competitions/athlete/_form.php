<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\Athlete */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="athlete-form">
	<?php $form = ActiveForm::begin(['options' => ['id' => $model->isNewRecord ? 'newAthlete' : 'updateAthlete']]); ?>
	<?= $form->field($model, 'cityId')->widget(Select2::classname(), [
		'name'    => 'kv-type-01',
		'data'    => \yii\helpers\ArrayHelper::map(\common\models\City::find()->all(), 'id', 'title'),
		'options' => [
			'placeholder' => 'Выберите город...',
		],
	]) ?>
	
	<?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'athleteClassId')->dropDownList(\yii\helpers\ArrayHelper::map(
		\common\models\AthletesClass::find()->all(), 'id', 'title'
	), ['prompt' => 'Укажите класс спортсмена']) ?>
	
	<?= $form->field($model, 'number')->textInput() ?>

    <div class="form-group complete">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>