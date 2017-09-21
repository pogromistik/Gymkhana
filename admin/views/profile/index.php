<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View              $this
 * @var \common\models\User        $user
 * @var string                     $success
 * @var \admin\models\PasswordForm $password
 * @var string                     $errors
 */
$this->title = 'Редактирование профиля';
?>

<?php if ($success) { ?>
    <div class="alert alert-success">
        Изменения сохранены
    </div>
<?php } ?>

    <h3>Профиль</h3>
<?php $form = ActiveForm::begin() ?>
<?= $form->field($user, 'showHint')->checkbox(); ?>
<?= Html::submitButton('сохранить', ['class' => 'btn btn-my-style btn-blue']) ?>
<?php $form->end() ?>


    <h3>Изменение пароля</h3>
    <div class="alert alert-info">
        Правила создания пароля:<br>
        1. Минимум 8 символов<br>
        2. Минимум 5 уникальных символов<br>
        3. Должны присутствовать маленькие буквы<br>
        4. Должны присутствовать большие буквы
    </div>
<?php $form = ActiveForm::begin() ?>
<?= $form->field($password, 'pass')->passwordInput()->label('Пароль'); ?>
<?= $form->field($password, 'pass_repeat')->passwordInput()->label('Подтвердите пароль') ?>
<?php if ($errors) { ?>
    <div class="alert alert-danger">
		<?= $errors ?>
    </div>
<?php } ?>
<?= Html::submitButton('сохранить', ['class' => 'btn btn-my-style btn-blue']) ?>
<?php $form->end() ?>