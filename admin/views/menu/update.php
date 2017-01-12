<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\MenuItem */
/* @var $success integer */

$this->title = 'Редактировать пункт: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Меню', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="menu-item-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?php if ($success) { ?>
        <div class="alert alert-success">Изменения успешно сохранены</div>
    <?php } ?>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
