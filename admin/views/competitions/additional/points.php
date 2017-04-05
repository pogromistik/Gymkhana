<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\PointSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Баллы для подсчёта итогов чемпионата';
?>
<div class="point-index">

    <p>
		<?= Html::a('Добавить балл', ['create-points'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			'id',
			'point',
			
			[
				'format' => 'raw',
				'value'  => function (\common\models\Point $item) {
					return Html::a('<span class="fa fa-edit"></span>', ['update-points', 'id' => $item->id], [
						'class' => 'btn btn-primary',
						'title' => 'Редактировать'
					]);
				}
			],
			
			[
				'format' => 'raw',
				'value'  => function (\common\models\Point $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['delete-points', 'id' => $item->id], [
						'class' => 'btn btn-danger',
						'title' => 'Удалить',
						'data'  => [
							'confirm' => 'Уверены, что хотите полностью удалить эту запись?'
						]
					]);
				}
			],
		],
	]); ?>
</div>
