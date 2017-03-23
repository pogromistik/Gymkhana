<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Athlete */

$this->title = $model->lastName . ' ' . $model->firstName;
$this->params['breadcrumbs'][] = ['label' => 'Спортсмены', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$motorcycles = '';
?>
<?php
if ($motorcyclesModels = $model->getMotorcycles()->andWhere(['status' => \common\models\Motorcycle::STATUS_ACTIVE])->all()) {
	$motorcycles = '<ul>';
	foreach ($motorcyclesModels as $motorcycleItem) {
		$motorcycles .= '<li>' . $motorcycleItem->model . ' ' . $motorcycleItem->mark . '</li>';
	}
	$motorcycles .= '</ul>';
}
?>
<div class="athlete-view">

    <p>
		<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?php if (!$model->hasAccount) { ?>
			<?= Html::a('Создать кабинет', ['create-cabinet', 'id' => $model->id],
				['class' => 'btn btn-default createCabinet', 'data-id' => $model->id]) ?>
		<?php } ?>
    </p>
	
	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'firstName',
			'lastName',
			[
				'attribute' => 'cityId',
				'value'     => $model->city->title
			],
			'phone',
			'email:email',
			[
				'attribute'      => 'login',
				'contentOptions' => ['class' => 'bg-blue'],
                'captionOptions' => ['class' => 'bg-blue']
			],
			[
				'attribute' => 'athleteClassId',
				'value'     => $model->athleteClassId ? $model->athleteClass->title : '',
			],
			'number',
			[
				'attribute' => 'createdAt',
				'value'     => date("d.m.Y, H:i", $model->createdAt)
			],
			[
				'attribute' => 'updatedAt',
				'value'     => date("d.m.Y, H:i", $model->updatedAt)
			],
			[
				'attribute' => 'hasAccount',
				'value'     => $model->hasAccount ? 'Да' : 'Нет'
			],
			[
				'attribute' => 'lastActivityDate',
				'value'     => $model->lastActivityDate ? date("d.m.Y, H:i", $model->lastActivityDate) : ''
			],
			[
				'attribute' => 'motorcycles',
				'label'     => 'Мотоциклы',
				'format'    => 'raw',
				'value'     => $motorcycles
			]
		],
	]) ?>


</div>
