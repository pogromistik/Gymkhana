<?php

use kartik\widgets\Select2;
use yii\grid\GridView;
use common\models\ClassesRequest;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ClassesRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обработанные заявки на смену класса';
?>

<div class="tmp-participant-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'rowOptions'   => ['class' => 'gray'],
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'dateAdded',
				'format'    => 'raw',
				'value'     => function (ClassesRequest $item) {
					return date("d.m.Y, H:i", $item->dateAdded);
				}
			],
			[
				'attribute' => 'athleteId',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (ClassesRequest $item) {
					return $item->athlete->getFullName();
				}
			],
			[
				'attribute' => 'comment',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (ClassesRequest $item) {
					return nl2br(htmlspecialchars($item->comment));
				}
			],
			[
				'label'  => 'status',
				'format' => 'raw',
				'value'  => function (ClassesRequest $item) {
					return ClassesRequest::$statusesTitle[$item->status];
				}
			],
			'feedback',
			[
				'format'  => 'raw',
				'visible' => \Yii::$app->user->can('developer'),
				'value'   => function (ClassesRequest $item) {
					return \yii\helpers\Html::a('логи', ['/competitions/developer/logs',
						'modelClass' => ClassesRequest::class,
						'modelId'    => $item->id
					], ['class' => 'dev-logs dev-logs-btn']);
				}
			]
		],
	]); ?>
</div>