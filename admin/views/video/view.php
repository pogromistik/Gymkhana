<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Video */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Видеогалерея', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
	</p>

	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'title',
			[
				'attribute' => 'typeId',
				'format'    => 'raw',
				'value'     => $model->type->title
			],
			'description',
			[
				'attribute' => 'link',
				'format'    => 'raw',
				'value'     => $model->link
			],
			[
				'attribute' => 'dateAdded',
				'format'    => 'raw',
				'value'     => date('d.m.Y, H:i', $model->dateAdded)
			],
			[
				'attribute' => 'dateUpdated',
				'format'    => 'raw',
				'value'     => date('d.m.Y, H:i', $model->dateUpdated)
			],
		],
	]) ?>

</div>
