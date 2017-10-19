<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/**
 * @var \admin\models\MotorcycleForm $model
 */
?>

<div class="modal fade" id="modalChangeTmpMotorcycle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
				<?php $form = ActiveForm::begin(['id' => 'changeTmpMotorcycleForm']); ?>
				<?= $form->field($model, 'id')->hiddenInput(['id' => 'tmp-id'])->error(false)->label(false) ?>
				<?= $form->field($model, 'motorcycleId')->hiddenInput(['id' => 'tmp-motorcycleId'])->error(false)->label(false) ?>
				<?= $form->field($model, 'mark')->textInput() ?>
				<?= $form->field($model, 'model')->textInput() ?>
				<?= $form->field($model, 'cbm')->textInput() ?>
				<?= $form->field($model, 'power')->textInput() ?>
				<?= $form->field($model, 'isCruiser')->checkbox() ?>
				<?= Html::submitButton('Сохранить', ['class' => 'btn btn-my-style btn-blue']) ?>
				<?php $form->end(); ?>
            </div>
        </div>
    </div>
</div>