<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Track */
/* @var $success integer */

$this->title = $model->isNewRecord ? 'Добавить трассы' : 'Редактировать трассы: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Трассы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if ($success) { ?>
    <div class="alert alert-success">Изменения успешно сохранены</div>
<?php } ?>

<div class="track-create">
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
