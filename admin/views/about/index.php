<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\AboutBlockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'О проекте';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="about-block-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-info">
        <b>Страница "о проекте" состоит из информационных блоков. </b> Блок - слайдер+текст.
        Можно добавить только слайдер или только текст. Блоки сортируются по полю "сортировка" от меньшего к большему. На слайдер можно добавить текст, но не обязательно.
    </div>

    <p>
        <?= Html::a('Добавить новый блок', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'sliderText',
            'sort',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
