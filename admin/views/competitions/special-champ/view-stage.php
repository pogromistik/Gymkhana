<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Championship;
use common\models\Stage;

/* @var $this yii\web\View */
/* @var $stage common\models\SpecialStage */

$this->title = $stage->title;
$championship = $stage->championship;
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['/competitions/special-champ/index']];
$this->params['breadcrumbs'][] = ['label' => $stage->championship->title, 'url' => ['/competitions/special-champ/view', 'id' => $stage->championshipId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stage-view">
	
	<div class="buttons">
		<?= Html::a('Редактировать', ['update-stage', 'id' => $stage->id], ['class' => 'btn btn-blue btn-my-style']) ?>
		<?= Html::a('Пересчитать результаты', ['/competitions/special-champ/calculation-result', 'stageId' => $stage->id],
			[
				'class'   => 'btn btn-my-style btn-yellow',
			]) ?>
		<?= Html::a('Итоги', ['/competitions/special-champ/stage-results', 'stageId' => $stage->id],
			['class' => 'btn btn-my-style btn-lilac']) ?>
	</div>
	
	<?= DetailView::widget([
		'model'      => $stage,
		'attributes' => [
			[
				'attribute' => 'championshipId',
				'value'     => $stage->championship->title
			],
			'title',
			[
				'attribute' => 'description',
				'format'    => 'raw'
			],
			[
				'attribute' => 'dateAdded',
				'value'     => date("d.m.Y, H:i", $stage->dateAdded)
			],
			[
				'attribute' => 'dateStart',
				'value'     => $stage->dateStart ? $stage->dateStartHuman : null
			],
			[
				'attribute' => 'dateEnd',
				'value'     => $stage->dateEnd ? $stage->dateEndHuman : null
			],
			[
				'attribute' => 'dateResult',
				'value'     => $stage->dateResult ? $stage->dateResultHuman : null
			],
			[
				'attribute' => 'status',
				'value'     => \common\models\Stage::$statusesTitle[$stage->status]
			],
			[
				'attribute' => 'classId',
				'value'     => $stage->classId ? $stage->class->title : null
			],
			[
				'attribute' => 'photoPath',
				'format'    => 'raw',
				'value'     => $stage->photoPath ? Html::img(\Yii::getAlias('@filesView') . '/' . $stage->photoPath) : null
			],
			[
				'attribute' => 'outOfCompetitions',
				'label'     => 'Провести этап вне общего зачёта?',
				'value'     => $stage->outOfCompetitions ? 'да' : 'нет'
			],
			'referenceTimeHuman'
		],
	]) ?>

</div>
