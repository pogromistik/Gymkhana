<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Link */

$this->title = 'Редактировать ссылку: ';
$this->params['breadcrumbs'][] = ['label' => 'Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="link-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
