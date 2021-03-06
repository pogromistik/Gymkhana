<?php
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\bootstrap\Html;
use common\models\Country;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;

/**
 * @var \yii\web\View             $this
 * @var \common\models\TmpAthlete $registration
 */
?>
<h2><?= \Yii::t('app', 'Регистрация в личном кабинете') ?></h2>
<?php $form = ActiveForm::begin(['options' => ['class' => 'registrationAthlete']]) ?>
<div class="registration-form">

    <h4 class="text-center"><?= \Yii::t('app', 'Укажите информацию о себе') ?></h4>
	<div class="card-box">
		<?= $form->field($registration, 'lastName')->textInput(['placeholder' => \Yii::t('app', 'Ваша фамилия')]) ?>
		<?= $form->field($registration, 'firstName')->textInput(['placeholder' => \Yii::t('app', 'Полное имя')]) ?>
		<?php if (!$registration->countryId) { ?>
			<?php $registration->countryId = \common\helpers\GeoHelper::getUserCountryId(); ?>
		<?php } ?>
		<?= $form->field($registration, 'countryId')->widget(Select2::classname(), [
			'data'    => Country::getAll(true),
			'options' => [
				'placeholder' => \Yii::t('app', 'Выберите страну') . '...',
				'id'          => 'country-id',
			],
		]); ?>
		
		<?php
		$url = \yii\helpers\Url::to(['/help/city-list']);
		$cities = ArrayHelper::map(\common\models\City::find()->where(['countryId' => $registration->countryId])
			->orderBy(['title' => SORT_ASC])->limit(50)->all(),
			'id', 'title');
		?>

        <div class="registration-city">
            <div id="city-list">
				<?= $form->field($registration, 'cityId')->widget(DepDrop::classname(), [
					'data'           => $registration,
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
            </div>
            <div class="small">
                <a href="#" class="list" id="cityNotFound"><?= \Yii::t('app', 'Нажмите, если вашего города нет в списке') ?></a>
            </div>
            <div id="city-text" class="inactive">
				<?= $form->field($registration, 'city')->textInput(['placeholder' => \Yii::t('app', 'Введите Ваш город и регион'), 'id' => 'city-text-input']) ?>
            </div>
        </div>
		
		<?= $form->field($registration, 'phone')->textInput(['placeholder' => \Yii::t('app', 'Номер телефона')]) ?>
		
		<?= $form->field($registration, 'email')->textInput(['placeholder' => \Yii::t('app', 'Email')]) ?>
    </div>

    <h4 class="text-center"><?= \Yii::t('app', 'Укажите мотоцикл') ?></h4>
	<?php $i = 0; ?>
    <div class="card-box">
        <div class="row motorcycles">
            <div class="col-sm-6 col-xs-12">
                <div class="form-group field-tmpathlete-mark has-success">
                    <label class="control-label" for="tmpathlete-mark"><?= \Yii::t('app', 'Марка') ?></label>
				    <?= Html::input('text', 'mark[' . $i . ']', null, ['class' => 'form-control', 'placeholder' => \Yii::t('app', 'Марка, напр. kawasaki')]) ?>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="form-group field-tmpathlete-model has-success">
                    <label class="control-label" for="tmpathlete-model"><?= \Yii::t('app', 'Модель') ?></label>
				    <?= Html::input('text', 'model[' . $i . ']', null, ['class' => 'form-control', 'placeholder' => \Yii::t('app', 'Модель, напр. ER6-F')]) ?>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="form-group field-tmpathlete-cbm has-success">
                    <label class="control-label" for="tmpathlete-cbm">Объём</label>
				    <?= Html::input('text', 'cbm[' . $i . ']', null, ['class' => 'form-control', 'placeholder' => \Yii::t('app', 'Объём, см3')]) ?>
                </div>
            </div>
            <div class="col-sm-6 col-xs-12">
                <div class="form-group field-tmpathlete-power has-success">
                    <label class="control-label" for="tmpathlete-power">Мощность</label>
				    <?= Html::input('text', 'power[' . $i . ']', null, ['class' => 'form-control', 'placeholder' => \Yii::t('app', 'Мощность, л.с.')]) ?>
                </div>
            </div>
        </div>

        <a href="#" class="appendMotorcycle" data-i= <?= $i ?>><?= \Yii::t('app', 'Добавить ещё один мотоцикл') ?></a>

    </div>
    <div class="alert alert-danger" style="display: none"></div>
    <div class="alert alert-success" style="display: none"></div>
    <div class="modal-footer">
        <div class="form-text"></div>
        <div class="button">
			<?= Html::submitButton(\Yii::t('app', 'Зарегистрироваться'), ['class' => 'btn btn-lg btn-block btn-dark']) ?>
        </div>
    </div>
</div>
<?php $form->end() ?>
