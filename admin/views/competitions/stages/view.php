<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Stage */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Чемпионаты', 'url' => ['/competitions/championships/index']];
$this->params['breadcrumbs'][] = ['label' => $model->championship->title, 'url' => ['/competitions/championships/view', 'id' => $model->championshipId]];
$this->params['breadcrumbs'][] = ['label' => 'Все этапы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stage-view">

    <p>
		<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Участники', ['/competitions/participants/index', 'stageId' => $model->id], ['class' => 'btn btn-success']) ?>
		<?= Html::a('Установить классы участникам', ['/competitions/stages/calculation-result', 'stageId' => $model->id],
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
    </p>
	
	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			[
				'attribute' => 'championshipId',
				'value'     => $model->championship->title
			],
			'title',
			'location',
			[
				'attribute' => 'cityId',
				'value'     => $model->city->title
			],
			'description',
			'countRace',
			[
				'attribute' => 'dateAdded',
				'value'     => date("d.m.Y, H:i", $model->dateAdded)
			],
			[
				'attribute' => 'dateOfThe',
				'value'     => $model->dateOfThe ? $model->dateOfTheHuman : null
			],
			[
				'attribute' => 'startRegistration',
				'value'     => $model->startRegistration ? $model->startRegistrationHuman : null
			],
			[
				'attribute' => 'endRegistration',
				'value'     => $model->endRegistration ? $model->endRegistrationHuman : null
			],
			[
				'attribute' => 'status',
				'value'     => \common\models\Stage::$statusesTitle[$model->status]
			],
			[
				'attribute' => 'class',
				'value'     => $model->class ? $model->classModel->title : null
			],
            'referenceTimeHuman'
		],
	]) ?>

</div>
