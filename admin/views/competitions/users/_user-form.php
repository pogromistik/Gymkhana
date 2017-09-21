<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Client;

/**
 * @var \common\models\User              $user
 * @var \dektrium\rbac\models\Assignment $assignment
 * @var string                           $errors
 */

?>

<?php $form = ActiveForm::begin([
	'layout'      => 'horizontal',
	'fieldConfig' => [
		'horizontalCssClasses' => [
			'wrapper' => 'col-sm-9',
		],
	],
]); ?>

<?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'username')->textInput(['maxlength' => 255])->label('Логин') ?>

<?= $form->field($assignment, 'items[]')->dropDownList(
	\admin\controllers\competitions\UsersController::$rolesTitle, $assignment->items ? [
	'options' => [reset($assignment->items) => ["Selected" => true]]
] : [])->label('Роль') ?>

<?= $form->field($user, 'password')->passwordInput() ?>

<?php if ($errors) { ?>
    <div class="alert alert-danger">
		<?= $errors ?>
    </div>
<?php } ?>

    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
			<?= Html::submitButton(Yii::t('user', 'Save'),
				['class' => $user->isNewRecord ? 'btn btn-block btn-my-style btn-green' : 'btn btn-block btn-my-style btn-blue']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>