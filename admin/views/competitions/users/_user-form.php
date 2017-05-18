<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use common\models\Client;

/**
 * @var \common\models\User              $user
 * @var \dektrium\rbac\models\Assignment $assignment
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
<?= $form->field($user, 'username')->textInput(['maxlength' => 255]) ?>

<?= $form->field($assignment, 'items[]')->dropDownList(
	\admin\controllers\competitions\UsersController::$rolesTitle, $assignment->items ? [
	'options' => [reset($assignment->items) => ["Selected" => true]]
] : [])->label('Роль') ?>

<?= $form->field($user, 'password')->passwordInput() ?>
	
	<div class="form-group">
		<div class="col-lg-offset-3 col-lg-9">
			<?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?>
		</div>
	</div>

<?php ActiveForm::end(); ?>