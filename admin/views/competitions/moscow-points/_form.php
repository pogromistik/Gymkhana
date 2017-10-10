<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model common\models\MoscowPoint */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="moscow-point-form">

    <?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'class')->widget(Select2::classname(), [
		'name'    => 'kv-type-01',
		'data'    =>  \yii\helpers\ArrayHelper::map(\common\models\AthletesClass::find()->orderBy(['title' => SORT_ASC])->all(), 'id', 'title'),
		'options' => [
			'placeholder' => 'Выберите группу...',
		],
	]) ?>

    <?= $form->field($model, 'place')->textInput() ?>

    <?= $form->field($model, 'point')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',
            ['class' => $model->isNewRecord ? 'btn btn-my-style btn-green' : 'btn btn-my-style btn-blue']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
