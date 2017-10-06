<?php
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use common\models\Country;
use yii\helpers\Url;
use yii\web\JsExpression;
use karpoff\icrop\CropImageUpload;

/**
 * @var \common\models\Athlete     $athlete
 * @var string                     $success
 * @var \champ\models\PasswordForm $password
 */
?>
<h2>Редактирование профиля</h2>
<div class="row">
    <div class="col-bg-7 col-lg-9 col-md-10 col-sm-10-col-xs-12">
		<?php if ($success) { ?>
            <div class="alert alert-success">Изменения успешно сохранены</div>
		<?php } ?>


        <h3>Изменение пароля</h3>
		<?php $form = ActiveForm::begin() ?>
		<?= $form->field($password, 'pass')->passwordInput()->label('Пароль'); ?>
		<?= $form->field($password, 'pass_repeat')->passwordInput()->label('Подтвердите пароль') ?>
		<?= Html::submitButton('изменить', ['class' => 'btn btn-success']) ?>
		<?php $form->end() ?>
        
        <div class="hr-motorcycle hr-motorcycle-8"></div>
        
        <h3>Изменение фотографии</h3>
        <div class="help-for-athlete">
            <small>Размер загружаемого изображения не должен превышать 300КБ. Допустимые форматы: png, jpg.
                Необходимые пропорции: 3x4 (300x400 pixels)
            </small>
        </div>
		<div class="pt-10">
			<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
			<?php if ($athlete->photo) { ?>
                <div class="row">
                    <div class="col-md-2 col-sm-4 img-in-profile">
						<?= Html::img(\Yii::getAlias('@filesView') . $athlete->photo) ?>
                        <br>
                        <a href="#" class="btn btn-default btn-block deletePhoto">удалить</a>
                        <br>
                    </div>
                    <div class="col-md-10 col-sm-8">
						<?= $form->field($athlete, 'photo')->widget(\sadovojav\cutter\Cutter::className(), [
							'cropperOptions'        => [
								'aspectRatio' => 3 / 4,
							],
							'defaultCropperOptions' => [
								'rotatable' => false,
								'zoomable'  => false,
								'movable'   => false,
							]
						]) ?>
                    </div>
                </div>
			<?php } else { ?>
				<?= $form->field($athlete, 'photo')->widget(\sadovojav\cutter\Cutter::className(), [
					'cropperOptions'        => [
						'aspectRatio' => 3 / 4
					],
					'defaultCropperOptions' => [
						'rotatable' => false,
						'zoomable'  => false,
						'movable'   => false,
					]
				]) ?>
			<?php } ?>
            <div class="form-group">
				<?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>
            </div>
			<?php ActiveForm::end(); ?>
        </div>

        <div class="hr-motorcycle hr-motorcycle-6"></div>
        
        <h3><?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id], ['target' => '_blank']) ?></h3>
        <div class="athlete-form">
			<?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'options' => ['id' => 'updateAthlete', 'enctype' => 'multipart/form-data']]); ?>

            <div class="help-for-athlete">
                <small>Информация, обязательная для заполнения:</small>
            </div>
			
			<?= $form->field($athlete, 'countryId')->widget(Select2::classname(), [
				'data'    => Country::getAll(true),
				'options' => [
					'placeholder' => 'Выберите страну...',
					'id'          => 'country-id',
				],
			]); ?>
			
			<?php $cities = [];
			if ($athlete->cityId) {
				$cities = [$athlete->cityId => $athlete->city->title];
				if ($athlete->countryId !== null) {
					$cities = ArrayHelper::map(\common\models\City::find()->where(['countryId' => $athlete->countryId])
						->andWhere(['!=', 'id', $athlete->cityId])
						->orderBy(['title' => SORT_ASC])->limit(50)->all(),
						'id', 'title');
					$cities[$athlete->cityId] = $athlete->city->title;
				}
			}
			?>
			<?php $url = \yii\helpers\Url::to(['/help/city-list']); ?>
			<?= $form->field($athlete, 'cityId')->widget(DepDrop::classname(), [
				'data'           => $cities,
				'options'        => ['placeholder' => 'Выберите город ...'],
				'type'           => DepDrop::TYPE_SELECT2,
				'select2Options' => [
					'pluginOptions' => [
						'allowClear'         => true,
						'minimumInputLength' => 3,
						'language'           => [
							'errorLoading' => new JsExpression("function () { return 'Поиск результатов...'; }"),
						],
						'ajax'               => [
							'url'      => $url,
							'dataType' => 'json',
							'data'     => new JsExpression('function(params) { return {title:params.term, countryId:$("#country-id").val()}; }')
						],
						'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
						'templateResult'     => new JsExpression('function(city) { return city.text; }'),
						'templateSelection'  => new JsExpression('function (city) { return city.text; }'),
					],
				],
				'pluginOptions'  => [
					'depends'     => ['country-id'],
					'url'         => Url::to(['/help/country-category', 'type' => \champ\controllers\HelpController::TYPE_CITY]),
					'loadingText' => 'Для выбранной страны нет городов...',
					'placeholder' => 'Выберите город...',
				]
			]); ?>

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
                <small>Вы можете указать свой персональный номер. Под этим номером вы будете выступать на всех
                    чемпионатах
                    своей
                    области.
                    В одной области не может быть несколько участников с одним номером.
                </small>
            </div>
			<?= $form->field($athlete, 'number')->textInput() ?>

            <div class="form-group complete">
				<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            </div>
			
			<?php ActiveForm::end(); ?>

        </div>

        <div class="hr-motorcycle hr-motorcycle-7"></div>
        
        <h3>Мотоциклы</h3>
        <div class="help-for-athlete">
            <small>
                Вы можете добавить ещё один мотоцикл или удалить (заблокировать) старый (при необходимости его можно
                будет
                вернуть). При
                удалении мотоцикла все результаты, показынные на нём, сохраняются, но возможность зарегистрироваться на
                нём
                на этап
                исчезает.<br>
                При необходимости внести изменения в созданный мотоцикл (напр. при опечатке или если перепутаны местами
                марка и модель),
                пожалуйста,
                <a href="#" data-toggle="modal" data-target="#feedbackForm">свяжитесь с администрацией</a>.
            </small>
        </div>
		<?= $this->render('_motorcycle-form', ['athlete' => $athlete]) ?>
		<?php if ($motorcycles = $athlete->motorcycles) { ?>
            <table class="table">
                <thead>
                <tr>
                    <th>Марка и модель</th>
                    <th class="show-pk">Статус</th>
                    <th class="show-pk">Добавлен</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
				<?php foreach ($motorcycles as $motorcycleInfo) { ?>
                    <tr>
                        <td><?= $motorcycleInfo->mark ?> <?= $motorcycleInfo->model ?></td>
                        <td class="show-pk">
							<?= \common\models\Motorcycle::$statusesTitle[$motorcycleInfo->status] ?>
                        </td>
                        <td class="show-pk">
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
    </div>
</div>
        