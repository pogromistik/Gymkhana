<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\DocumentSectionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Документы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assoc-news-index">
    <p>
		<?= Html::a('Добавить раздел', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			'title:ntext',
			
			[
				'format' => 'raw',
				'value'  => function (\common\models\DocumentSection $item) {
					return Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $item->id], [
						'class' => 'btn btn-primary'
					]);
				}
			],
			[
				'format' => 'raw',
				'value'  => function (\common\models\DocumentSection $item) {
					if ($item->status) {
						return Html::a('<span class="fa fa-remove"></span>', ['change-status', 'id' => $item->id], [
							'class' => 'btn btn-danger',
							'data'  => [
								'confirm' => 'Уверены, что хотите заблокировать этот раздел?',
								'method'  => 'post',
							]
						]);
					} else {
						return Html::a('<span class="fa fa-remove"></span>', ['change-status', 'id' => $item->id], [
							'class' => 'btn btn-success',
							'data'  => [
								'confirm' => 'Уверены, что хотите разблокировать этот раздел?',
								'method'  => 'post',
							]
						]);
                    }
				}
			]
		],
	]); ?>
</div>
