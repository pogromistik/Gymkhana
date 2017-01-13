<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Marshal */

$this->title = 'Редактировать маршала: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'О проекте'];
$this->params['breadcrumbs'][] = ['label' => 'Маршалы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="marshal-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
