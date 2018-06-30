<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\InterviewSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Опросы';
?>
<div class="interview-index">
    <p>
		<?= Html::a('Добавить опрос', ['create'], ['class' => 'btn btn-my-style btn-green']) ?>
    </p>
	
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			'dateStartHuman',
			'dateEndHuman',
			'title',
			[
				'format' => 'raw',
				'value'  => function (\common\models\Interview $interview) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $interview->id],
						['class' => 'btn btn-my-style btn-primary btn-sm', 'title' => 'Редактировать']);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\Interview $interview) {
					return Html::a('<span class="fa fa-question-circle"></span>', ['answers', 'id' => $interview->id],
						['class' => 'btn btn-my-style btn-light-aquamarine btn-sm', 'title' => 'Список вопросов']);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\Interview $interview) {
					return Html::a('<span class="fa fa-remove"></span>', ['delete', 'id' => $interview->id],
						['class' => 'btn btn-my-style btn-red btn-sm', 'title' => 'Список вопросов',
						 'data'  => [
							 'confirm' => 'Уверены, что хотите безвозвратно удалить опрос?'
						 ]
						]);
				}
			]
		],
	]); ?>
</div>
