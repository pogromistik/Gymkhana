<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TmpParticipant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tmp-participant-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'championshipId')->textInput() ?>

    <?= $form->field($model, 'stageId')->textInput() ?>

    <?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cityId')->textInput() ?>

    <?= $form->field($model, 'motorcycleMark')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'motorcycleModel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'number')->textInput() ?>

    <?= $form->field($model, 'dateAdded')->textInput() ?>

    <?= $form->field($model, 'dateUpdated')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'athleteId')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
