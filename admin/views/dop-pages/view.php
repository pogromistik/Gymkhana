<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DopPage */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Доп страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dop-page-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'picture',
                'format'    => 'raw',
                'value'     => Html::img(Yii::getAlias('@filesView') . $model->picture)
            ],

            'type',
        ],
    ]) ?>

</div>
