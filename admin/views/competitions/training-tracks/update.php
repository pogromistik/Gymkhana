<?php

/* @var $this yii\web\View */
/* @var $model common\models\TrainingTrack */

$this->title = 'Редактирование трассы: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Редактирование трассы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="training-track-update">
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
