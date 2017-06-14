<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MoscowPoint */

$this->title = 'Добавить балл';
$this->params['breadcrumbs'][] = ['label' => 'Баллы за чемпионат по Московской схеме', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="moscow-point-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
