<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\StageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Этапы';
?>
<div class="stage-index">

	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'championshipId',
				'format'    => 'raw',
				'value'     => function (\common\models\Stage $stage) {
					return $stage->championship->title;
				}
			],
			'title',
			[
				'attribute' => 'cityId',
				'format'    => 'raw',
				'filter'    => Select2::widget([
					'model'     => $searchModel,
					'attribute' => 'cityId',
					'data'      => \common\models\City::getAll(true),
					'theme'     => Select2::THEME_BOOTSTRAP,
					'options'   => [
						'placeholder' => 'Укажите город...',
					]
				]),
				'value'     => function (\common\models\Stage $stage) {
					return $stage->city->title;
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\Stage $stage) {
					return Html::a('<span class="fa fa-eye"></span>', ['view', 'id' => $stage->id], [
						'class' => 'btn btn-info',
						'title' => 'Просмотр'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\Stage $stage) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $stage->id], [
						'class' => 'btn btn-primary',
						'title' => 'Редактирование'
					]);
				}
			],
            'referenceTime'
		],
	]); ?>
</div>
