<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AboutBlock */

$this->title = 'Создание блока "о проекте"';
$this->params['breadcrumbs'][] = ['label' => 'О проекте', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="about-block-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
