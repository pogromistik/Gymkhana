<?php
use kartik\date\DatePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\MaskedInput;
use kartik\depdrop\DepDrop;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/**
 * @var \common\models\Stage $stage
 */

$athlete = \common\models\Athlete::findOne(\Yii::$app->user->id);
$formModel = new \champ\models\SpecialStageForm();
$formModel->stageId = $stage->id;
$formModel->dateHuman = date("d.m.Y");
$formModel->countryId = 1;
?>

<div class="registration-form">
	<h3>Заполните все поля</h3>
	
	<?php $form = ActiveForm::begin(['options' => ['id' => 'specialStageForGuest']]); ?>
	
	<?= $form->field($formModel, 'stageId')->hiddenInput()->error(false)->label(false) ?>
	
	<?= $form->field($formModel, 'lastName')->textInput(['placeholder' => 'Фамилия']) ?>
	<?= $form->field($formModel, 'firstName')->textInput(['placeholder' => 'Имя']) ?>
	<?= $form->field($formModel, 'email')->textInput(['placeholder' => 'Email']) ?>
	
	<?= $form->field($formModel, 'countryId')->widget(Select2::classname(), [
		'data'    => \common\models\Country::getAll(true),
		'options' => [
			'placeholder' => 'Выберите страну...',
			'id'          => 'country-id',
		],
	]); ?>
	
	<?php
	$url = \yii\helpers\Url::to(['/help/city-list']);
	$cities = ArrayHelper::map(\common\models\City::find()->where(['countryId' => $formModel->countryId])
		->orderBy(['title' => SORT_ASC])->limit(50)->all(),
		'id', 'title');
	?>
	
	<div class="registration-city">
		<div id="city-list">
			<?= $form->field($formModel, 'cityId')->widget(DepDrop::classname(), [
				'data'           => $formModel,
				'options'        => ['placeholder' => 'Выберите город...'],
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
							'data'     => new JsExpression('function(params) { return {title:params.term, countryId:$("#country-id").val(), championshipId: $("#championshipId").val()}; }')
						],
						'escapeMarkup'       => new JsExpression('function (markup) { return markup; }'),
						'templateResult'     => new JsExpression('function(city) { return city.text; }'),
						'templateSelection'  => new JsExpression('function (city) { return city.text; }'),
					],
				],
				'pluginOptions'  => [
					'depends'     => ['country-id'],
					'url'         => \yii\helpers\Url::to(['/help/country-category', 'type' => \champ\controllers\HelpController::TYPE_CITY]),
					'loadingText' => 'Для выбранной страны нет городов...',
					'placeholder' => 'Выберите город...',
				]
			]); ?>
		</div>
		<div class="small">
			<a href="#" class="list" id="cityNotFound">Нажмите, если вашего города нет в списке</a>
		</div>
		<div id="city-text" class="inactive">
			<?= $form->field($formModel, 'cityTitle')->textInput(['placeholder' => 'Введите Ваш город и регион', 'id' => 'city-text-input']) ?>
		</div>
	</div>
	
	<?= $form->field($formModel, 'motorcycleMark')->textInput(['placeholder' => 'Марка, напр. kawasaki']) ?>
	<?= $form->field($formModel, 'motorcycleModel')->textInput(['placeholder' => 'Модель, напр. ER6-F']) ?>
	
	<?= $form->field($formModel, 'dateHuman')->widget(DatePicker::classname(), [
		'options'       => ['placeholder' => 'Введите дату заезда'],
		'removeButton'  => false,
		'language'      => 'ru',
		'pluginOptions' => [
			'autoclose' => true,
			'format'    => 'dd.mm.yyyy',
		]
	]) ?>
	
	<?= $form->field($formModel, 'timeHuman')->widget(MaskedInput::classname(), [
		'mask'    => '99:99.99',
		'options' => [
			'id'    => 'setTime',
			'class' => 'form-control',
			'type'  => 'tel'
		]
	]) ?>
	
	<?= $form->field($formModel, 'fine')->textInput() ?>
	
	<?= $form->field($formModel, 'videoLink')->textInput() ?>
	
	<div class="alert alert-danger" style="display: none"></div>
	<div class="alert alert-success" style="display: none"></div>
	
	<div class="form-group">
		<?= Html::submitButton('Отправить', ['class' => 'btn btn-dark']) ?>
	</div>
	
	<?php $form->end(); ?>
</div>
