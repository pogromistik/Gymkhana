<?php

use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;

/**
 * @var \yii\web\View                      $this
 */
$this->title = 'Объединение мотоциклов';
?>
<div class="merge-athletes">
	
	<?= \yii\bootstrap\Html::beginForm(['merge-motorcycles-second'], 'get') ?>

    <div class="row">
        <div class="col-md-6 pb-10">
            <?= Select2::widget([
	            'name'    => 'athleteId',
	            'data'    => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(), 'id', function (\common\models\Athlete $item) {
		            return $item->id . ' ' . $item->getFullName();
	            }),
	            'options' => [
		            'placeholder' => 'Выберите спортсмена...',
		            'id'          => 'athleteId',
	            ]
            ]) ?>
        </div>
    </div>
	
	<?= \yii\helpers\Html::submitButton('Далее', ['class' => 'btn btn-my-style btn-blue']) ?>
	
	<?= \yii\bootstrap\Html::endForm(); ?>

    <div id="secondStep"></div>

</div>