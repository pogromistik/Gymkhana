<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \champ\models\PasswordResetRequestForm */
$this->title = \Yii::t('app', 'Восстановление пароля');
?>

<div class = "reset-password">
	
	<h2><?= \Yii::t('app', 'Восстановление пароля') ?></h2>
	
	<div class = "row">
		<div class="col-md-8 col-sm-10 col-xs-12">
			<?php $form = ActiveForm::begin(['id' => 'resetPasswordForm']); ?>
			
			<?= $form->field($model, 'login')->label(\Yii::t('app', 'Введите адрес электронной почты или логин')) ?>
			
			<div class="alert alert-danger" style="display: none"></div>
			<div class="alert alert-success" style="display: none"></div>
			
			<div class="form-group">
				<?= Html::submitButton(\Yii::t('app', 'восстановить'), ['class' => 'btn btn-dark']) ?>
			</div>
			
			<?php ActiveForm::end(); ?>
		</div>
	</div>

</div>

