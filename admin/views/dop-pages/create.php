<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\DopPage */

$this->title = 'Добавить страницу';
$this->params['breadcrumbs'][] = ['label' => 'Доп страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dop-page-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
