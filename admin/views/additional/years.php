<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Year;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\YearSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Года';
$this->params['breadcrumbs'][] = 'Дополнительно';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="years-index">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Добавить год', ['/additional/year-view'], ['class' => 'btn btn-success']) ?>
	</p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],

			[
				'attribute' => 'year',
				'format'    => 'raw',
				'value'     => function (Year $year) {
					return Html::a($year->year, ['/additional/year-view', 'yearId' => $year->id]);
				}
			],
			[
				'attribute' => 'status',
				'format'    => 'raw',
				'value'     => function (Year $year) {
					return Year::$statusesTitle[$year->status];
				}
			]
		],
	]); ?>
</div>
