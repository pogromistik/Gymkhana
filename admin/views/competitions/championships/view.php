<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Championship;

/* @var $this yii\web\View */
/* @var $model common\models\Championship */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$model->groupId], 'url' => ['index', 'groupId' => $model->groupId]];
$this->params['breadcrumbs'][] = $this->title;

$stages = $model->stages;
?>
<div class="championship-view">
	
	<?php if (!$stages) { ?>
        <div class="alert alert-danger">Даже если у вас одноэтапный чемпионат, необходимо всё равно создать этап.</div>
	<?php } ?>

    <p>
		<?php if (\Yii::$app->user->can('projectAdmin')) { ?>
			<?= Html::a('Редактировать', ['update', 'id' => $model->id],
				['class' => 'btn btn-my-style btn-blue']) ?>
			<?= Html::a('Добавить этап', ['/competitions/stages/create', 'championshipId' => $model->id], [
				'class' => 'btn btn-my-style btn-light-green'
			]); ?>
		<?php } ?>
		<?= Html::a('Результаты', ['/competitions/championships/results', 'championshipId' => $model->id], [
			'class' => 'btn btn-my-style btn-lilac'
		]); ?>
    </p>
	
	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'title',
			[
				'attribute' => 'description',
				'format'    => 'raw'
			],
			[
				'attribute' => 'yearId',
				'value'     => $model->year->year
			],
			[
				'attribute' => 'status',
				'value'     => Championship::$statusesTitle[$model->status]
			],
			[
				'attribute' => 'groupId',
				'value'     => Championship::$groupsTitle[$model->groupId]
			],
			[
				'attribute' => 'regionGroupId',
				'value'     => $model->regionGroupId ? $model->regionalGroup->title : ''
			],
			[
				'attribute' => 'dateAdded',
				'value'     => date("d.m.Y, H:i", $model->dateAdded)
			],
			[
				'attribute' => 'dateUpdated',
				'value'     => date("d.m.Y, H:i", $model->dateUpdated)
			],
			[
				'attribute' => 'onlyRegions',
				'value'     => $model->getRegionsFor(true),
				'visible'   => $model->isClosed
			],
		],
	]) ?>

    <h3>Этапы</h3>
    <table class="table">
        <thead>
        <tr>
            <th>Название</th>
            <th>Дата проведения</th>
            <th>Старт регистрации</th>
            <th>Завершение регистрации</th>
            <th>Статус</th>
            <th>Класс соревнования</th>
            <th></th>
        </tr>
        </thead>
		<?php foreach ($stages as $stage) { ?>
            <tr>
                <td><?= Html::a($stage->title, ['/competitions/stages/view', 'id' => $stage->id]) ?></td>
                <td><?= $stage->dateOfTheHuman ?></td>
                <td><?= $stage->startRegistrationHuman ?></td>
                <td><?= $stage->endRegistrationHuman ?></td>
                <td><?= \common\models\Stage::$statusesTitle[$stage->status] ?></td>
                <td><?= $stage->class ? $stage->classModel->title : null ?></td>
                <td>
					<?= Html::a('<span class="fa fa-user btn-my-style btn-light-aquamarine small"></span>',
						['/competitions/participants/index', 'stageId' => $stage->id]) ?>
					<?php if (\Yii::$app->user->can('projectAdmin')) { ?>
                        &nbsp;
						<?= Html::a('<span class="fa fa-edit btn-my-style btn-blue small"></span>',
							['/competitions/stages/update', 'id' => $stage->id]) ?>
					<?php } ?>
                </td>
            </tr>
		<?php } ?>
    </table>
	
	<?php if (\Yii::$app->user->can('projectAdmin')) { ?>
        <h3>Классы награждения</h3>
		<?= $this->render('_classes', [
			'model' => $model,
		]) ?>
	<?php } else { ?>
		<?php if ($model->activeInternalClasses) { ?>
            <h3>Классы награждения</h3>
            <table class="table">
				<?php foreach ($model->activeInternalClasses as $class) { ?>
                    <tr>
                        <td><?= $class->title ?></td>
                        <td><?= $class->description ?></td>
                    </tr>
				<?php } ?>
            </table>
		<?php } ?>
	<?php } ?>
</div>
