<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Album */

$this->title = 'Создание альбома';
$this->params['breadcrumbs'][] = 'Галерея';
$this->params['breadcrumbs'][] = ['label' => 'Альбомы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="album-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
