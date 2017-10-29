<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Motorcycle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="motorcycle-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'mark')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'model')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'cbm')->textInput() ?>
	
	<?= $form->field($model, 'power')->textInput() ?>
	
	<?= $form->field($model, 'isCruiser')->checkbox() ?>
	
	<div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => 'btn btn-my-style btn-blue']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>