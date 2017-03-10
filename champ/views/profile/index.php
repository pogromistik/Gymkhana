<?php
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\bootstrap\Html;

/**
 * @var \common\models\Athlete $athlete
 * @var string                 $success
 */
?>

<?php if ($success) { ?>
    <div class="alert alert-success">Изменения успешно сохранены</div>
<?php } ?>

    <div class="athlete-form">
		<?php $form = ActiveForm::begin(['options' => ['id' => 'updateAthlete']]); ?>

        <div class="help-for-athlete">
            <small>Информация, обязательная для заполнения:</small>
        </div>
		
		<?= $form->field($athlete, 'cityId')->widget(Select2::classname(), [
			'name'    => 'kv-type-01',
			'data'    => \common\models\City::getAll(true),
			'options' => [
				'placeholder' => 'Выберите город...',
			],
		]) ?>

        <div class="row">
            <div class="col-md-6 col-sm-12">
				<?= $form->field($athlete, 'lastName')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6 col-sm-12">
				<?= $form->field($athlete, 'firstName')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="help-for-athlete">
            <small>Информация, не обязательная для заполнения. Настоятельно рекомендуем заполнить хотя бы одно поле.
                Ваши
                контакты будут видны
                только уполномоченным людям.
            </small>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
				<?= $form->field($athlete, 'phone')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6 col-sm-12">
				<?= $form->field($athlete, 'email')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="help-for-athlete">
            <small>Вы можете указать свой персональный номер. Под этим номером вы будете выступать на всех чемпионатах
                своей
                области.
                В одной области не может быть несколько участников с одним номером.
            </small>
        </div>
		<?= $form->field($athlete, 'number')->textInput() ?>

        <div class="form-group complete">
			<?= Html::submitButton('Сохранить', ['class' => 'btn btn-dark']) ?>
        </div>
		
		<?php ActiveForm::end(); ?>

    </div>

    <h3>Мотоциклы</h3>
    <div class="help-for-athlete">
        <small>
            Вы можете добавить ещё один мотоцикл или удалить (заблокировать) старый (при необходимости его можно будет вернуть). При
            удалении мотоцикла все результаты, показынные на нём, сохраняются, но возможность зарегистрироваться на нём на этап
            исчезает.<br>
            При необходимости внести изменения в созданный мотоцикл (напр. при опечатке или если перепутаны местами марка и модель),
            пожалуйста,
            <a href="#" data-toggle="modal" data-target="#feedbackForm">свяжитесь с администрацией:</a>
            <b><a href="#" class="btn btn-dark" data-toggle="modal" data-target="#feedbackForm">написать</a></b>
        </small>
    </div>
<?php if ($motorcycles = $athlete->motorcycles) { ?>
	<?= $this->render('_motorcycle-form', ['athlete' => $athlete]) ?>
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
                <td><?= $motorcycleInfo->mark ?></td>
                <td><?= $motorcycleInfo->model ?></td>
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
							'data-id' => $motorcycleInfo->id,
							'title'   => 'Удалить'
						]);
					} else {
						echo Html::a('<span class="fa fa-check"></span>', ['/competitions/motorcycles/change-status',
							'id' => $motorcycleInfo->id], [
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