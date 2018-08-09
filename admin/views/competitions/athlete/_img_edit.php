<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;

/**
 * @var \common\models\Athlete $athlete
 */
?>

<h3><?= \Yii::t('app', 'Загрузить новое фото') ?></h3>
<div class="help-for-athlete">
    <div class="red">
        <b>Не загружайте фото без согласия спортсмена.</b>
    </div>
    <small><?= \Yii::t('app', 'Размер загружаемого изображения не должен превышать 1024КБ. Допустимые форматы: png, jpg. Необходимые пропорции: 3x4 (300x400 pixels)') ?>
    </small>
</div>
<div class="pt-10">
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	<?= $form->field($athlete, 'photo')->widget(\sadovojav\cutter\Cutter::className(), [
		'cropperOptions'        => [
			'aspectRatio' => 3 / 4
		],
		'defaultCropperOptions' => [
			'rotatable' => false,
			'zoomable'  => false,
			'movable'   => false,
		
		]]) ?>
    <div class="form-group">
		<?= Html::submitButton(\Yii::t('app', 'Загрузить'), ['class' => 'btn btn-primary']) ?>
    </div>
	<?php ActiveForm::end(); ?>
</div>