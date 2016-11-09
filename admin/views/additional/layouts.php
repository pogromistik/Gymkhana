<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\LayoutSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Шаблоны страниц';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="layout-index">

	<p>
		<?= Html::a('Добавить шаблон', ['layout-info'], ['class' => 'btn btn-success']) ?>
	</p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],

			[
				'attribute' => 'id',
				'format'    => 'raw',
				'value'     => function (\common\models\Layout $layout) {
					return Html::a($layout->id, ['/additional/layout-info', 'layoutId' => $layout->id]);
				}
			],
            'title',
        ],
    ]); ?>
</div>
