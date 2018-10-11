<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TrainingTrack */

$this->title = 'Добавление трассы';
$this->params['breadcrumbs'][] = ['label' => 'Тренировочные трассы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-track-create">

	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
