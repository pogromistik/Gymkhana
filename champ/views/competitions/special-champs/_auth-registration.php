<?php
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Motorcycle;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/**
 * @var \common\models\Stage $stage
 */

$athlete = \common\models\Athlete::findOne(\Yii::$app->user->id);
$formModel = new \common\models\RequestForSpecialStage();
$formModel->athleteId = $athlete->id;
$formModel->stageId = $stage->id;
$formModel->dateHuman = date("d.m.Y");
?>

<div class="registration-form">
    <h3>Заполните все поля</h3>
	
	<?php $form = ActiveForm::begin(['options' => ['id' => 'specialStageForAuth']]); ?>
	
	<?= $form->field($formModel, 'athleteId')->hiddenInput()->error(false)->label(false) ?>
	
	<?= $form->field($formModel, 'stageId')->hiddenInput()->error(false)->label(false) ?>
	
	<?= $form->field($formModel, 'motorcycleId')->dropDownList(ArrayHelper::map(
		$athlete->activeMotorcycles, 'id', function (Motorcycle $item) {
		return $item->getFullTitle();
	})) ?>
	
	<?= $form->field($formModel, 'dateHuman')->widget(DatePicker::classname(), [
		'options'       => ['placeholder' => 'Введите дату заезда'],
		'removeButton'  => false,
		'language'      => 'ru',
		'pluginOptions' => [
			'autoclose' => true,
			'format'    => 'dd.mm.yyyy',
		]
	]) ?>
	
	<?= $form->field($formModel, 'timeHuman')->widget(MaskedInput::classname(), [
		'mask'    => '99:99.99',
		'options' => [
			'id'    => 'setTime',
			'class' => 'form-control',
			'type'  => 'tel'
		]
	]) ?>
	
	<?= $form->field($formModel, 'fine')->textInput() ?>
	
	<?= $form->field($formModel, 'videoLink')->textInput() ?>

    <div class="alert alert-danger" style="display: none"></div>
    <div class="alert alert-success" style="display: none"></div>

    <div class="form-group">
		<?= Html::submitButton('Отправить', ['class' => 'btn btn-dark']) ?>
    </div>
	
	<?php $form->end(); ?>
</div>
