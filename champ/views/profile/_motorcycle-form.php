<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $athlete common\models\Athlete */
/* @var $form yii\widgets\ActiveForm */
$motorcycle = new \common\models\Motorcycle();
$motorcycle->athleteId = $athlete->id;
?>

<div class="athlete-form">
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($motorcycle, 'athleteId')->hiddenInput()->label(false)->error(false) ?>
    <div class="row">
        <div class="col-md-5 col-sm-4">
			<?= $form->field($motorcycle, 'mark')->textInput(['placeholder' => \Yii::t('app', 'марка, напр. kawasaki')])->label(false) ?>
        </div>
        <div class="col-md-5 col-sm-4">
			<?= $form->field($motorcycle, 'model')->textInput(['placeholder' => \Yii::t('app', 'модель, напр. ER6-F')])->label(false) ?>
        </div>
        <div class="col-md-5 col-sm-4">
			<?= $form->field($motorcycle, 'cbm')->textInput(['placeholder' => \Yii::t('app', 'объём, м3')])->label(false) ?>
        </div>
        <div class="col-md-5 col-sm-4">
			<?= $form->field($motorcycle, 'power')->textInput(['placeholder' => \Yii::t('app', 'Мощность, л.с.')])->label(false) ?>
        </div>
        <div class="col-md-2 col-sm-4">
            <div class="form-group complete">
				<?= Html::submitButton(\Yii::t('app', 'Добавить'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
