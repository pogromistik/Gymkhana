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
	
	<?php if (\common\helpers\UserHelper::accessAverage($model->regionId, $model->creatorUserId)) { ?>
		<?php if (!$model->hasAccount) { ?>
			<?= Html::a('Создать кабинет', ['create-cabinet', 'id' => $model->id],
				['class' => 'btn btn-my-style btn-orange createCabinet', 'data-id' => $model->id]) ?>
		<?php } elseif (\Yii::$app->user->can('projectOrganizer')) { ?>
			<?= Html::a('Удалить кабинет', ['delete-cabinet', 'id' => $model->id],
				['class' => 'btn btn-my-style btn-red deleteCabinet', 'data-id' => $model->id]) ?>
		<?php } ?>
	<?php } ?>

    <div class="with-bottom-border">
        <h3>Информация о спортсмене</h3>
		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
    </div>
	
	<?php if (\Yii::$app->user->can('developer')) { ?>
        <div class="pt-10">
            <div class="alert help-alert alert-info">
                <div class="text-right">
                    <span class="fa fa-remove closeHintBtn"></span>
                </div>
                Пароль должен содержать как минимум 6 символов. Желательно, чтобы он не был слишком простым.
                Можно воспользоваться онлайн генератором, к примеру
                <a href="http://www.onlinepasswordgenerator.ru/" target="_blank">www.onlinepasswordgenerator.ru</a>
            </div>
        </div>

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
    <div class="pt-10">
        <div class="alert help-alert alert-info">
            <div class="text-right">
                <span class="fa fa-remove closeHintBtn"></span>
            </div>
            Постарайтесь не перепутать марку и модель :)<br>
            Параметры для уже созданных мотоциклов можно редактировать - для этого необходимо нажать на нужное поле.
            Если ничего не происходит - значит, у вас недостаточно прав для совершения этого действия. Для решения
            проблемы вы можете обратиться
            к организатору своего региона или напрямую к <a href="https://vk.com/id19792817" target="_blank">разработчику</a>.<br>
            Красная кнопка - "удаление" мотоцикла. Фактически, при этом он лишь блокируется и в любой момент его можно
            вернуть обратно
            (кнопка удаления сменится на кнопку возврата).
        </div>
    </div>
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
                <th>Объём</th>
                <th>Мощность</th>
                <th>Круизёр</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
			<?php foreach ($motorcycles as $motorcycleInfo) {
				?>
                <tr class="is-active-<?= $motorcycleInfo->status ?>">
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
						<?= Editable::widget([
							'name'          => 'cbm',
							'value'         => $motorcycleInfo->cbm,
							'url'           => 'update-motorcycle',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $motorcycleInfo->id,
								'value'     => $motorcycleInfo->cbm,
								'placement' => 'right',
							]
						]); ?>
                    </td>
                    <td>
						<?= Editable::widget([
							'name'          => 'power',
							'value'         => $motorcycleInfo->power,
							'url'           => 'update-motorcycle',
							'type'          => 'text',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $motorcycleInfo->id,
								'value'     => $motorcycleInfo->power,
								'placement' => 'right',
							]
						]); ?>
                    </td>
                    <td>
						<?= Editable::widget([
							'name'          => 'isCruiser',
							'value'         => $motorcycleInfo->isCruiser ? 'Да' : 'Нет',
							'url'           => 'update-motorcycle',
							'type'          => 'select',
							'mode'          => 'inline',
							'clientOptions' => [
								'pk'        => $motorcycleInfo->id,
								'value'     => $motorcycleInfo->isCruiser,
								'placement' => 'right',
								'select'    => [
									'width' => '124px'
								],
								'source'    => [
									2 => 'Нет',
									1 => 'Да'
								],
							]
						]); ?>
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
	
	<?= $this->render('_img_edit', ['athlete' => $model]) ?>
</div>
