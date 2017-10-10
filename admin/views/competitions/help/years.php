<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Year;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\YearSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Года';
?>
<div class="years-index">

    <p>
		<?= Html::a('Добавить год', ['/competitions/help/year-view'], ['class' => 'btn btn-my-style btn-green']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'year',
				'format'    => 'raw',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'year', ['class' => 'form-control', 'placeholder' => 'Поиск по году...']) . '
</div>',
				'value'     => function (Year $year) {
					return Html::a($year->year, ['/competitions/help/year-view', 'yearId' => $year->id]);
				}
			],
			[
				'attribute' => 'status',
				'format'    => 'raw',
				'filter'    => false,
				'value'     => function (Year $year) {
					return Year::$statusesTitle[$year->status];
				}
			]
		],
	]); ?>
</div>
