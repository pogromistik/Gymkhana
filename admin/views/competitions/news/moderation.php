<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AssocNewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости от пользователей';
?>
<div class="assoc-news-index">
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			[
				'attribute' => 'title',
				'filter'    => '<div class="input-group">
  <span class="input-group-addon"><i class="fa fa-search"></i></span>
' . Html::activeInput('text', $searchModel, 'title', ['class' => 'form-control', 'placeholder' => 'Поиск по названию...']) . '
</div>',
			],
			[
				'attribute' => 'previewText',
				'filter'    => false,
			],
			[
				'attribute' => 'offerUserId',
				'label'     => 'Предложил',
				'filter'    => false,
				'format'    => 'raw',
				'value'     => function (\common\models\AssocNews $assocNews) {
					return $assocNews->offerUserId ? $assocNews->offerUser->getFullName() : ' ';
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\AssocNews $item) {
					return Html::a('<span class="fa fa-eye"></span>', ['view', 'id' => $item->id], [
						'class'  => 'btn btn-my-style btn-blue',
						'target' => '_blank'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\AssocNews $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['delete', 'id' => $item->id], [
						'class' => 'btn btn-my-style btn-red',
						'data'  => [
							'confirm' => 'Уверены, что хотите удалить эту новость?',
							'method'  => 'post',
						]
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\AssocNews $item) {
					return Html::a('<span class="fa fa-check"></span>', ['publish', 'id' => $item->id], [
						'class' => 'btn btn-my-style btn-green',
						'data'  => [
							'confirm' => 'Уверены, что хотите опубликовать эту новость?',
							'method'  => 'post',
						]
					]);
				}
			]
		],
	]); ?>
</div>
