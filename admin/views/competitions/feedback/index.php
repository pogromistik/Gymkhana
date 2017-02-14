<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\FeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обратная связь';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			'username',
			'phone',
			'email:email',
			[
				'attribute' => 'dateAdded',
				'format'    => 'raw',
				'value'     => function (\common\models\Feedback $item) {
					return date("d.m.Y, H:i", $item->dateAdded);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\Feedback $item) {
					return Html::a('<span class="fa fa-envelope-open"></span>', ['view', 'id' => $item->id], [
						'class' => 'btn btn-primary',
						'title' => 'Просмотр'
					]);
				}
			],
			[
				'format'  => 'raw',
				'visible' => \Yii::$app->user->can('admin'),
				'value'   => function (\common\models\Feedback $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['delete', 'id' => $item->id], [
						'class' => 'btn btn-danger',
						'title' => 'Удалить',
						'data'  => [
							'confirm' => 'Уверены? Отменить это действие будет невозможно.',
							'method'  => 'post',
						]
					]);
				}
			],
		],
	]); ?>
</div>
