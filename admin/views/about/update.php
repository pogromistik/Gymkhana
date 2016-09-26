<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AboutBlock */

$this->title = 'Редактирование блока "о проекте"';
$this->params['breadcrumbs'][] = ['label' => 'О проекте', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="about-block-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
