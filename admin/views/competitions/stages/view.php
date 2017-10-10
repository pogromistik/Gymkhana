<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Championship;
use common\models\Stage;

/* @var $this yii\web\View */
/* @var $model common\models\Stage */

$this->title = $model->title;
$championship = $model->championship;
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$championship->groupId], 'url' => ['/competitions/championships/index', 'groupId' => $championship->groupId]];
$this->params['breadcrumbs'][] = ['label' => $model->championship->title, 'url' => ['/competitions/championships/view', 'id' => $model->championshipId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="stage-view">

    <div class="buttons">
		<?php if (\Yii::$app->user->can('projectAdmin')) { ?>
			<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-blue btn-my-style']) ?>
		<?php } ?>
		<?= $this->render('_buttons', ['model' => $model, 'championship' => $championship]) ?>
    </div>
	
	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			[
				'attribute' => 'championshipId',
				'value'     => $model->championship->title
			],
			'title',
			'location',
			[
				'attribute' => 'cityId',
				'value'     => $model->city->title
			],
			[
				'attribute' => 'description',
				'format'    => 'raw'
			],
			'countRace',
			[
				'attribute' => 'dateAdded',
				'value'     => date("d.m.Y, H:i", $model->dateAdded)
			],
			[
				'attribute' => 'dateOfThe',
				'value'     => $model->dateOfThe ? $model->dateOfTheHuman : null
			],
			[
				'attribute' => 'startRegistration',
				'value'     => $model->startRegistration ? $model->startRegistrationHuman : null
			],
			[
				'attribute' => 'endRegistration',
				'value'     => $model->endRegistration ? $model->endRegistrationHuman : null
			],
			[
				'attribute' => 'status',
				'value'     => \common\models\Stage::$statusesTitle[$model->status]
			],
			[
				'attribute' => 'class',
				'value'     => $model->class ? $model->classModel->title : null
			],
			[
				'attribute' => 'trackPhoto',
				'format'    => 'raw',
				'value'     => $model->trackPhoto ? Html::img(\Yii::getAlias('@filesView') . '/' . $model->trackPhoto) : null
			],
			[
				'attribute' => 'trackPhotoStatus',
				'label'     => 'Статус публикации фото',
				'value'     => $model->trackPhotoStatus ? 'опубликовано' : 'не опубликовано'
			],
			'referenceTimeHuman',
			'participantsLimit',
			'fastenClassFor'
		],
	]) ?>

</div>
