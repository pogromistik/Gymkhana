<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Album;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AlbumSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Альбомы';
$this->params['breadcrumbs'][] = 'Галерея';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить альбом', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'yearId',
                'format' => 'raw',
                'value' => function (Album $album) {
                    return $album->year ? $album->year->year : null;
                }
            ],
            'folder',
            [
                'attribute' => 'yearId',
                'format' => 'raw',
                'value' => function (Album $album) {
                    return date("d.m.Y, H:i", $album->dateAdded);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
