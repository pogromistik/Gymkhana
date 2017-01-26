<?php

/* @var $this yii\web\View */
/* @var $model common\models\AssocNews */
/* @var $success integer */

$this->title = 'Редактирование новости: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<?php if ($success) { ?>
    <div class="alert alert-success">Изменения успешно сохранены</div>
<?php } ?>

<div class="assoc-news-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
