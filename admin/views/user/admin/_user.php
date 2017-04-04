<?php
use kartik\widgets\Select2;
/**
 * @var yii\widgets\ActiveForm    $form
 * @var dektrium\user\models\User $user
 */

?>

<?= $form->field($user, 'username')->textInput(['maxlength' => 25]) ?>
<?= $form->field($user, 'email')->textInput(['maxlength' => 255]) ?>
<?= $form->field($user, 'password')->passwordInput() ?>
<?= $form->field($user, 'regionId')->widget(Select2::classname(), [
	'name'          => 'kv-type-01',
	'data'          => \common\models\Region::getAll(true),
	'options'       => [
		'placeholder' => 'Выберите регион...',
	],
	'pluginOptions' => [
		'allowClear' => true
	],
]) ?>