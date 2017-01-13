<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\MenuItem */

$this->title = 'Добавить пункт меню';
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
