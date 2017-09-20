<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\ClassesRequest;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\ClassesRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<h2><?= $this->context->pageTitle ?></h2>

<div class="tmp-participant-index table-responsive">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'newClassId',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (ClassesRequest $item) {
					return $item->class->title;
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
				'attribute' => 'status',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (ClassesRequest $item) {
					return ClassesRequest::$statusesTitle[$item->status];
				}
			],
			[
				'attribute' => 'feedback',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (ClassesRequest $item) {
					return $item->feedback;
				}
			]
		],
	]); ?>
</div>