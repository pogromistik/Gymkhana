<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\RequestForSpecialStage;

/**
 * @var \yii\web\View                                     $this
 * @var common\models\search\RequestForSpecialStageSearch $searchModel
 * @var \yii\data\ActiveDataProvider                      $dataProvider
 */
?>
<div class="request-for-special-stage-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'motorcycleId',
				'value'     => function (RequestForSpecialStage $item) {
					return $item->motorcycleId ? $item->motorcycle->getFullTitle() : '';
				}
			],
			'resultTimeHuman',
			[
				'label'     => 'Видео',
				'attribute' => 'videoLink',
				'format'    => 'raw',
				'value'     => function (RequestForSpecialStage $item) {
					return Html::a('посмотреть', $item->videoLink, ['target' => '_blank']);
				}
			],
			'cancelReason',
			[
				'attribute' => 'status',
				'format'    => 'raw',
				'value'     => function (RequestForSpecialStage $item) {
					return RequestForSpecialStage::$statusesTitle[$item->status];
				}
			]
		],
	]); ?>
</div>