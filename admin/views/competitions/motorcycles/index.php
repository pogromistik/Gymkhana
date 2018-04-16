<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MotorcyclesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Motorcycles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="motorcycle-index">
	
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'athleteId',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (\common\models\Motorcycle $motorcycle) {
					return $motorcycle->athlete->getFullName();
				}
			],
			'mark',
			'model',
			[
				'attribute' => 'dateAdded',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (\common\models\Motorcycle $motorcycle) {
					return date('d.m.Y', $motorcycle->dateAdded);
				}
			],
			'cbm',
			'power',
			[
				'attribute' => 'isCruiser',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (\common\models\Motorcycle $motorcycle) {
					return $motorcycle->isCruiser ? 'Да' : 'Нет';
				}
			],
			[
				'format'    => 'raw',
				'value'     => function (\common\models\Motorcycle $motorcycle) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $motorcycle->id], [
						'class' => 'btn btn-my-style btn-blue',
						'title' => 'Редактировать'
					]);
				}
            ]
		],
	]); ?>
</div>