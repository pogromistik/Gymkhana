<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\MarshalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Маршалы';
$this->params['breadcrumbs'][] = ['label' => 'О проекте'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marshal-index">

    <h1><?= Html::encode($this->title) ?></h1>
	
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
        <?= Html::a('Добавить маршала', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'post',
            'motorcycle',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
