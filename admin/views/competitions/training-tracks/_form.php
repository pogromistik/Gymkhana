<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TrainingTrack */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="training-track-form">
	
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	
	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
	
	<?= $form->field($model, 'imgFile')->fileInput() ?>
	
	<?= $form->field($model, 'status')->dropDownList(\common\models\TrainingTrack::$statusTitles) ?>
	
	<?= $form->field($model, 'minWidth')->textInput() ?>
	
	<?= $form->field($model, 'minHeight')->textInput() ?>
	
	<?= $form->field($model, 'level')->dropDownList(\common\models\TrainingTrack::$levelTitles) ?>
	
	<?= $form->field($model, 'conesCount')->textInput() ?>

    <div class="form-group">
		<?= Html::submitButton('Сохранить', ['class' => $model->isNewRecord ? 'btn btn-my-style btn-green' : 'btn btn-my-style btn-blue']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
