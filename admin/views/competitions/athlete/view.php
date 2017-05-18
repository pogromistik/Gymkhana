<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use dosamigos\editable\Editable;

/* @var $this yii\web\View */
/* @var $model common\models\Athlete */
/* @var $motorcycle \common\models\Motorcycle */

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
		<?php if (\common\helpers\UserHelper::accessAverage($model->regionId, $model->creatorUserId)) { ?>
			<?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?php } ?>
		<?php if (!$model->hasAccount) { ?>
			<?= Html::a('Создать кабинет', ['create-cabinet', 'id' => $model->id],
				['class' => 'btn btn-default createCabinet', 'data-id' => $model->id]) ?>
		<?php } elseif (\Yii::$app->user->can('projectOrganizer')) { ?>
			<?= Html::a('Удалить кабинет', ['delete-cabinet', 'id' => $model->id],
				['class' => 'btn btn-danger deleteCabinet', 'data-id' => $model->id]) ?>
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


<h3>Мотоциклы</h3>
<?= $this->render('_motorcycle-form', [
	'motorcycle' => $motorcycle,
]) ?>
<?php if ($motorcycles = $model->motorcycles) { ?>
    <table class="table">
        <thead>
        <tr>
            <th>Марка</th>
            <th>Модель</th>
            <th>Статус</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($motorcycles as $motorcycleInfo) { ?>
			<?php if (\common\helpers\UserHelper::accessAverage($model->regionId, $motorcycleInfo->creatorUserId)) { ?>
                <tr>
                    <td>
						<?= Editable::widget([
							'name'          => 'mark',
							'value'         => $motorcycleInfo->mark,
							'url'           => 'update-motorcycle',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $motorcycleInfo->id,
								'value'     => $motorcycleInfo->mark,
								'placement' => 'right',
							]
						]); ?>
                    </td>
                    <td>
						<?= Editable::widget([
							'name'          => 'model',
							'value'         => $motorcycleInfo->model,
							'url'           => 'update-motorcycle',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $motorcycleInfo->id,
								'value'     => $motorcycleInfo->model,
								'placement' => 'right',
							]
						]); ?>
                    </td>
                    <td>
						<?= \common\models\Motorcycle::$statusesTitle[$motorcycleInfo->status] ?>
                    </td>
                    <td>
						<?php
						if ($motorcycleInfo->status) {
							echo Html::a('<span class="fa fa-remove"></span>', ['/competitions/motorcycles/change-status', 'id' => $motorcycleInfo->id], [
								'class'   => 'btn btn-danger changeMotorcycleStatus',
								'data-id' => $motorcycleInfo->id,
								'title'   => 'Удалить'
							]);
						} else {
							echo Html::a('<span class="fa fa-check"></span>', ['/competitions/motorcycles/change-status', 'id' => $motorcycleInfo->id], [
								'class'   => 'btn btn-warning changeMotorcycleStatus',
								'data-id' => $motorcycleInfo->id,
								'title'   => 'Вернуть в работу'
							]);
						}
						?>
                    </td>
                </tr>
			<?php } else { ?>
                <tr>
                    <td>
						<?= $motorcycleInfo->mark ?>
                    </td>
                    <td>
						<?= $motorcycleInfo->model ?>
                    </td>
                    <td>
						<?= \common\models\Motorcycle::$statusesTitle[$motorcycleInfo->status] ?>
                    </td>
                    <td>
						<?php
						if ($motorcycleInfo->status) {
						} else {
							echo Html::a('<span class="fa fa-check"></span>', ['/competitions/motorcycles/change-status', 'id' => $motorcycleInfo->id], [
								'class'   => 'btn btn-warning changeMotorcycleStatus',
								'data-id' => $motorcycleInfo->id,
								'title'   => 'Вернуть в работу'
							]);
						}
						?>
                    </td>
                </tr>
			<?php } ?>
		<?php } ?>
        </tbody>
    </table>
<?php } ?>
