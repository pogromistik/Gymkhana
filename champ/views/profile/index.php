<?php
use dosamigos\editable\Editable;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use common\models\Country;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * @var \common\models\Athlete          $athlete
 * @var string                          $success
 * @var \champ\models\PasswordForm      $password
 * @var \common\models\NewsSubscription $subscription
 */
?>

<h2>Редактирование профиля</h2>

<div class="href-menu">
    <ul>
        <li><a href="#password-link">пароль</a></li>
        <li><a href="#about-me-link">о себе</a></li>
        <li><a href="#motorcycles-link">мотоциклы</a></li>
        <li><a href="#newsletters-link">подписка на новости</a></li>
    </ul>
</div>

<div class="row">
    <div class="col-bg-7 col-lg-9 col-md-10 col-sm-10-col-xs-12">
		<?php if ($success) { ?>
            <div class="alert alert-success">Изменения успешно сохранены</div>
		<?php } ?>


        <div id="password-link">
            <h3>Изменение пароля</h3>
			<?php $form = ActiveForm::begin() ?>
			<?= $form->field($password, 'pass')->passwordInput()->label('Пароль'); ?>
			<?= $form->field($password, 'pass_repeat')->passwordInput()->label('Подтвердите пароль') ?>
			<?= Html::submitButton('изменить', ['class' => 'btn btn-success']) ?>
			<?php $form->end() ?>
        </div>

        <div class="hr-motorcycle hr-motorcycle-8"></div>

        <div id="about-me-link">
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
									'rotatable' => false, 'zoomable' => false,
									'movable'   => false,
								]]) ?>
                        </div>
                    </div>
				<?php } else { ?>
					<?= $form->field($athlete, 'photo')->widget(\sadovojav\cutter\Cutter::className(), [
						'cropperOptions'        => [
							'aspectRatio' => 3 / 4
						],
						'defaultCropperOptions' => [
							'rotatable' => false, 'zoomable' => false,
							'movable'   => false,
						]]) ?>
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
                    <small>Информация, не обязательная для заполнения. Настоятельно рекомендуем заполнить хотя бы одно
                        поле.
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
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Марка и модель</th>
                        <th>Объём</th>
                        <th>Мощность</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php foreach ($motorcycles as $motorcycleInfo) { ?>
                        <tr class="is-active-<?= $motorcycleInfo->status ?>">
                            <td>
								<?= $motorcycleInfo->getFullTitle() ?>
								<?php if ($motorcycleInfo->isCruiser) { ?>
                                    <br>
                                    <small><b>Круизёр</b></small>
								<?php } ?>
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
            </div>
		<?php } ?>
        <div id="newsletters-link">
            <h3>Подписаться на новости</h3>
            <div class="help-for-athlete">
                <small>
                    Подписываясь на новости, вы даёте согласие на отправку писем, содержащих информацию о
                    предстоящих этапах, на ваш email (<?= $athlete->email ?>).<br>
                    Вы можете подписаться на все новости всех регионов (просто отметив пункт "Подписаться на новостную
                    рассылку");
                    можете выбрать страны и регионы, новости которых вас интересуют; можете выбрать тип новостей. (Поля
                    для выбора
                    появятся после выбора пункта "Подписаться на новостную рассылку").
                    Если вы выберите страну, но не укажите ни одного региона, вам будут приходить все новости этой
                    страны.<br>
                    В любой момент вы можете отписаться от рассылки, сняв отметку в личном кабинете.
                </small>
            </div>
            <div class="form pt-10">
				<?php $form = ActiveForm::begin(['id' => 'newslettersForm']); ?>

                <div class="pb-10">
					<?= Html::checkbox('subscription', !$subscription->isNewRecord, [
						'label' => 'Подписаться на новостную рассылку',
						'id'    => 'subscriptionNews'
					]) ?>
                </div>

                <div class="subscription-info" style="display: <?= $subscription->isNewRecord ? 'none' : 'block' ?>">
					<?= $form->field($subscription, 'types')->widget(Select2::classname(), [
						'data'    => \common\models\NewsSubscription::$typesTitle,
						'options' => [
							'placeholder' => 'Выберите типы...',
							'id'          => 'subscript-types',
							'multiple'    => true,
						],
					])->label('Выберите типы'); ?>
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
							<?= $form->field($subscription, 'countryIds')->widget(Select2::classname(), [
								'data'          => Country::getAll(true),
								'options'       => [
									'placeholder' => 'Выберите страну...',
									'id'          => 'subscript-country-id',
									'multiple'    => true,
								],
								'pluginOptions' => [
									'allowClear' => true
								]
							])->label('Выберите страны'); ?>
                        </div>
                        <div class="col-md-6 col-sm-12">
							<?= $form->field($subscription, 'regionIds')->widget(DepDrop::classname(), [
								'data'           => $subscription->getRegions(true, $subscription->countryIds),
								'options'        => ['placeholder' => 'Выберите регионы...'],
								'type'           => DepDrop::TYPE_SELECT2,
								'select2Options' => [
									'pluginOptions' => [
										'multiple'           => true,
										'allowClear'         => true,
										'minimumInputLength' => 3,
										'language'           => [
											'errorLoading' => new JsExpression("function () { return 'Поиск результатов...'; }"),
										],
										'ajax'               => [
											'url'      => \yii\helpers\Url::to(['/help/regions-list']),
											'dataType' => 'json',
											'data'     => new JsExpression('function(params) { return {title:params.term, countryId:$("#subscript-country-id").val()}; }')
										],
										'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
										'templateResult'     => new JsExpression('function(region) { return region.text; }'),
										'templateSelection'  => new JsExpression('function (region) { return region.text; }'),
									],
								],
								'pluginOptions'  => [
									'depends'     => ['subscript-country-id'],
									'url'         => \yii\helpers\Url::to(['/help/country-category', 'type' => \champ\controllers\HelpController::TYPE_CITY]),
									'loadingText' => 'Для выбранной страны нет регионов...',
									'placeholder' => 'Выберите регион...'
								]
							])->label('Выберите регионы'); ?>
                        </div>
                    </div>
                </div>
                <div class="pt-10 text-right">
					<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
				<?php $form->end(); ?>
            </div>
        </div>
    </div>
</div>
