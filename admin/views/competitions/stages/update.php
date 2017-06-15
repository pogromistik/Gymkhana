<?php

use yii\helpers\Html;
use common\models\Championship;
use common\models\Stage;

/* @var $this yii\web\View */
/* @var $model common\models\Stage */

$this->title = 'Редактирование этапа: ' . $model->title;
$championship = $model->championship;
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$championship->groupId], 'url' => ['/competitions/championships/index', 'groupId' => $championship->groupId]];
$this->params['breadcrumbs'][] = ['label' => $championship->title, 'url' => ['/competitions/championships/view', 'id' => $model->championshipId]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="stage-update">
	
	<?= Html::a('Участники', ['/competitions/participants/index', 'stageId' => $model->id], ['class' => 'btn btn-success']) ?>
	<?php if ($model->status != Stage::STATUS_CALCULATE_RESULTS && $model->status != Stage::STATUS_PAST) { ?>
		<?= Html::a('Добавить время по фигурам',
			['/competitions/stages/add-figures-results', 'stageId' => $model->id], ['class' => 'btn btn-info-light']) ?>
	<?php } ?>
	<?= Html::a('Установить классы участникам', ['/competitions/participants/set-classes', 'stageId' => $model->id],
		[
			'class'   => 'btn btn-danger setParticipantsClasses',
			'data-id' => $model->id
		]) ?>
	<?= Html::a('Заезды', ['/competitions/participants/races', 'stageId' => $model->id], ['class' => 'btn btn-info']) ?>
	<?= Html::a('Пересчитать результаты', ['/competitions/stages/calculation-result', 'stageId' => $model->id],
		[
			'class'   => 'btn btn-default stageCalcResult',
			'data-id' => $model->id
		]) ?>
	<?= Html::a('Итоги', ['/competitions/stages/result', 'stageId' => $model->id], ['class' => 'btn btn-warning']) ?>
	<?php if ($championship->useMoscowPoints) { ?>
		<?= Html::a('Начислить баллы', ['/competitions/stages/accrue-points', 'stageId' => $model->id],
			[
				'class'       => 'btn btn-default accruePoints',
				'data-id'     => $model->id
			]) ?>
	<?php } ?>
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
