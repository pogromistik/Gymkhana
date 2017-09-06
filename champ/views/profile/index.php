<?php
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use common\models\Country;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var \common\models\Athlete     $athlete
 * @var string                     $success
 * @var \champ\models\PasswordForm $password
 */
?>

<h2><?= \Yii::t('app', 'Редактирование профиля') ?></h2>
<div class="row">
    <div class="col-bg-7 col-lg-9 col-md-10 col-sm-10-col-xs-12">
		<?php if ($success) { ?>
            <div class="alert alert-success"><?= \Yii::t('app', 'Изменения успешно сохранены') ?></div>
		<?php } ?>

        <h3><?= \Yii::t('app', 'Изменение пароля') ?></h3>
		<?php $form = ActiveForm::begin() ?>
		<?= $form->field($password, 'pass')->passwordInput()->label(\Yii::t('app', 'Пароль')); ?>
		<?= $form->field($password, 'pass_repeat')->passwordInput()->label(\Yii::t('app', 'Подтвердите пароль')) ?>
		<?= Html::submitButton(\Yii::t('app', 'изменить'), ['class' => 'btn btn-success']) ?>
		<?php $form->end() ?>


        <h3><?= \yii\bootstrap\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id], ['target' => '_blank']) ?></h3>
        <div class="athlete-form">
			<?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'options' => ['id' => 'updateAthlete', 'enctype' => 'multipart/form-data']]); ?>

            <div class="help-for-athlete">
                <small>
					<?= \Yii::t('app', 'Размер загружаемого изображения не должен превышать 300КБ. Допустимые форматы: png, jpg. Необходимые пропорции: 3x4 (300x400 pixels)') ?>
                </small>
            </div>
			<?php if ($athlete->photo) { ?>
                <div class="row">
                    <div class="col-md-2 col-sm-4 img-in-profile">
						<?= Html::img(\Yii::getAlias('@filesView') . $athlete->photo) ?>
                        <br>
                        <a href="#" class="btn btn-default btn-block deletePhoto"><?= \Yii::t('app', 'удалить') ?></a>
                        <br>
                    </div>
                    <div class="col-md-10 col-sm-8">
						<?= $form->field($athlete, 'photoFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
                    </div>
                </div>
			<?php } else { ?>
				<?= $form->field($athlete, 'photoFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
			<?php } ?>

            <div class="help-for-athlete">
                <small><?= \Yii::t('app', 'Информация, обязательная для заполнения:') ?></small>
            </div>
			
			<?= $form->field($athlete, 'countryId')->widget(Select2::classname(), [
				'data'    => Country::getAll(true),
				'options' => [
					'placeholder' => \Yii::t('app', 'Выберите страну') . '...',
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
				'options'        => ['placeholder' => \Yii::t('app', 'Выберите город') . '...'],
				'type'           => DepDrop::TYPE_SELECT2,
				'select2Options' => [
					'pluginOptions' => [
						'allowClear'         => true,
						'minimumInputLength' => 3,
						'language'           => [
							'errorLoading' => new JsExpression("function () { return '" . \Yii::t('app', 'Поиск результатов') . "...'; }"),
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
					'loadingText' => \Yii::t('app', 'Для выбранной страны не найдено городов') . '...',
					'placeholder' => \Yii::t('app', 'Выберите город') . '...',
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
                <small>
					<?= \Yii::t('app', 'Информация, не обязательная для заполнения. Настоятельно рекомендуем заполнить хотя бы одно поле. Ваши контакты будут видны только уполномоченным людям.') ?>
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
                <small>
					<?= \Yii::t('app', 'Вы можете указать свой персональный номер. Под этим номером вы будете выступать на всех чемпионатах своего региона. В одном регионе не может быть несколько участников с одним номером.') ?>
                </small>
            </div>
			<?= $form->field($athlete, 'number')->textInput() ?>

            <div class="form-group complete">
				<?= Html::submitButton(\Yii::t('app', 'Сохранить'), ['class' => 'btn btn-primary']) ?>
            </div>
			
			<?php ActiveForm::end(); ?>

        </div>

        <h3><?= \Yii::t('app', 'Мотоциклы') ?></h3>
        <div class="help-for-athlete">
            <small>
				<?= \Yii::t('app', 'Вы можете добавить ещё один мотоцикл или удалить (заблокировать) старый (при необходимости его можно будет вернуть). При удалении мотоцикла все результаты, показынные на нём, сохраняются, но возможность зарегистрироваться на нём на этап исчезает.') ?>
                <br>
				<?= \Yii::t('app', 'При необходимости внести изменения в созданный мотоцикл (напр. при опечатке или если перепутаны местами марка и модель), пожалуйста, {text}',
					['text' => '<a href="#" data-toggle="modal" data-target="#feedbackForm">' . \Yii::t('app', 'свяжитесь с администрацией') . '</a>']) ?>
                .
            </small>
        </div>
		<?= $this->render('_motorcycle-form', ['athlete' => $athlete]) ?>
		<?php if ($motorcycles = $athlete->motorcycles) { ?>
            <table class="table">
                <thead>
                <tr>
                    <th><?= \Yii::t('app', 'Марка и модель') ?></th>
                    <th class="show-pk"><?= \Yii::t('app', 'Статус') ?></th>
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
                        <td>
							<?php
							if ($motorcycleInfo->status) {
								echo Html::a('<span class="fa fa-remove"></span>', ['/competitions/motorcycles/change-status', 'id' => $motorcycleInfo->id], [
									'class'   => 'btn btn-danger changeMotorcycleStatus',
									'data-id' => $motorcycleInfo->id,
									'title'   => \Yii::t('app', 'Удалить')
								]);
							} else {
								echo Html::a('<span class="fa fa-check"></span>', ['/competitions/motorcycles/change-status',
									'id' => $motorcycleInfo->id], [
									'class'   => 'btn btn-warning changeMotorcycleStatus',
									'data-id' => $motorcycleInfo->id,
									'title'   => \Yii::t('app', 'Вернуть в работу')
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
        