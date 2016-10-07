<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Russia */

$this->title = 'Редактировать город: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Россия', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="russia-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
