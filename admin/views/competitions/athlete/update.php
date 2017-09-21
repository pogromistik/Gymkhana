<?php

use yii\helpers\Html;
use dosamigos\editable\Editable;

/**
 * @var \yii\web\View              $this
 * @var \common\models\Athlete     $model
 * @var integer                    $success
 * @var \common\models\Motorcycle  $motorcycle
 * @var \admin\models\PasswordForm $password
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
	
	<?php if (!$model->hasAccount) { ?>
		<?= Html::a('Создать кабинет', ['create-cabinet', 'id' => $model->id],
			['class' => 'btn btn-my-style btn-orange createCabinet', 'data-id' => $model->id]) ?>
	<?php } elseif (\Yii::$app->user->can('projectOrganizer')) { ?>
		<?= Html::a('Удалить кабинет', ['delete-cabinet', 'id' => $model->id],
			['class' => 'btn btn-my-style btn-red deleteCabinet', 'data-id' => $model->id]) ?>
	<?php } ?>

    <div class="with-bottom-border">
        <h3>Информация о спортсмене</h3>
		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
    </div>
	
	<?php if (\Yii::$app->user->can('developer')) { ?>
        <div class="with-bottom-border pb-20">
            <h3>Изменение пароля</h3>
			<?php $form = \yii\bootstrap\ActiveForm::begin() ?>
			<?= $form->field($password, 'pass')->passwordInput()->label('Пароль'); ?>
			<?= $form->field($password, 'athleteId')->hiddenInput(['value' => $model->id])->label(false)->error(false); ?>
			<?= $form->field($password, 'pass_repeat')->passwordInput()->label('Подтвердите пароль') ?>
			<?= Html::submitButton('изменить', ['class' => 'btn btn-my-style btn-blue']) ?>
			<?php $form->end() ?>
        </div>
	<?php } ?>

    <h3>Мотоциклы</h3>
    <div id="motorcycles">
		<?= $this->render('_motorcycle-form', [
			'motorcycle' => $motorcycle,
		]) ?>
    </div>
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
						<?= date("d.m.Y, H:i", $motorcycleInfo->dateAdded) ?>
                    </td>
                    <td>
						<?php
						if ($motorcycleInfo->status) {
							echo Html::a('<span class="fa fa-remove"></span>', ['/competitions/motorcycles/change-status', 'id' => $motorcycleInfo->id], [
								'class'   => 'btn btn-my-style btn-red changeMotorcycleStatus',
								'data-id' => $motorcycleInfo->id,
								'title'   => 'Удалить'
							]);
						} else {
							echo Html::a('<span class="fa fa-check"></span>', ['/competitions/motorcycles/change-status', 'id' => $motorcycleInfo->id], [
								'class'   => 'btn btn-my-style btn-boggy changeMotorcycleStatus',
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
