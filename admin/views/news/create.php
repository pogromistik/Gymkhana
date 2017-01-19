<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = 'Добавить новость';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-info">
    <b>На этой странице указывается только краткое описание и маленькая (квадратная) фотография. После создания новости можно будет заполнить остальную информацию.</b>
</div>

<div class="news-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
