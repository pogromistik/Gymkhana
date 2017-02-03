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
?>

<div class="participant-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'championshipId')->hiddenInput()->label(false)->error(false) ?>

    <?= $form->field($model, 'stageId')->hiddenInput()->label(false)->error(false) ?>

    <?= $form->field($model, 'athleteId')->widget(Select2::classname(), [
	    'name'    => 'kv-type-01',
	    'data'    => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function(\common\models\Athlete $item) {
	        return $item->lastName . ' ' . $item->firstName;
        }),
	    'options' => [
		    'placeholder' => 'Выберите спортсмена...',
		    'id' => 'athlete-id',
	    ],
    ]) ?>
	
	<?= $form->field($model, 'motorcycleId')->widget(\kartik\widgets\DepDrop::className(), [
		'options'       => ['id' => 'motorcycle-id'],
		'pluginOptions' => [
			'depends'     => ['athlete-id'],
			'placeholder' => 'Выберите мотоцикл...',
			'url'         => \yii\helpers\Url::to('/competitions/participants/motorcycle-category')
		]
	]) ?>

    <?= $form->field($model, 'internalClassId')->dropDownList(
            ArrayHelper::map(InternalClass::getActiveClasses($model->championshipId), 'id', 'title'),
        ['prompt' => 'Выберите класс награждения']) ?>

    <?= $form->field($model, 'number')->textInput() ?>

    <?= $form->field($model, 'sort')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
