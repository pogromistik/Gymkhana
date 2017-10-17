<?php

/**
 * @var \yii\web\View               $this
 * @var \common\models\SpecialStage $stage
 * @var int                         $success
 * @var int                         $errorCity
 */

use yii\helpers\Html;

$this->title = 'Редактирование этапа';
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['/competitions/special-champ/index']];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/special-champ/view',
	'id' => $stage->championship->id]];
$this->params['breadcrumbs'][] = ['label' => $stage->title, 'url' => ['/competitions/special-champ/view-stage',
	'id' => $stage->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stage-create">

    <p>
		<?= Html::a('Участники', ['/competitions/special-champ/participants', 'stageId' => $stage->id],
			['class' => 'btn btn-my-style btn-light-aquamarine']) ?>
		<?= Html::a('Пересчитать результаты', ['/competitions/special-champ/calculation-result', 'stageId' => $stage->id],
			['class' => 'btn btn-my-style btn-yellow']) ?>
		<?= Html::a('Итоги', ['/competitions/special-champ/stage-results', 'stageId' => $stage->id],
			['class' => 'btn btn-my-style btn-lilac']) ?>
    </p>
	
	<?= $this->render('_stage-form', [
		'model' => $stage,
	]) ?>

</div>
