<?php
use yii\bootstrap\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
?>

    <div class="compareWith">
        <div class="pb-10">
			<?= Html::beginForm(['/stats/compare-with'], 'get', ['id' => 'compareWith']) ?>
            сравнить свои результаты за <?= Html::dropDownList('year', null, ArrayHelper::map(
		        \common\models\Year::findAll(['status' => \common\models\Year::STATUS_ACTIVE]), 'year', 'year'),
		        ['class' => 'form-control', 'prompt' => 'всё время']
	        ) ?>
            с <?= Select2::widget([
				'name'          => 'athleteId',
				'data'          => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(\Yii::$app->user->id), 'id', function (\common\models\Athlete $item) {
					return $item->lastName . ' ' . $item->firstName;
				}),
				'options' => ['placeholder' => 'Выберите максимум 2 спортсменов...', 'multiple' => true],
				'pluginOptions' => [
					'tags' => true,
					'tokenSeparators' => [',', ' '],
					'maximumInputLength' => 10
				],
			]) ?>
			<?= Html::submitButton('сравнить', ['class' => 'btn btn-dark']) ?>
			<?= Html::endForm() ?>
        </div>

        <div class="alert alert-danger" style="display: none"></div>
    </div>

<div class="result"></div>