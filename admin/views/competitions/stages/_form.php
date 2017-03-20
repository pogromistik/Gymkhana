<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use yii\helpers\Url;
use kartik\widgets\DepDrop;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use common\models\Country;

/* @var $this yii\web\View */
/* @var $model common\models\Stage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stage-form">
	
	<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'countryId')->widget(Select2::classname(), [
		'data'    => Country::getAll(true),
		'options' => [
			'placeholder' => 'Выберите страну...',
			'id'          => 'country-id',
		],
	]); ?>
	<?php
	$cities = [];
	if ($model->cityId) {
		$cities = ArrayHelper::map(\common\models\City::find()->where(['countryId' => $model->city->countryId])
			->andWhere(['!=', 'id', $model->cityId])
			->orderBy(['title' => SORT_ASC])->limit(50)->all(),
			'id', 'title');
		$cities[$model->cityId] = $model->city->title;
	}
	?>
	<?php $url = \yii\helpers\Url::to(['/competitions/help/city-list']); ?>
	<?= $form->field($model, 'cityId')->widget(DepDrop::classname(), [
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
			'url'         => Url::to(['/competitions/help/country-category', 'type' => \champ\controllers\HelpController::TYPE_CITY]),
			'loadingText' => 'Для выбранной страны нет городов...',
			'placeholder' => 'Выберите город...',
		]
	]); ?>
	
	<?= $form->field($model, 'championshipId')->hiddenInput()->label(false)->error(false) ?>
	
	<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
	
	<?= $form->field($model, 'countRace')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'dateOfTheHuman')->widget(DatePicker::classname(), [
		'options'       => ['placeholder' => 'Введите дату проведения соревнований'],
		'removeButton'  => false,
		'language'      => 'ru',
		'pluginOptions' => [
			'autoclose' => true,
			'format'    => 'dd.mm.yyyy',
		]
	]) ?>
	
	<?= $form->field($model, 'startRegistrationHuman',
		['inputTemplate' => '<div class="input-with-description"><div class="text">время считается GMT +5 (Челябинск)</div>{input}</div>'])
		->widget(DateTimePicker::classname(), [
			'options'       => ['placeholder' => 'Введите дату и время начала регистрации'],
			'removeButton'  => false,
			'language'      => 'ru',
			'pluginOptions' => [
				'autoclose' => true,
				'format'    => 'dd.mm.yyyy, hh:ii',
			]
		]) ?>
	
	<?= $form->field($model, 'endRegistrationHuman',
		['inputTemplate' => '<div class="input-with-description"><div class="text">время считается GMT +5 (Челябинск)</div>{input}</div>'])
		->widget(DateTimePicker::classname(), [
			'options'       => ['placeholder' => 'Введите дату и время завершения регистрации'],
			'removeButton'  => false,
			'language'      => 'ru',
			'pluginOptions' => [
				'autoclose' => true,
				'format'    => 'dd.mm.yyyy, hh:ii',
			]
		]) ?>
	
	<?php if ($model->trackPhoto) { ?>
        <div class="row">
            <div class="col-md-2 col-sm-4 img-in-profile">
				<?= Html::img(\Yii::getAlias('@filesView') . '/' . $model->trackPhoto) ?>
                <br>
                <a href="#" class="btn btn-default btn-block deletePhoto" data-id="<?= $model->id ?>"
                   data-model="<?= \admin\controllers\competitions\HelpController::PHOTO_STAGE ?>">удалить</a>
                <br>
            </div>
            <div class="col-md-10 col-sm-8">
				<?= $form->field($model, 'photoFile', ['inputTemplate' => '<div class="input-with-description"><div class="text">
 Допустимые форматы: png, jpg. Максимальный размер: 2МБ.
</div>{input}</div>'])->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
				<?= $form->field($model, 'trackPhotoStatus')->checkbox() ?>
            </div>
        </div>
	<?php } else { ?>
		<?= $form->field($model, 'photoFile', ['inputTemplate' => '<div class="input-with-description"><div class="text">
 Допустимые форматы: png, jpg. Максимальный размер: 2МБ.
</div>{input}</div>'])->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
		<?= $form->field($model, 'trackPhotoStatus')->checkbox() ?>
	<?php } ?>
	
	<?= $form->field($model, 'documentId',
		['inputTemplate' => '<div class="input-with-description"><div class="text">
 Предварительно необходимо загрузить нужный регламент в раздел "'
			. Html::a('документы', ['/competitions/documents/update', 'id' => \common\models\DocumentSection::REGULATIONS],
                ['target' => '_blank']) . '".</div>{input}</div>'])
		->widget(Select2::classname(), [
			'data'    => ArrayHelper::map(\common\models\OverallFile::getActualRegulations(), 'id', 'title'),
			'options' => [
				'placeholder' => 'Выберите регламент...'
			],
            'pluginOptions' => [
	            'allowClear'         => true,
            ]
		]) ?>
	
	<?= $form->field($model, 'class',
		['inputTemplate' => '<div class="input-with-description"><div class="text">
 Это поле заполняется автоматически после завершения регистрации. Меняйте его только в том случае, если рассчитанный класс
        не будет соответствовать действительности. Если этап ещё не начался - оставьте это поле пустым.
</div>{input}</div>'])
		->dropDownList(\yii\helpers\ArrayHelper::map(
			\common\models\AthletesClass::find()->andWhere(['status' => \common\models\AthletesClass::STATUS_ACTIVE])->orderBy(['sort' => SORT_ASC])->all(), 'id', 'title'
		), ['prompt' => 'Укажите класс']) ?>
	
	<?= $form->field($model, 'status')->dropDownList(\common\models\Stage::$statusesTitle) ?>
    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
