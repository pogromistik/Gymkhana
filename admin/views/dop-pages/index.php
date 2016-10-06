<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\DopPage;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\DopPageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Дополнительные страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dop-page-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить страницу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function (DopPage $page) {
                    return DopPage::$typesTitle[$page->type];
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
