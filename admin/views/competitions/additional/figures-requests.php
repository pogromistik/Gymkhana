<?php

use yii\grid\GridView;
use kartik\widgets\Select2;
use common\models\TmpFigureResult;
use yii\bootstrap\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpFigureResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обработанные заявки с результатами базовых фигур';
?>
<div class="tmp-athlete-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'rowOptions'   => ['class' => 'gray'],
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'dateAdded',
				'format'    => 'raw',
				'value'     => function (TmpFigureResult $figureResult) {
					return date("d.m.Y, H:i", $figureResult->dateAdded);
				}
			],
			[
				'attribute' => 'figureId',
				'format'    => 'raw',
				'filter'    => Select2::widget([
					'model'         => $searchModel,
					'attribute'     => 'figureId',
					'data'          => \yii\helpers\ArrayHelper::map(\common\models\Figure::find()->orderBy(['title' => SORT_DESC])->all(), 'id', 'title'),
					'theme'         => Select2::THEME_BOOTSTRAP,
					'pluginOptions' => [
						'allowClear' => true
					],
					'options'       => [
						'placeholder' => 'Выберите фигуру...',
					]
				]),
				'value'     => function (TmpFigureResult $figureResult) {
					return $figureResult->figure->title;
				}
			],
			'dateForHuman',
			[
				'label'  => 'Данные о спортсмене',
				'format' => 'raw',
				'value'  => function (TmpFigureResult $figureResult) {
					return $figureResult->athlete->getFullName() . '<br>' . $figureResult->motorcycle->getFullTitle();
				}
			],
			'timeForHuman',
			[
				'attribute' => 'fine',
				'filter'    => false,
			],
			[
				'attribute' => 'videoLink',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (TmpFigureResult $figureResult) {
					return Html::a($figureResult->videoLink, $figureResult->videoLink, ['target' => '_blank']);
				}
			],
			[
				'attribute' => 'cancelReason',
				'filter'    => false,
			],
			[
				'format'  => 'raw',
				'visible' => \Yii::$app->user->can('developer'),
				'value'   => function (TmpFigureResult $figureResult) {
					return \yii\helpers\Html::a('логи', ['/competitions/developer/logs',
						'modelClass' => TmpFigureResult::class,
						'modelId'    => $figureResult->id
					], ['class' => 'dev-logs dev-logs-btn']);
				}
			]
		],
	]); ?>
</div>