<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Link */

$this->title = 'Добавление ссылки';
$this->params['breadcrumbs'][] = ['label' => 'Links', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="link-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
