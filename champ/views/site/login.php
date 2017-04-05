<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход в личный кабинет';
?>
<h2>Вход в личный кабинет</h2>
<div class="site-login">
	<div class="row">
		<div class="col-lg-5">
			<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
			
			<?= $form->field($model, 'login')->textInput(['autofocus' => true])->label('Логин или e-mail') ?>
			
			<?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
			
			<div class="form-group">
                <div class="row show-pk">
                    <div class="col-md-6 col-sm-6 text-left">
						<?= Html::a('Регистрация', ['/site/registration'], ['class' => 'btn btn-light']) ?>
                    </div>
                    <div class="col-md-6 col-sm-6 text-right">
						<?= Html::submitButton('Вход', ['class' => 'btn btn-dark', 'name' => 'login-button']) ?>
                    </div>
                </div>
                <div class="row show-mobile">
                    <div class="col-md-12 text-left pb-10">
		                <?= Html::submitButton('Вход', ['class' => 'btn btn-dark', 'name' => 'login-button']) ?>
                    </div>
                    <div class="col-md-12 text-left">
						<?= Html::a('Регистрация', ['/site/registration'], ['class' => 'btn btn-light']) ?>
                    </div>
                </div>
			</div>
			
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>
