<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\CitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Россия';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="russia-index">
	
	<?= Collapse::widget([
		'items' => [
			[
				'label'   => 'Настройки страницы',
				'content' => $this->render('//common/_page-form', ['model' => $page])
			],
		]
	]);
	?>

    <p>
        <?= Html::a('Добавить город', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'link',
            'top',
            'left',
            [
                'attribute' => 'regionId',
                'format' => 'raw',
                'value' => function (\common\models\City $city) {
                    return $city->regionId ? $city->region->title : null;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
