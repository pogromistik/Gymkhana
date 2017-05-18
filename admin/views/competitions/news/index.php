<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AssocNewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости';
?>
<div class="assoc-news-index">
    <p>
		<?= Html::a('Добавить новость', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
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
				'attribute' => 'link',
				'filter'    => false,
			],
			
			[
				'format' => 'raw',
				'value'  => function (\common\models\AssocNews $item) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $item->id], [
						'class' => 'btn btn-primary'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\AssocNews $item) {
					return Html::a('<span class="fa fa-remove"></span>', ['delete', 'id' => $item->id], [
						'class' => 'btn btn-danger',
						'data'  => [
							'confirm' => 'Уверены, что хотите удалить эту новость?',
							'method'  => 'post',
						]
					]);
				}
			]
		],
	]); ?>
</div>
