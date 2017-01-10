<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Collapse;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\RussiaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Россия';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="russia-index">

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

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
