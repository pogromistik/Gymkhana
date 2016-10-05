<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\search\MarshalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="marshal-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'post') ?>

    <?= $form->field($model, 'photo') ?>

    <?= $form->field($model, 'text1') ?>

    <?php // echo $form->field($model, 'text2') ?>

    <?php // echo $form->field($model, 'text3') ?>

    <?php // echo $form->field($model, 'motorcycle') ?>

    <?php // echo $form->field($model, 'motorcyclePhoto') ?>

    <?php // echo $form->field($model, 'gif') ?>

    <?php // echo $form->field($model, 'link') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
