<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Year;
use yii\helpers\ArrayHelper;
use common\models\Championship;
use kartik\widgets\Select2;
use dosamigos\ckeditor\CKEditor;

/* @var $this yii\web\View */
/* @var $model Championship */
/* @var $form yii\widgets\ActiveForm */
$country = \common\models\Country::getRussia();
?>

<div class="championship-form">

    <div class="alert help-alert alert-info">
        <div class="text-right">
            <span class="fa fa-remove closeHintBtn"></span>
        </div>
        <ul>
	        <?php if ($model->isNewRecord && $model->groupId != Championship::GROUPS_RUSSIA) { ?>
                <li>
                    Если вы впервые проводите соревнования через нашу систему, сперва создайте
                    <a href="#createGroup">региональный раздел</a> внизу страницы
                </li>
	        <?php } ?>
            <li>
                Поля, обязательные для заполнения: "Региональный раздел" и "Год". Если оставить поле "Название" пустым, по умолчанию туда
                запишется название раздела.
            </li>
            <li>
                Если вы отметите пункт "Закрытый чемпионат", до регистрации будут допущены только спортсмены из выбранных регионов
                (поле для выбора появится после того, как вы отметите этот пункт). Обратите внимание - спортсмены из других регионов не смогут даже
                оставить заявку на участие! Если вы допускаете иногородних, к примеру, "вне зачёта" - не отмечайте этот пункт!
            </li>
            <li>
                Поля "Обязательное количество этапов для спортсмена", "Количество этапов, по которым подсчитывается итог", "Необходимо хоть раз выступить в другом городе"
                нужны для посчётов итогов многоэтапных чемпионатов.
            </li>
            <li>
                "Показывать итоги чемпионата" - если этот пункт не отмечен, общий итог чемпионата нигде не будет выводиться. Особенно актуально для
                одноэтапных соревнований, где итог чемпионата, по сути, совпадает с итогами единственного этапа.
            </li>
        </ul>
    </div>
	
	<?php $form = ActiveForm::begin(['enableAjaxValidation' => !$model->isNewRecord]); ?>
	
	<?php if ($model->groupId == Championship::GROUPS_REGIONAL) { ?>
		<?= $form->field($model, 'regionGroupId',
			['inputTemplate' => '<div class="input-with-description"><div class="text">
 Если в списке нет необходимого раздела - создайте его в <a href="#createGroup">форме внизу страницы</a> .
</div>{input}</div>'])->widget(Select2::classname(), [
			'name'    => 'kv-type-01',
			'data'    => \yii\helpers\ArrayHelper::map(\common\models\RegionalGroup::find()->orderBy(['title' => SORT_ASC])->all(), 'id', 'title'),
			'options' => [
				'placeholder' => 'Выберите раздел. Добавить новый раздел можно внизу страницы',
			],
		]) ?>
	<?php } ?>
	
	<?= $form->field($model, 'title')->textInput(['placeholder' => 'название чемпионата, необязательное поле']) ?>

    <div class="champ-description">
		<?= $form->field($model, 'description')->widget(CKEditor::className(), [
			'preset' => 'full', 'clientOptions' => ['height' => 150]
		]) ?>
    </div>

    <a href="#" class="btn btn-my-style btn-gray small" id="enInfo">Добавить информацию на английском</a>
    <div class="en_info">
        <small><b>Внимание! Скрытие этого блока не удаляет введённую информацию, т.е. если вы заполните поля, потом
                скроете блок и нажмёте "сохранить" - информация сохранится</b></small>
		<?= $form->field($model, 'title_en')->textInput(['maxlength' => true]) ?>
		<?= $form->field($model, 'descr_en')->widget(CKEditor::className(), [
			'preset' => 'full', 'clientOptions' => ['height' => 150]
		]) ?>
    </div>
	
	<?= $form->field($model, 'yearId')->dropDownList(ArrayHelper::map(Year::find()->orderBy(['year' => SORT_DESC])->all(), 'id', 'year')) ?>
	
	<?= $form->field($model, 'isClosed')->checkbox(['id' => 'isClosedChamp']) ?>

    <div id="regionsForChamp" style="display: <?= $model->isClosed ? 'block' : 'none' ?>">
		<?= $form->field($model, 'onlyRegions',
			['inputTemplate' => '<div class="input-with-description"><div class="text">
Если все этапы чемпионата будут проходить в одном регионе - укажите его.
</div>{input}</div>'])->widget(Select2::classname(), [
			'name'          => 'kv-type-01',
			'data'          => \common\models\Region::getAll(true, $country->id),
			'options'       => [
				'placeholder' => 'Выберите регион...',
			],
			'pluginOptions' => [
				'allowClear' => true,
				'multiple'   => true
			],
		]) ?>
    </div>
	
	<?= $form->field($model, 'status')->dropDownList(Championship::$statusesTitle) ?>
	
	<?= $form->field($model, 'groupId')->hiddenInput()->label(false)->error(false) ?>
	
	<?php if ($model->groupId != Championship::GROUPS_RUSSIA) { ?>
		<?= $form->field($model, 'regionId',
			['inputTemplate' => '<div class="input-with-description"><div class="text">
Если все этапы чемпионата будут проходить в одном регионе - укажите его.
</div>{input}</div>'])->widget(Select2::classname(), [
			'name'          => 'kv-type-01',
			'data'          => \common\models\Region::getAll(true, $country->id),
			'options'       => [
				'placeholder' => 'Выберите регион...',
			],
			'pluginOptions' => [
				'allowClear' => true
			],
		]) ?>
	<?php } ?>

    <b>Диапазон стартовых номеров участников</b>
    <div class="row">
        <div class="col-md-4 col-sm-6">
			<?= $form->field($model, 'minNumber',
				['inputTemplate' => '<div class="input-with-description"><div class="text">
 минимальный номер
</div>{input}</div>'])->textInput(['placeholder' => 'минимальный номер'])->label(false) ?>
        </div>
        <div class="col-md-4 col-sm-6">
			<?= $form->field($model, 'maxNumber',
				['inputTemplate' => '<div class="input-with-description"><div class="text">
 максимальный номер
</div>{input}</div>'])->textInput(['placeholder' => 'минимальный номер'])->label(false) ?>
        </div>
    </div>
	
	<?= $form->field($model, 'amountForAthlete')->textInput(['placeholder' => 'обязательное поле']) ?>
	<?= $form->field($model, 'estimatedAmount')->textInput(['placeholder' => 'обязательное поле']) ?>
	<?= $form->field($model, 'requiredOtherRegions')->checkbox() ?>
	<?= $form->field($model, 'useCheScheme',
		['inputTemplate' => '<div class="input-with-description"><div class="text">
' . Html::a('Нажмите, чтобы узнать подробнее о Челябинской схеме награждения', ['/competitions/help/che-scheme'], ['target' => '_blank']) . '
</div>{input}</div>'])->checkbox() ?>
	<?= $form->field($model, 'showResults')->checkbox() ?>
	<?= $form->field($model, 'useMoscowPoints',
		['inputTemplate' => '<div class="input-with-description"><div class="text">
' . Html::a('Нажмите, чтобы узнать подробнее о Московской схеме баллов', ['/competitions/help/moscow-points-scheme'], ['target' => '_blank']) . '
</div>{input}</div>'])->checkbox() ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-my-style btn-green' : 'btn btn-my-style btn-blue']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
