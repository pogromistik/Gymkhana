<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\FeedbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Обратная связь';
?>

<div class="alert help-alert alert-info">
    <div class="text-right">
        <span class="fa fa-remove closeHintBtn"></span>
    </div>
    Зелёным цветом выделены новые заявки
</div>

<div class="feedback-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'rowOptions' => function (\common\models\Feedback $item) {
			return ['class' => $item->isNew ? 'green-grid-row' : ''];
		},
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
	    $class = $item->isNew ? 'fa fa-envelope' : 'fa fa-envelope-open';
					return Html::a('<span class="'.$class.'"></span>', ['view', 'id' => $item->id], [
						'class' => $item->isNew ? 'btn btn-my-style btn-green small' : 'btn btn-my-style btn-blue small',
						'title' => 'Просмотр'
					]);
				}
			],
			[
				'format'  => 'raw',
				'visible' => \Yii::$app->user->can('globalWorkWithCompetitions'),
				'value'   => function (\common\models\Feedback $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['delete', 'id' => $item->id], [
						'class' => 'btn btn-my-style btn-red small',
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
