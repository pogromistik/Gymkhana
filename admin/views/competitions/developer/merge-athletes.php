<?php

use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \yii\web\View                   $this
 * @var \admin\models\MergeAthletesForm $formModel
 */
$this->title = 'Объединение спортсменов';
?>
<div class="merge-athletes">
	
	<?php $form = ActiveForm::begin(['id' => 'firstStepMerge']); ?>

    <div class="row">
        <div class="col-md-6">
			<?= $form->field($formModel, 'firstAthleteId')->widget(Select2::classname(), [
				'name'    => 'kv-type-01',
				'data'    => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function (\common\models\Athlete $item) {
					return $item->id . ' ' . $item->getFullName();
				}),
				'options' => [
					'placeholder' => 'Выберите первого спортсмена...',
					'id'          => 'first-athlete-id',
				]
			]) ?>
        </div>
        <div class="col-md-6">
			<?= $form->field($formModel, 'secondAthleteId')->widget(Select2::classname(), [
				'name'    => 'kv-type-02',
				'data'    => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function (\common\models\Athlete $item) {
					return $item->id . ' ' . $item->getFullName();
				}),
				'options' => [
					'placeholder' => 'Выберите второго спортсмена...',
					'id'          => 'second-athlete-id',
				]
			]) ?>
        </div>
    </div>
	
	<?= \yii\helpers\Html::submitButton('Далее', ['class' => 'btn btn-my-style btn-blue']) ?>
	
	<?php $form->end(); ?>

    <div id="secondStep"></div>

</div>