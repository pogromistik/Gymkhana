<?php
use common\models\Stage;
use yii\helpers\Html;

/**
 * @var \common\models\Stage        $model
 * @var \common\models\Championship $championship
 */
?>

<?= Html::a('Участники', ['/competitions/participants/index', 'stageId' => $model->id], ['class' => 'btn btn-success']) ?>
<?php if ($model->status != Stage::STATUS_CALCULATE_RESULTS && $model->status != Stage::STATUS_PAST) { ?>
	<?= Html::a('Добавить время по фигурам',
		['/competitions/stages/add-figures-results', 'stageId' => $model->id], ['class' => 'btn btn-info-light']) ?>
<?php } ?>
<div class="pt-10">
	<?= Html::a('Установить классы участникам и класс соревнования', ['/competitions/participants/set-classes', 'stageId' => $model->id],
		[
			'class'   => 'btn btn-danger setParticipantsClasses',
			'data-id' => $model->id
		]) ?>
	<?= Html::a('Сформировать итоговые списки', ['/competitions/participants/set-final-list', 'stageId' => $model->id],
		[
			'class'   => 'btn btn-primary setFinalList',
			'data-id' => $model->id
		]) ?>
</div>
<div class="pt-10">
	<?= Html::a('Заезды', ['/competitions/participants/races', 'stageId' => $model->id], ['class' => 'btn btn-info']) ?>
	<?= Html::a('Пересчитать результаты', ['/competitions/stages/calculation-result', 'stageId' => $model->id],
		[
			'class'   => 'btn btn-default stageCalcResult',
			'data-id' => $model->id
		]) ?>
	<?= Html::a('Итоги', ['/competitions/stages/result', 'stageId' => $model->id], ['class' => 'btn btn-warning']) ?>
	<?php if ($championship->useMoscowPoints) { ?><?= Html::a('Начислить баллы', ['/competitions/stages/accrue-points', 'stageId' => $model->id],
		[
			'class'   => 'btn btn-default accruePoints',
			'data-id' => $model->id
		]) ?>
	<?php } ?>
</div>
