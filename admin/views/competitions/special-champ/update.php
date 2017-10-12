<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SpecialChamp */

$this->title = 'Редактирование чемпионата: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="special-champ-update">
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
