<?php

/**
 * @var \yii\web\View          $this
 * @var \common\models\Athlete $model
 * @var int                    $success
 * @var int                    $errorCity
 */

$this->title = 'Добавить спортсмена';
$this->params['breadcrumbs'][] = ['label' => 'Спортсмены', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="athlete-create">
	
	<?= $this->render('//competitions/common/_city-form', [
		'errorCity'  => $errorCity,
		'success'    => $success,
		'url'        => '/competitions/athlete/create',
		'actionType' => 'withoutId'
	]) ?>

    <hr>

    <h3>Добавить спортсмена</h3>
    <div class="alert alert-info">Рекомендуем сначала проверить, есть ли необходимый город в списке</div>
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
