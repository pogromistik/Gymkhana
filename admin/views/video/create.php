<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Video */

$this->title = 'Добавить видео';
$this->params['breadcrumbs'][] = ['label' => 'Видеогалерея', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
