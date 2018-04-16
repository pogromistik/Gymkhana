<?php
use yii\bootstrap\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
?>

    <div class="compareWith">
        <div class="pb-10">
			<?= Html::beginForm(['/stats/compare-with'], 'get', ['id' => 'compareWith']) ?>
            <?= \Yii::t('app', 'сравнить свои результаты за {year} с {fullName}', [
                    'year' => Html::dropDownList('year', null, ArrayHelper::map(
	                    \common\models\Year::find()->where(['status' => \common\models\Year::STATUS_ACTIVE])->orderBy(['year' => SORT_ASC])->all(), 'year', 'year'),
	                    ['class' => 'form-control', 'prompt' => \Yii::t('app', 'всё время')]
                    ),
                'fullName' => Select2::widget([
	                'name'          => 'athleteId',
	                'data'          => ArrayHelper::map(\common\models\Athlete::getActiveAthletes(\Yii::$app->user->id), 'id', function (\common\models\Athlete $item) {
		                return $item->lastName . ' ' . $item->firstName;
	                }),
	                'options' => ['placeholder' => \Yii::t('app', 'Выберите максимум 2 спортсменов') . '...', 'multiple' => true],
	                'pluginOptions' => [
		                'tags' => true,
		                'tokenSeparators' => [',', ' '],
		                'maximumInputLength' => 10
	                ],
                ])
            ]) ?>
			<?= Html::submitButton(\Yii::t('app', 'сравнить'), ['class' => 'btn btn-dark']) ?>
			<?= Html::endForm() ?>
        </div>

        <div class="alert alert-danger" style="display: none"></div>
    </div>

<div class="result"></div>