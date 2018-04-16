<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use kartik\date\DatePicker;

/**
 * @var \yii\web\View                        $this
 * @var common\models\RequestForSpecialStage $participant
 */

$stage = $participant->stage;
$this->title = $stage->title . ': ' . $participant->athlete->getFullName();
$championship = $stage->championship;
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['/competitions/special-champ/index']];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/special-champ/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['/competitions/special-champ/view-stage', 'id' => $stage->id]];
$this->params['breadcrumbs'][] = ['label' => 'Участники', 'url' => ['/competitions/special-champ/participants', 'stageId' => $stage->id]];
$this->params['breadcrumbs'][] = $participant->athlete->getFullName();
?>
<div class="request-for-special-stage-index">

    <div class="request-for-special-stage-form">
		
		<?php $form = ActiveForm::begin(); ?>
        
        <?= $form->field($participant, 'athleteClassId')
            ->dropDownList(\common\models\AthletesClass::getList()) ?>
		
		<?= $form->field($participant, 'motorcycleId')->widget(\kartik\widgets\DepDrop::className(), [
			'options'       => ['id' => 'motorcycle-id'],
			'data'          => ArrayHelper::map(\common\models\Motorcycle::findAll(['athleteId' => $participant->athleteId]), 'id', function (\common\models\Motorcycle $item) {
				return $item->mark . ' ' . $item->model;
			}),
			'pluginOptions' => [
				'depends'     => ['athlete-id'],
				'placeholder' => 'Выберите мотоцикл...',
				'url'         => \yii\helpers\Url::to('/competitions/participants/motorcycle-category')
			]
		]) ?>
		
		<?= $form->field($participant, 'dateHuman')->widget(DatePicker::classname(), [
			'options'       => ['placeholder' => 'Введите дату заезда'],
			'removeButton'  => false,
			'language'      => 'ru',
			'pluginOptions' => [
				'autoclose' => true,
				'format'    => 'dd.mm.yyyy',
			]
		]) ?>
		
		<?= $form->field($participant, 'timeHuman')->widget(MaskedInput::classname(), [
			'mask'    => '99:99.99',
			'options' => [
				'id'    => 'setTime',
				'class' => 'form-control',
				'type'  => 'tel'
			]
		]) ?>
		
		<?= $form->field($participant, 'fine')->textInput() ?>
	
	    <?= $form->field($participant, 'videoLink')->textInput() ?>
	
	    <?= $form->field($participant, 'status')->dropDownList(\common\models\RequestForSpecialStage::$statusesTitle) ?>

        <div class="form-group">
			<?= Html::submitButton('Добавить', ['class' => 'btn btn-my-style btn-green']) ?>
        </div>
		
		<?php ActiveForm::end(); ?>

    </div>
</div>