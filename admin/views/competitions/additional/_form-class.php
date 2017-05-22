<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CheScheme */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="che-scheme-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'percent')->textInput() ?>
	
	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>