<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MoscowPoint */

$this->title = 'Редактировать балл: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Баллы за чемпионат по Московской схеме', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="moscow-point-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
