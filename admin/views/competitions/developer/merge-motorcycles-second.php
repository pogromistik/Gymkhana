<?php

use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \yii\web\View                      $this
 * @var \admin\models\MergeMotorcyclesForm $formModel
 * @var \common\models\Athlete             $athlete
 * @var string                             $errors
 */
$this->title = 'Объединение мотоциклов: ' . $athlete->getFullName();
?>

<div class="alert alert-info">После объединения второй мотоцикл будет удалён</div>

<?php if ($errors) {
	?>
    <div class="alert alert-danger"><?= $errors ?></div>
	<?php
} ?>
<div class="merge-athletes">
	
	<?php $form = ActiveForm::begin(['id' => 'mergeMotorcycles']); ?>
	
	<?= $form->field($formModel, 'athleteId')->hiddenInput()->error(false)->label(false) ?>

    <div class="row">
        <div class="col-md-6">
			<?= $form->field($formModel, 'firstMotorcycles')->dropDownList(ArrayHelper::map($athlete->motorcycles, 'id',
				function (\common\models\Motorcycle $item) {
					return $item->id . ': ' . $item->getFullTitle() . ' ('.\common\models\Motorcycle::$statusesTitle[$item->status].')';
				})) ?>
        </div>
        <div class="col-md-6">
			<?= $form->field($formModel, 'secondMotorcycles')->dropDownList(ArrayHelper::map($athlete->motorcycles, 'id',
				function (\common\models\Motorcycle $item) {
					return $item->id . ': ' . $item->getFullTitle() . ' ('.\common\models\Motorcycle::$statusesTitle[$item->status].')';
				})) ?>
        </div>
    </div>
	
	<?= \yii\helpers\Html::submitButton('Далее', ['class' => 'btn btn-my-style btn-blue']) ?>
	
	<?php $form->end(); ?>

    <div id="secondStep"></div>

</div>