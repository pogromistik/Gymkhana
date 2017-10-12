<?php

/**
 * @var \yii\web\View               $this
 * @var \common\models\SpecialStage $stage
 * @var int                         $success
 * @var int                         $errorCity
 */

$this->title = 'Редактирование этапа';
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['/competitions/special-champ/index']];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/special-champ/view',
	'id' => $stage->championship->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stage-create">
	
	<?= $this->render('_stage-form', [
		'model' => $stage,
	]) ?>

</div>
