<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use kartik\widgets\DepDrop;
use kartik\date\DatePicker;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;

/**
 * @var \admin\models\SpecialRequestForm $formModel
 * @var int                              $id
 */
?>

<?php $form = ActiveForm::begin(['id' => 'changeTmpAthleteForm']); ?>
    <input type="hidden" value="<?= $id ?>" id="tmp-id">
<?php if (!$formModel->athleteId) { ?>
	<?= $form->field($formModel, 'lastName')->textInput() ?>
	<?= $form->field($formModel, 'firstName')->textInput() ?>
	<?= $form->field($formModel, 'email')->textInput() ?>
	
	<?= $form->field($formModel, 'countryId')->widget(Select2::classname(), [
		'data'    => \common\models\Country::getAll(true),
		'options' => [
			'placeholder' => \Yii::t('app', 'Выберите страну') . '...',
			'id'          => 'country-id',
		],
	]); ?>
	
	<?php
	$url = \yii\helpers\Url::to(['/competitions/help/city-list']);
	$cities = ArrayHelper::map(\common\models\City::find()->where(['countryId' => $formModel->countryId])
		->orderBy(['title' => SORT_ASC])->limit(50)->all(),
		'id', 'title');
	?>
	
	<?php $cities = [];
	if ($formModel->cityId) {
		$cities = [$formModel->cityId => $formModel->getCity()->title];
		if ($formModel->countryId !== null) {
			$cities = ArrayHelper::map(\common\models\City::find()->where(['countryId' => $formModel->countryId])
				->andWhere(['!=', 'id', $formModel->cityId])
				->orderBy(['title' => SORT_ASC])->limit(50)->all(),
				'id', 'title');
			$cities[$formModel->cityId] = $formModel->getCity()->title;
		}
	}
	?>
	<?= $form->field($formModel, 'cityId')->widget(DepDrop::classname(), [
		'data'           => $cities,
		'options'        => ['placeholder' => \Yii::t('app', 'Выберите город') . '...'],
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
			'url'         => \yii\helpers\Url::to(['/competitions/help/country-category', 'type' => \champ\controllers\HelpController::TYPE_CITY]),
			'loadingText' => 'Для выбранной страны нет городов...',
			'placeholder' => 'Выберите город...',
		]
	]); ?>
<?php } ?>

<?= $form->field($formModel, 'dateHuman')->widget(DatePicker::classname(), [
	'options'       => ['placeholder' => \Yii::t('app', 'Введите дату заезда')],
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

<?= Html::submitButton('Сохранить', ['class' => 'btn btn-my-style btn-blue']) ?>
<?php $form->end(); ?>