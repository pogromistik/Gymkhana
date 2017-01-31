<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Athlete */
/**
 * @var \yii\base\View         $this
 * @var \common\models\Athlete $model
 * @var int                    $success
 * @var int                    $errorCity
 */

$this->title = 'Добавить спортсмена';
$this->params['breadcrumbs'][] = ['label' => 'Спортсмены', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="athlete-create">
	
	<?= $this->render('_city-form') ?>
	
	<?php if ($success) { ?>
        <br>
        <div class="alert alert-success">Город добавлен</div>
	<?php } ?>
	
	<?php if ($errorCity) { ?>
        <br>
        <div class="alert alert-warning">Город уже существует</div>
	<?php } ?>

    <hr>

    <h3>Добавить спортсмена</h3>
    <div class="alert alert-info">Рекомендуем сначала проверить, есть ли необходимый город в списке</div>
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
