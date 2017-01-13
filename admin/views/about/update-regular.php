<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Regular */

$this->title = 'Редактировать правило: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'О проекте'];
$this->params['breadcrumbs'][] = ['label' => 'Правила', 'url' => ['regular']];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="regular-update">

	<?= $this->render('_form-regular', [
		'model' => $model,
	]) ?>

</div>
