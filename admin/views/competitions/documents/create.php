<?php

/* @var $this yii\web\View */
/* @var $model common\models\AssocNews */

$this->title = 'Создание раздела';
$this->params['breadcrumbs'][] = ['label' => 'Документы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="assoc-news-create">
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
