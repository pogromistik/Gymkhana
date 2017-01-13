<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Video */

$this->title = 'Добавить видео';
$this->params['breadcrumbs'][] = ['label' => 'Видеогалерея', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
