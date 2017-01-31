<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Championship;

/* @var $this yii\web\View */
/* @var $model common\models\Championship */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Разделы чемпионатов', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Championship::$groupsTitle[$model->groupId], 'url' => ['index', 'groupId' => $model->groupId]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="championship-view">
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            'description:ntext',
	        [
		        'attribute' => 'yearId',
		        'value' => $model->year->year
	        ],
	        [
		        'attribute' => 'status',
		        'value' => Championship::$statusesTitle[$model->status]
	        ],
	        [
		        'attribute' => 'groupId',
		        'value' => Championship::$groupsTitle[$model->groupId]
	        ],
	        [
		        'attribute' => 'regionGroupId',
		        'value' => $model->regionGroupId ? $model->regionalGroup->title : ''
	        ],
	        [
		        'attribute' => 'dateAdded',
		        'value' => date("d.m.Y, H:i", $model->dateAdded)
	        ],
	        [
		        'attribute' => 'dateUpdated',
		        'value' => date("d.m.Y, H:i", $model->dateUpdated)
	        ],
        ],
    ]) ?>

</div>
