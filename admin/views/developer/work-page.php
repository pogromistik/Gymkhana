<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Work */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="che-scheme-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'text')->textarea(['rows' => 3]) ?>
	
	<?= $form->field($model, 'time')->textInput() ?>
	
	<?= $form->field($model, 'status')->checkbox() ?>
	
	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>