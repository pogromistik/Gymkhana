<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Point */

$this->title = 'Редактирование баллов: ' . $model->id . ' место';
$this->params['breadcrumbs'][] = ['label' => 'Баллы', 'url' => ['points']];
$this->params['breadcrumbs'][] = $this->title
?>
<div class="point-update">

    <?= $this->render('_form-points', [
        'model' => $model,
    ]) ?>

</div>
