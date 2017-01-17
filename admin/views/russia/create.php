<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\City */

$this->title = 'Добавить город';
$this->params['breadcrumbs'][] = ['label' => 'Россия', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="russia-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
