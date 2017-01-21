<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \admin\models\SignupForm */

$this->title = 'Регистрация';
?>

<div class="form">
	<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'username')->textInput(['placeholder' => 'ник']); ?>
	<?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Пароль'])->label("Введите пароль"); ?>

	<div class="form-group">
		<?= Html::submitButton('Зарегистрировать', ['class' => 'button radius full-btn', 'name' => 'btn btn-success']) ?>
	</div>

	<?php ActiveForm::end(); ?>
</div>
