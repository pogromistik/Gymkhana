<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Motorcycle */

$this->title = 'Редактирование мотоцикла: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Мотоциклы', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="motorcycle-update">
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>