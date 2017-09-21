<?php
use common\models\Stage;
use yii\helpers\Html;

/**
 * @var \common\models\Stage        $model
 * @var \common\models\Championship $championship
 */
?>

<div class="row with-hr-border">
    <div class="col-md-6">
        <h3>Подготовка к этапу</h3>
		
		<?= Html::a('Участники', ['/competitions/participants/index', 'stageId' => $model->id],
            ['class' => 'btn btn-my-style btn-light-aquamarine']) ?>
		<?php if ($model->status != Stage::STATUS_CALCULATE_RESULTS && $model->status != Stage::STATUS_PAST) { ?>
			<?= Html::a('Добавить время по фигурам',
				['/competitions/stages/add-figures-results', 'stageId' => $model->id], ['class' => 'btn btn-my-style btn-light-blue']) ?>
		<?php } ?>
        <br>
		<?= Html::a('Установить класс соревнования', ['/competitions/participants/set-classes', 'stageId' => $model->id],
			[
				'class'   => 'btn btn-my-style btn-burgundy setParticipantsClasses',
				'data-id' => $model->id
			]) ?>
		<?= Html::a('Сформировать итоговые списки', ['/competitions/participants/set-final-list', 'stageId' => $model->id],
			[
				'class'   => 'btn btn-my-style btn-gray setFinalList',
				'data-id' => $model->id
			]) ?>

    </div>

    <div class="col-md-6">
        <h3>Проведение этапа</h3>
        
			<?= Html::a('Заезды', ['/competitions/participants/races', 'stageId' => $model->id],
				['class' => 'btn btn-my-style btn-aquamarine']) ?>
			
			<?= Html::a('Пересчитать результаты', ['/competitions/stages/calculation-result', 'stageId' => $model->id],
				[
					'class'   => 'btn btn-my-style btn-yellow stageCalcResult',
					'data-id' => $model->id
				]) ?>
			<?= Html::a('Итоги', ['/competitions/stages/result', 'stageId' => $model->id],
				['class' => 'btn btn-my-style btn-lilac']) ?>
			<?php if ($championship->useMoscowPoints) { ?><?= Html::a('Начислить баллы', ['/competitions/stages/accrue-points', 'stageId' => $model->id],
				[
					'class'   => 'btn btn-my-style btn-peach accruePoints',
					'data-id' => $model->id
				]) ?>
			<?php } ?>
    </div>
</div>

