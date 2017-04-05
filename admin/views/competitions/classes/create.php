<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AthletesClass */

$this->title = 'Добавить класс спортсменов';
$this->params['breadcrumbs'][] = ['label' => 'Классы спортсменов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="athletes-class-create">
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
