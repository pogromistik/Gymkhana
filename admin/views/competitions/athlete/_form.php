<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use common\models\Country;
use kartik\widgets\DepDrop;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Athlete */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="athlete-form">
	<?php $form = ActiveForm::begin(['options' => ['id' => $model->isNewRecord ? 'newAthlete' : 'updateAthlete']]); ?>
	
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
			$cities = ArrayHelper::map(\common\models\City::find()->where(['countryId' => $model->countryId])->orderBy(['title' => SORT_ASC])->all(),
				'id', 'title');
		}
	}
	?>
	<?= $form->field($model, 'cityId')->widget(DepDrop::classname(), [
		'data'           => $cities,
		'options'        => ['placeholder' => 'Выберите город ...'],
		'type'           => DepDrop::TYPE_SELECT2,
		'select2Options' => ['pluginOptions' => ['allowClear' => true]],
		'pluginOptions'  => [
			'depends'     => ['country-id'],
			'url'         => Url::to(['/competitions/help/country-category']),
			'loadingText' => 'Для выбранной страны нет городов...',
			'placeholder' => 'Выберите город...'
		]
	]); ?>
	
	<?= $form->field($model, 'lastName')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'firstName')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
	
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
	
	<?= $form->field($model, 'number')->textInput() ?>

    <div class="form-group complete">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
