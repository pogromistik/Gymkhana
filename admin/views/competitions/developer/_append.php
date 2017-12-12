<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var int                    $i
 * @var \common\models\Athlete $firstAthlete
 * @var \common\models\Athlete $secondAthlete
 */
?>
<div class="row">
    <div class="col-sm-12">
        <hr>
    </div>
    <div class="col-md-5">
		<?= Html::dropDownList('firstMotorcycles[' . $i . ']', null, ArrayHelper::map($firstAthlete->activeMotorcycles, 'id',
			function (\common\models\Motorcycle $item) {
				return $item->getFullTitle();
			}),
			[
				'class'  => 'form-control',
				'prompt' => 'Выберите мотоцикл для объединения'
			]) ?>
    </div>
    <div class="col-md-5">
		<?= Html::dropDownList('secondMotorcycles[' . $i . ']', null, ArrayHelper::map($secondAthlete->activeMotorcycles, 'id',
			function (\common\models\Motorcycle $item) {
				return $item->getFullTitle();
			}),
			[
				'class'  => 'form-control',
				'prompt' => 'Выберите мотоцикл для объединения'
			]) ?>
    </div>
    <div class="col-md-2">
        <a href="#" class="btn btn-my-style btn-red deleteMergeMotorcycle"><span class="fa fa-remove"></span></a>
    </div>
</div>