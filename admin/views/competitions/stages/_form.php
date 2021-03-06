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
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model common\models\Stage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="stage-form">
    <div class="alert help-alert alert-info">
        <div class="text-right">
            <span class="fa fa-remove closeHintBtn"></span>
        </div>
        <ul>
            <li>
                Поля, обязательные для заполнения: "Страна", "Город проведения", "Название", "Количество заездов".
            </li>
            <li>
                Обратите внимание - если у вас ограниченное количество участников, то все заявки будут нуждаться в
                предварительной
                модерации (осуществляется на странице
				<?= $model->isNewRecord ? 'со списком участников' :
					Html::a('со списком участников', ['/competitions/participants/index', 'stageId' => $model->id]) ?>).
                Таким образом, при необходимости вы принудительно сможете зарегистрировать больше людей, чем указано в
                этом поле.
            </li>
            <li>
                Поля "Начало регистрации" и "Завершение регистрации" нужны для предворительной регистрации (с сайта).
            </li>
            <li>
                Никто не увидит фото трассы, пока вы не отметите пункт "Опубликовать трассу".
            </li>
            <li>
                Класс соревнования рассчитывается автоматически после завершения регистрации и квалификационных заездов.
                Его изменение имеет смысл только в случае, если рассчёты по этому классу могут привести к некорректным
                изменениям (например,
                у вас на этап приехало 3 спортсмена класса C3. Двое из них полностью завалили обе попытки, а третий
                проехал, но очень плохо.
                В такой ситуации многие спортсмены попадут в класс C3, фактически едва дотягивая до D2. Мы бы
                рекомендовали в такой ситуации
                принудительно понизить класс до D1 или другого, по которому рассчёт будет произведён более корректно.)
            </li>
            <li>
                Если отмечен пункт "вне зачёта", баллы за этот этап не будут суммироваться при подсчёте итогов
                чемпионата.
            </li>
        </ul>
    </div>
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
	
	<?= $form->field($model, 'description')->widget(CKEditor::className(), [
		'preset' => 'full', 'clientOptions' => ['height' => 150]
	]) ?>

    <a href="#" class="btn btn-my-style btn-gray small" id="enInfo">Добавить информацию на английском</a>
    <div class="en_info">
        <small><b>Внимание! Скрытие этого блока не удаляет введённую информацию, т.е. если вы заполните поля, потом
                скроете блок и нажмёте "сохранить" - информация сохранится</b></small>
		<?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'descr_en')->widget(CKEditor::className(), [
			'preset' => 'full', 'clientOptions' => ['height' => 150]
		]) ?>
    </div>
	
	<?= $form->field($model, 'outOfCompetitions')->checkbox() ?>
	
	<?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'countRace')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'participantsLimit',
		['inputTemplate' => '<div class="input-with-description"><div class="text">
 Если ограничения по количеству участников нет - оставьте поле пустым.
</div>{input}</div>'])
		->textInput() ?>
	
	<?= $form->field($model, 'dateOfTheHuman')->widget(DatePicker::classname(), [
		'options'       => ['placeholder' => 'Введите дату проведения соревнований'],
		'removeButton'  => false,
		'language'      => 'ru',
		'pluginOptions' => [
			'autoclose' => true,
			'format'    => 'dd.mm.yyyy',
		]
	]) ?>
	
	<?= $form->field($model, 'documentIds',
		['inputTemplate' => '<div class="input-with-description"><div class="text">
 Предварительно необходимо загрузить нужный регламент в раздел "'
			. Html::a('документы', ['/competitions/documents/update', 'id' => \common\models\DocumentSection::REGULATIONS],
				['target' => '_blank']) . '".</div>{input}</div>'])
		->widget(Select2::classname(), [
			'data'          => ArrayHelper::map(\common\models\OverallFile::getActualRegulations(), 'id', 'title'),
			'options'       => [
				'placeholder' => 'Выберите регламент...',
				'multiple'    => true
			],
			'pluginOptions' => [
				'allowClear' => true,
			]
		]) ?>
	
	<?= $form->field($model, 'registrationFromSite')->checkbox() ?>
	
	<?= $form->field($model, 'startRegistrationHuman',
		['inputTemplate' => '<div class="input-with-description"><div class="text">если для выбранного города не установлен часовой 
пояс - по умолчанию будет установлено Московское время. Изменить пояс можно в разделе "города"</div>{input}</div>'])
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
		['inputTemplate' => '<div class="input-with-description"><div class="text">если для выбранного города не установлен часовой 
пояс - по умолчанию будет установлено Московское время. Изменить пояс можно в разделе "города"</div>{input}</div>'])
		->widget(DateTimePicker::classname(), [
			'options'       => ['placeholder' => 'Введите дату и время завершения регистрации'],
			'removeButton'  => false,
			'language'      => 'ru',
			'pluginOptions' => [
				'autoclose' => true,
				'format'    => 'dd.mm.yyyy, hh:ii',
			]
		]) ?>
	
	<?= $form->field($model, 'fastenClassFor',
		['inputTemplate' => '<div class="input-with-description"><div class="text">
Класс участников этапа перестанет меняться за указанное количество дней (напр. спортсмен зарегистрировался на этап в классе N,
затем проехал GP8 в класс D3. Класс спортсмена на сайте изменится, но на этом этапе он будет выступать в классе N).
</div>{input}</div>'])
		->textInput(['placeholder' => 'Количество суток...']) ?>
	
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
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',
			['class' => $model->isNewRecord ? 'btn btn-my-style btn-green' : 'btn btn-my-style btn-blue']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
