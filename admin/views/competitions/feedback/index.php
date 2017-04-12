<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\FeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обратная связь';
?>

<div class="alert alert-info">
    Зелёным цветом выделены новые заявки
</div>

<div class="feedback-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			[
				'attribute'      => 'username',
				'format'         => 'raw',
				'value'          => function (\common\models\Feedback $item) {
					if ($item->athleteId) {
						return Html::a($item->username, ['/competitions/athlete/update', 'id' => $item->athleteId],
							['target' => '_blank']);
					}
					
					return $item->username;
				},
				'contentOptions' => function (\common\models\Feedback $item) {
					if ($item->isNew) {
					    return ['class' => 'bg-green'];
                    };
					return [];
				},
			],
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
				'visible' => \Yii::$app->user->can('globalWorkWithCompetitions'),
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
