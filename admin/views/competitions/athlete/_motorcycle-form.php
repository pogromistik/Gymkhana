<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $motorcycle common\models\Motorcycle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="athlete-form">
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($motorcycle, 'athleteId')->hiddenInput()->label(false)->error(false) ?>
	<div class="row">
		<div class="col-md-4 col-sm-3">
			<?= $form->field($motorcycle, 'mark')->textInput(['placeholder' => 'марка, напр. kawasaki'])->label(false) ?>
		</div>
        <div class="col-md-4 col-sm-3">
			<?= $form->field($motorcycle, 'model')->textInput(['placeholder' => 'модель, напр. ER6-F'])->label(false) ?>
        </div>
        <div class="col-md-2 col-sm-3">
			<?= $form->field($motorcycle, 'cbm')->textInput(['placeholder' => 'объём'])->label(false) ?>
        </div>
		<div class="col-md-2 col-sm-3">
			<div class="form-group complete">
				<?= Html::submitButton('Добавить', ['class' => 'btn btn-success']) ?>
			</div>
		</div>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>
