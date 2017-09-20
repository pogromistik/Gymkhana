<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TranslateMessageSource */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="source-message-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'message')->textarea(['rows' => 3]) ?>
	<?= $form->field($model, 'comment')->textarea(['rows' => 3]) ?>
	
	<div class="form-group">
		<?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>
