<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\CheScheme */

$this->title = 'Добавить класс';
$this->params['breadcrumbs'][] = ['label' => 'Классы награждения: Челябинская схема', 'url' => ['points']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="point-create">
	
	<?= $this->render('_form-class', [
		'model' => $model,
	]) ?>

</div>
