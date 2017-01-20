<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\TrackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $page \common\models\Page */

$this->title = 'Трассы';
?>

<?= Collapse::widget([
	'items' => [
		[
			'label'   => 'Настройки страницы',
			'content' => $this->render('//common/_page-form', ['model' => $page])
		],
	]
]);
?>

<div class="track-index">

    <p>
        <?= Html::a('Добавить трассу', ['create-or-update'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            'title',
	
	        [
		        'format' => 'raw',
		        'value'  => function (\common\models\Track $item) {
			        return Html::a('<span class="fa fa-edit"></span>', ['/tracks/create-or-update', 'id' => $item->id], [
				        'class' => 'btn btn-primary'
			        ]);
		        }
	        ],
	        [
		        'format' => 'raw',
		        'value'  => function (\common\models\Track $item) {
			        return Html::a('<span class="fa fa-remove"></span>', ['/tracks/delete', 'id' => $item->id], [
				        'class' => 'btn btn-danger',
				        'data'  => [
					        'confirm' => 'Уверены, что хотите удалить эти трассы? Документы, связанные с ними, тоже будут удалены',
					        'method'  => 'post',
				        ]
			        ]);
		        }
	        ]
        ],
    ]); ?>
</div>
