<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SpecialChamp */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Специальные чемпионаты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="special-champ-view">

    <p>
		<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-my-style btn-blue']) ?>
		<?= Html::a('Добавить этап', ['/competitions/special-champ/create-stage', 'championshipId' => $model->id], [
			'class' => 'btn btn-my-style btn-light-green'
		]); ?>
		<?= Html::a('Результаты', ['/competitions/special-champ/results', 'id' => $model->id], [
			'class' => 'btn btn-my-style btn-lilac'
		]); ?>
    </p>
    </p>
	
	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'title',
			[
				'attribute' => 'description',
				'format'    => 'raw'
			],
			[
				'attribute' => 'yearId',
				'value'     => $model->year->year
			],
			[
				'attribute' => 'status',
				'value'     => \common\models\SpecialChamp::$statusesTitle[$model->status]
			],
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
