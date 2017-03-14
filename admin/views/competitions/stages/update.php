<?php

use yii\helpers\Html;
use common\models\Championship;

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
	<?= Html::a('Установить классы участникам', ['/competitions/participants/set-classes', 'stageId' => $model->id],
		[
			'class'   => 'btn btn-default setParticipantsClasses',
			'data-id' => $model->id
		]) ?>
	<?= Html::a('Заезды', ['/competitions/participants/races', 'stageId' => $model->id], ['class' => 'btn btn-info']) ?>
	<?= Html::a('Итоги', ['/competitions/stages/result', 'stageId' => $model->id], ['class' => 'btn btn-warning']) ?>
	<?= Html::a('Пересчитать результаты', ['/competitions/stages/calculation-result', 'stageId' => $model->id],
		[
			'class'   => 'btn btn-default stageCalcResult',
			'data-id' => $model->id
		]) ?>
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
