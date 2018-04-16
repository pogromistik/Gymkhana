<?php

/* @var $this yii\web\View */
/* @var $model common\models\SpecialChamp */

$this->title = 'Создать чемпионат';
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="special-champ-create">
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
