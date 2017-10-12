<?php

/**
 * @var \yii\web\View               $this
 * @var \common\models\SpecialStage $stage
 * @var \common\models\SpecialChamp $championship
 * @var int                         $success
 * @var int                         $errorCity
 */

$this->title = 'Создание нового этапа';
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['/competitions/special-champ/index']];
$this->params['breadcrumbs'][] = ['label' => $championship->title, 'url' => ['/competitions/special-champ/view', 'id' => $championship->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stage-create">

	<?= $this->render('_stage-form', [
		'model' => $stage,
	]) ?>

</div>
