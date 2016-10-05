<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Regular */

$this->title = 'Добавить правило';
$this->params['breadcrumbs'][] = ['label' => 'О проекте'];
$this->params['breadcrumbs'][] = ['label' => 'Правила', 'url' => ['regular']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regular-create">

	<h1><?= Html::encode($this->title) ?></h1>

	<?= $this->render('_form-regular', [
		'model' => $model,
	]) ?>

</div>
