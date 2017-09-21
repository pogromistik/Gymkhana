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
		<?= Html::a('Добавить фигуру', ['create'], ['class' => 'btn btn-my-style btn-green']) ?>
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
		],
	]); ?>
</div>
