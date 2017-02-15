<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Championship;

/* @var $this yii\web\View */
/* @var $model common\models\Championship */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Разделы чемпионатов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$model->groupId], 'url' => ['index', 'groupId' => $model->groupId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="championship-view">
    <p>
		<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Добавить этап', ['/competitions/stages/create', 'championshipId' => $model->id], [
			'class' => 'btn btn-success',
			'title' => 'Просмотр'
		]); ?>
    </p>
	
	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'title',
			'description:ntext',
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
        </tr>
        </thead>
		<?php foreach ($model->stages as $stage) { ?>
            <tr>
                <td><?= Html::a($stage->title, ['/competitions/stages/view', 'id' => $stage->id]) ?></td>
                <td><?= $stage->dateOfTheHuman ?></td>
                <td><?= $stage->startRegistrationHuman ?></td>
                <td><?= $stage->endRegistrationHuman ?></td>
                <td><?= \common\models\Stage::$statusesTitle[$stage->status] ?></td>
                <td><?= $stage->class ? $stage->classModel->title : null ?></td>
            </tr>
		<?php } ?>
    </table>
	
	<?php if ($model->internalClasses) { ?>
        <h3>Классы награждения</h3>
		<?= $this->render('_classes', [
			'model' => $model,
		]) ?>
	<?php } ?>
</div>
