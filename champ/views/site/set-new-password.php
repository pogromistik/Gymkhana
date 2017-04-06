<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \champ\models\ResetPasswordForm */
?>

<div class="reset-password">

    <h2>Восстановление пароля</h2>

    <div class="row">
        <div class="col-md-8 col-sm-10 col-xs-12">
			<?php $form = ActiveForm::begin(); ?>
			
			<?= $form->field($model, 'password')->passwordInput()->label('Новый пароль'); ?>
	        <?= $form->field($model, 'passwordRepeat')->passwordInput()->label('Подтвердите пароль') ?>

            <div class="form-group">
				<?= Html::submitButton('сохранить', ['class' => 'btn btn-dark']) ?>
            </div>
			
			<?php ActiveForm::end(); ?>
        </div>
    </div>

</div>

