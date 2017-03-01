<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AthletesClass */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="athletes-class-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'percent')->textInput() ?>
	
	<?= $form->field($model, 'coefficient')->textInput() ?>
	
	<?= $form->field($model, 'sort')->textInput() ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>

    <div class="form-group">
        <?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
