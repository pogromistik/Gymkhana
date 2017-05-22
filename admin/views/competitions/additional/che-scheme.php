<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CheSchemeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Классы награждения: Челябинская схема';
?>
<div class="che-scheme-index">
	<p>
		<?= Html::a('Добавить класс награждения', ['create-class'], ['class' => 'btn btn-success']) ?>
	</p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],
			
			'title',
			'description',
			'percent',
			
			[
				'format' => 'raw',
				'value'  => function (\common\models\CheScheme $item) {
					return Html::a('<span class="fa fa-edit"></span>', ['update-class', 'id' => $item->id], [
						'class' => 'btn btn-primary',
						'title' => 'Редактировать'
					]);
				}
			]
		],
	]); ?>
</div>