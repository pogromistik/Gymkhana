<?php

use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\grid\GridView;
use common\models\TmpFigureResult;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpFigureResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Результаты фигур, требующие одобрения';
?>

<div class="tmp-participant-index table-responsive">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
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
			[
				'attribute' => 'dateForHuman',
				'format'    => 'raw',
				'value'     => function (TmpFigureResult $figureResult) {
					return $figureResult->dateForHuman;
				}
			],
			
			[
				'label'  => 'Мотоцикл',
				'format' => 'raw',
				'value'  => function (TmpFigureResult $figureResult) {
					return $figureResult->motorcycle->getFullTitle();
				}
			],
			[
				'attribute' => 'timeForHuman',
				'label'     => 'Время',
				'format'    => 'raw',
				'value'     => function (TmpFigureResult $figureResult) {
					return $figureResult->timeForHuman;
				}
			],
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
				'label'  => 'Статус',
				'format' => 'raw',
				'filter' => Html::activeDropDownList($searchModel, 'status', TmpFigureResult::$statusesTitle,
					['class' => 'form-control', 'prompt' => 'Выберите статус']),
				'value'  => function (TmpFigureResult $figureResult) {
					if ($figureResult->isNew) {
						return 'На рассмотерении';
					} else {
						if ($figureResult->cancelReason) {
							return 'Отклонена';
						} else {
							return 'Подтверждена';
						}
					}
				}
			],
			'cancelReason'
		],
	]); ?>
</div>