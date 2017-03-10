<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Point */

$this->title = 'Добавить балл';
$this->params['breadcrumbs'][] = ['label' => 'Баллы', 'url' => ['points']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="point-create">

    <?= $this->render('_form-points', [
        'model' => $model,
    ]) ?>

</div>
