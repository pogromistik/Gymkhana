<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\InternalClass;
use common\models\AthletesClass;

/* @var $this yii\web\View */
/* @var $model common\models\Participant */
/* @var $form yii\widgets\ActiveForm */
/* @var $championship \common\models\Championship */
/* @var $needClarification bool */
?>

<div class="participant-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'championshipId')->hiddenInput()->label(false)->error(false) ?>
	
	<?= $form->field($model, 'stageId')->hiddenInput()->label(false)->error(false) ?>
	
	<?= $form->field($model, 'athleteId')->widget(Select2::classname(), [
		'name'    => 'kv-type-01',
		'data'    => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function (\common\models\Athlete $item) {
			return $item->lastName . ' ' . $item->firstName . ' (' . $item->city->title . ')';
		}),
		'options' => [
			'placeholder' => 'Выберите спортсмена...',
			'id'          => 'athlete-id',
		],
	]) ?>
	
	<?= $form->field($model, 'motorcycleId')->widget(\kartik\widgets\DepDrop::className(), [
		'options'       => ['id' => 'motorcycle-id'],
		'data'          => ArrayHelper::map(\common\models\Motorcycle::findAll(['athleteId' => $model->athleteId]), 'id', function (\common\models\Motorcycle $item) {
			return $item->mark . ' ' . $item->model;
		}),
		'pluginOptions' => [
			'depends'     => ['athlete-id'],
			'placeholder' => 'Выберите мотоцикл...',
			'url'         => \yii\helpers\Url::to('/competitions/participants/motorcycle-category')
		]
	]) ?>
	<?php if ($championship->useCheScheme) { ?>
        <div class="input-with-description">
            <div class="text">
                Вы используете стандартную схему для награждения, поэтому класс награждения будет установлен
                автоматически
            </div>
        </div>
	<?php } else { ?>
		<?= $form->field($model, 'internalClassId')->dropDownList(
			ArrayHelper::map(InternalClass::getActiveClasses($model->championshipId), 'id', 'title'),
			['prompt' => 'Выберите класс награждения']) ?>
	<?php } ?>
	
	<?= $form->field($model, 'number')->textInput() ?>
	
	<?= $form->field($model, 'sort')->textInput() ?>
	
	<?= $form->field($model, 'status')->dropDownList(\common\models\Participant::$typesTitle) ?>
	
	<?php if (!$needClarification) { ?>
        <div class="form-group">
	        <?= Html::hiddenInput('confirmed', false) ?>
			<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
	<?php } else { ?>
        <div class="form-group">
	        <?= Html::hiddenInput('confirmed', true) ?>
            <div class="alert alert-danger">
                На этап уже зарегистрировано максимальное количество участников. Всё равно добавить этого спортсмена?<br>
	            <?= Html::a('Отмена', ['/competitions/participants/index', 'stageId' => $model->stageId], ['class' => 'btn btn-danger']) ?>
	            <?= Html::submitButton('Зарегистрировать', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    <?php } ?>
	
	<?php ActiveForm::end(); ?>

</div>
