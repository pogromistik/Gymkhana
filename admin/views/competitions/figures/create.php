<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Figure */

$this->title = 'Добавление фигуры';
$this->params['breadcrumbs'][] = ['label' => 'Фигуры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="figure-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
