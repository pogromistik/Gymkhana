<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\FigureSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Фигуры';
?>
<div class="figure-index">

    <p>
		<?= Html::a('Добавить фигуру', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'title',
				'format'    => 'raw',
				'value'     => function (\common\models\Figure $figure) {
					return Html::a($figure->title, ['update', 'id' => $figure->id]);
				}
			],
			'bestTimeForHuman',
			'bestTimeInRussiaForHuman',
			[
				'visible' => \Yii::$app->user->can('developer'),
				'format'  => 'raw',
				'value'   => function (\common\models\Figure $figure) {
					return Html::a('логи', ['/competitions/developer/logs',
						'modelClass' => \common\models\Figure::class,
						'modelId'    => $figure->id
					], ['class' => 'btn btn-default']);
				}
			]
		],
	]); ?>
</div>
