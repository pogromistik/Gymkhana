<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use dosamigos\ckeditor\CKEditor;
use yii\widgets\MaskedInput;

/* @var $this yii\web\View */
/* @var $model common\models\Figure */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="figure-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
	    'options' => ['id' => 'newBlock'],
	    'preset'  => 'basic',
    ]) ?>

    <?= $form->field($model, 'bestTimeForHuman')->widget(MaskedInput::classname(), [
	    'mask'    => '99:99.99',
	    'options' => [
		    'id'    => 'bestTimeForHuman' . $model->id,
		    'class' => 'form-control'
	    ]
    ]) ?>

    <?= $form->field($model, 'bestAthlete')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'bestTimeInRussiaForHuman')->widget(MaskedInput::classname(), [
	    'mask'    => '99:99.99',
	    'options' => [
		    'id'    => 'bestTimeInRussia' . $model->id,
		    'class' => 'form-control'
	    ]
    ]) ?>

    <?= $form->field($model, 'bestAthleteInRussia')->textarea(['rows' => 3]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
