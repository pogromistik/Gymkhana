<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Video */

$this->title = 'Редактировать видео: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Видеогалерея', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="video-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
