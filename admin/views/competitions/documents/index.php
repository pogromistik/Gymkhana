<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\DocumentSectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
?>
<div class="assoc-news-index">
	<?php if (\Yii::$app->user->can('globalWorkWithCompetitions')) { ?>
        <p>
			<?= Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-my-style btn-green']) ?>
        </p>
	<?php } ?>
	
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
				'format' => 'raw',
				'value'  => function (\common\models\DocumentSection $item) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $item->id], [
						'class' => 'btn btn-my-style btn-blue'
					]);
				}
			],
			[
				'format'  => 'raw',
				'visible' => \Yii::$app->user->can('globalWorkWithCompetitions'),
				'value'   => function (\common\models\DocumentSection $item) {
					if ($item->status) {
						return Html::a('<span class="fa fa-remove"></span>', ['change-status', 'id' => $item->id], [
							'class' => 'btn btn-my-style btn-red',
							'title' => 'Заблокировать раздел'
						]);
					} else {
						return Html::a('<span class="fa fa-check"></span>', ['change-status', 'id' => $item->id], [
							'class' => 'btn btn-my-style btn-boggy',
							'title' => 'Разблокировать раздел'
						]);
					}
				}
			]
		],
	]); ?>
</div>
