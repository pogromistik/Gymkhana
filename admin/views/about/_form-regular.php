<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Regular */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="regular-form">

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>

	<?= $form->field($model, 'sort')->textInput() ?>

	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
