<?php

use yii\helpers\Html;
use kartik\widgets\Select2;
use yii\grid\GridView;
use common\models\TmpFigureResult;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TmpFigureResultSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<h2><?= $this->context->pageTitle ?></h2>

<div class="tmp-participant-index table-responsive card-box">
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
						'placeholder' => \Yii::t('app', 'Выберите фигуру') . '...',
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
				'label'  => \Yii::t('app', 'Мотоцикл'),
				'format' => 'raw',
				'value'  => function (TmpFigureResult $figureResult) {
					return $figureResult->motorcycle->getFullTitle();
				}
			],
			[
				'attribute' => 'timeForHuman',
				'label'     => \Yii::t('app', 'Время'),
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
				'label'  => \Yii::t('app', 'Статус'),
				'format' => 'raw',
				'filter' => Html::activeDropDownList($searchModel, 'status', TmpFigureResult::$statusesTitle,
					['class' => 'form-control', 'prompt' => \Yii::t('app', 'Выберите статус')]),
				'value'  => function (TmpFigureResult $figureResult) {
					if ($figureResult->isNew) {
						return \Yii::t('app', 'На рассмотрении');
					} else {
						if ($figureResult->cancelReason) {
							return \Yii::t('app', 'Отклонена');
						} else {
							return \Yii::t('app', 'Подтверждена');
						}
					}
				}
			],
			'cancelReason'
		],
	]); ?>
</div>