<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\Country;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model common\models\Athlete */
/* @var $form yii\widgets\ActiveForm */

if (!$model->countryId) {
	$model->countryId = 1;
}
?>

<div class="alert help-alert alert-info">
    <div class="text-right">
        <span class="fa fa-remove closeHintBtn"></span>
    </div>
    Поля, обязательные для заполнения: "Страна", "Город", "Фамилия", "Имя".<br>
    Если необходимых города или страны нет в списке, добавьте их в
    разделе <?= Html::a('Города', ['/competitions/help/cities']) ?><br>
	<?php if (Yii::$app->user->can('projectAdmin')) { ?>
        Менять класс спортсмена лучше через раздел
		<?= Html::a('Изменение класса спортсмену', ['/competitions/athlete/change-class']) ?>
        или при добавлении времени по фигуре \ при проведении этапа - так статистика спортсменов
        будет более корректной. Но при необходимости вы можете сделать это здесь.<br>
	<?php } ?>
	<?php if ($model->isNewRecord) { ?>
        <b>Не добавляйте спортсмена, если он уже есть в системе.</b> При добавлении спортсмена система производит поиск
        совпадений с
        существующими данными, и в случае успеха показывает уточняющее уведомление. Пожалуйста, будьте внимательны на этом
        моменте.
	<?php } ?>
</div>

<div class="athlete-form">
	<?php if ($model->isNewRecord) { ?>
		<?php $form = ActiveForm::begin(['options' => ['id' => 'newAthlete']]); ?>
	<?php } else { ?>
		<?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'options' => ['id' => 'updateAthlete']]); ?>
	<?php } ?>
	
	<?php if (!$model->isNewRecord && $model->photo) { ?>
        <div class="row">
            <div class="col-md-2 col-sm-4 img-in-profile">
				<?= Html::img(\Yii::getAlias('@filesView') . '/' . $model->photo) ?>
                <br>
                <a href="#" class="btn btn-warning btn-block deletePhoto" data-id="<?= $model->id ?>"
                   data-model="<?= \admin\controllers\competitions\HelpController::PHOTO_ATHLETE ?>">удалить</a>
                <br>
            </div>
        </div>
	<?php } ?>
	
	<?= $form->field($model, 'countryId')->widget(Select2::classname(), [
		'data'    => Country::getAll(true),
		'options' => [
			'placeholder' => 'Выберите страну...',
			'id'          => 'country-id',
		],
	]); ?>
	
	<?php $cities = [];
	if ($model->cityId) {
		$cities = [$model->cityId => $model->city->title];
		if ($model->countryId !== null) {
			$cities = ArrayHelper::map(\common\models\City::find()->where(['countryId' => $model->countryId])
				->andWhere(['!=', 'id', $model->cityId])
				->orderBy(['title' => SORT_ASC])->limit(50)->all(),
				'id', 'title');
			$cities[$model->cityId] = $model->city->title;
		}
	} elseif ($model->countryId !== null) {
		$cities = ArrayHelper::map(\common\models\City::find()->where(['countryId' => $model->countryId])
			->orderBy(['title' => SORT_ASC])->limit(50)->all(),
			'id', 'title');
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
	
	<?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'language')->dropDownList(\common\models\TranslateMessage::$languagesTitle) ?>
	
	<?php if (Yii::$app->user->can('projectAdmin')) { ?>
		<?php
		$startClass = \common\models\AthletesClass::getStartClass();
		$text = '';
		if ($startClass) {
			$text = 'По умолчанию будет установлен класс ' . $startClass->title;
		}
		?>
		<?= $form->field($model, 'athleteClassId',
			['inputTemplate' => '<div class="input-with-description"><div class="text">
 ' . $text . '
</div>{input}</div>'])->dropDownList(\yii\helpers\ArrayHelper::map(
			\common\models\AthletesClass::find()->andWhere(['status' => \common\models\AthletesClass::STATUS_ACTIVE])->orderBy(['sort' => SORT_ASC])->all(), 'id', 'title'
		), ['prompt' => 'Укажите класс спортсмена']) ?>
	<?php } ?>
	
	<?php if (Yii::$app->user->can('projectAdmin')) { ?>
		<?= $form->field($model, 'number')->textInput() ?>
	<?php } ?>

    <div class="form-group complete">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить',
			['class' => $model->isNewRecord ? 'btn btn-my-style btn-green' : 'btn btn-my-style btn-blue']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
