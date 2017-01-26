<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AssocNewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Новости ассоциации';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="assoc-news-index">
    <p>
        <?= Html::a('Добавить новость', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title:ntext',
            'previewText',
            'link',
	
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
