<?php


/* @var $this yii\web\View */
/* @var $model common\models\Notice */

$this->title = 'Отправить уведомление';
$this->params['breadcrumbs'][] = ['label' => 'Уведомления', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
