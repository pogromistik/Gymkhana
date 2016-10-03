<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Regular */

$this->title = 'Редактировать правило: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Правила', 'url' => ['regular']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="regular-update">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form-regular', [
		'model' => $model,
	]) ?>

</div>
