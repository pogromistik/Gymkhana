<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DopPage */

$this->title = 'Редактировать страницу: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Доп страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dop-page-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
