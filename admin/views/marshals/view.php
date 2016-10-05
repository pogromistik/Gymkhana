<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Marshal */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Маршалы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="marshal-view">

	<h1><?= Html::encode($this->title) ?></h1>

	<p>
		<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('Удалить', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data'  => [
				'confirm' => 'Вы уверены, что хотите удалить маршала ' . $model->name . '?',
				'method'  => 'post',
			],
		]) ?>
	</p>

	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'id',
			'name',
			'post',
			[
				'attribute' => 'photo',
				'format'    => 'raw',
				'value'     => Html::img(Yii::getAlias('@picturesView') . $model->photo)
			],
			'text1:ntext',
			'text2:ntext',
			'text3:ntext',
			'motorcycle',
			[
				'attribute' => 'motorcyclePhoto',
				'format'    => 'raw',
				'value'     => Html::img(Yii::getAlias('@picturesView') . $model->motorcyclePhoto)
			],
			[
				'attribute' => 'gif',
				'format'    => 'raw',
				'value'     => Html::img(Yii::getAlias('@picturesView') . $model->gif)
			],
			'link',
		],
	]) ?>

</div>
