<?php

/* @var $this yii\web\View */
/* @var $model common\models\Interview */

$this->title = 'Редактирование опроса: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Опросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="interview-update">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
