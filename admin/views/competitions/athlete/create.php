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

    <h3>Добавить спортсмена</h3>
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>
