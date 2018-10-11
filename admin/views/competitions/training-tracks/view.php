<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TrainingTrack */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Тренировочные трассы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="training-track-view">

    <p>
		<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
	
	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'title',
			'description:ntext',
			[
				'attribute' => 'imgPath',
				'format'    => 'raw',
				'value'     => Html::img(\Yii::getAlias('@filesView') . '/' . $model->imgPath)
			],
			[
				'attribute' => 'status',
				'value'     => \common\models\TrainingTrack::$statusTitles[$model->status]
			],
			'minWidth',
			'minHeight',
			[
				'attribute' => 'level',
				'value'     => \common\models\TrainingTrack::$levelTitles[$model->level]
			],
			'conesCount',
			[
				'attribute' => 'dateAdded',
				'value'     => date("d.m.Y, H:i", $model->dateAdded)
			],
			[
				'attribute' => 'dateUpdated',
				'value'     => date("d.m.Y, H:i", $model->dateUpdated)
			],
		],
	]) ?>

</div>
