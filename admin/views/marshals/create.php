<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Marshal */

$this->title = 'Добавить маршала';
$this->params['breadcrumbs'][] = ['label' => 'О проекте'];
$this->params['breadcrumbs'][] = ['label' => 'Маршалы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marshal-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
