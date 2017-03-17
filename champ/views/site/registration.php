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

<?php $form = ActiveForm::begin(['options' => ['class' => 'registrationAthlete']]) ?>
<div class="registration-form">

    <h4 class="text-center">Укажите информацию о себе</h4>
	<?= $form->field($registration, 'lastName')->textInput(['placeholder' => 'Ваша фамилия']) ?>
	<?= $form->field($registration, 'firstName')->textInput(['placeholder' => 'Полное имя']) ?>
	<?php if (!$registration->countryId) { ?>
		<?php $registration->countryId = 1 ?>
	<?php } ?>
	<?= $form->field($registration, 'countryId')->widget(Select2::classname(), [
		'data'    => Country::getAll(true),
		'options' => [
			'placeholder' => 'Выберите страну...',
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
        </div>
        <div class="small">
            <a href="#" class="list" id="cityNotFound">Нажмите, если вашего города нет в списке</a>
        </div>
        <div id="city-text" class="inactive">
			<?= $form->field($registration, 'city')->textInput(['placeholder' => 'Введите Ваш город и регион', 'id' => 'city-text-input']) ?>
        </div>
    </div>
	
	<?= $form->field($registration, 'phone')->textInput(['placeholder' => 'Номер телефона']) ?>
	
	<?= $form->field($registration, 'email')->textInput(['placeholder' => 'Email']) ?>

    <h4 class="text-center">Укажите мотоцикл</h4>
	<?php $i = 0; ?>
    <div class="row motorcycles">
        <div class="col-sm-6 col-xs-12">
            <div class="form-group field-tmpathlete-mark has-success">
                <label class="control-label" for="tmpathlete-mark">Марка</label>
				<?= Html::input('text', 'mark[' . $i . ']', null, ['class' => 'form-control', 'placeholder' => 'Марка, напр. kawasaki']) ?>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <div class="form-group field-tmpathlete-model has-success">
                <label class="control-label" for="tmpathlete-model">Модель</label>
				<?= Html::input('text', 'model[' . $i . ']', null, ['class' => 'form-control', 'placeholder' => 'Модель, напр. ER6-F']) ?>
            </div>
        </div>
    </div>

    <a href="#" class="appendMotorcycle" data-i = <?= $i ?>>Добавить ещё один мотоцикл</a>

    <div class="alert alert-danger" style="display: none"></div>
    <div class="alert alert-success" style="display: none"></div>
    <div class="modal-footer">
        <div class="form-text"></div>
        <div class="button">
			<?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-lg btn-block btn-dark']) ?>
        </div>
    </div>
</div>
<?php $form->end() ?>
