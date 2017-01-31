<?php

use yii\helpers\Html;
use dosamigos\editable\Editable;

/**
 * @var \yii\web\View             $this
 * @var \common\models\Athlete    $model
 * @var integer                   $success
 * @var \common\models\Motorcycle $motorcycle
 */

$this->title = 'Редактирование спортсмена: ' . $model->lastName . ' ' . $model->firstName;
$this->params['breadcrumbs'][] = ['label' => 'Спортсмены', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lastName . ' ' . $model->firstName, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="athlete-update">
	
	<?php if ($success) { ?>
        <div class="alert alert-success">Изменения успешно сохранены</div>
	<?php } ?>

    <h3>Информация о спортсмене</h3>
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

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
                <th>Добавлен</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($motorcycles as $motorcycleInfo) { ?>
                <tr>
                    <td>
						<?= Editable::widget([
							'name'          => 'model',
							'value'         => $motorcycleInfo->model,
							'url'           => 'update-motorcycle',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $motorcycleInfo->model,
								'value'     => $motorcycleInfo->model,
								'placement' => 'right',
							]
						]); ?>
                    </td>
                    <td>
						<?= Editable::widget([
							'name'          => 'mark',
							'value'         => $motorcycleInfo->mark,
							'url'           => 'update-motorcycle',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $motorcycleInfo->mark,
								'value'     => $motorcycleInfo->mark,
								'placement' => 'right',
							]
						]); ?>
                    </td>
                    <td>
						<?= \common\models\Motorcycle::$statusesTitle[$motorcycleInfo->status] ?>
                    </td>
                    <td>
						<?= date("d.m.Y, H:i", $motorcycleInfo->dateAdded) ?>
                    </td>
                    <td>
						<?php
						if ($motorcycleInfo->status) {
							echo Html::a('<span class="fa fa-remove"></span>', ['/competitions/motorcycles/change-status', 'id' => $motorcycleInfo->id], [
								'class'   => 'btn btn-danger changeMotorcycleStatus',
								'data'    => [
									'confirm' => 'Уверены, что хотите заблокировать этот мотоцикл?',
									'method'  => 'post',
								],
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
			<?php } ?>
            </tbody>
        </table>
	<?php } ?>
</div>
