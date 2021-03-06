<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\widgets\MaskedInput;

/**
 * @var \yii\web\View                  $this
 * @var \common\models\TmpFigureResult $model
 * @var \common\models\Motorcycle[]    $motorcycles
 * @var \common\models\Figure[]        $figures
 */
?>
<div class="tmp-figure-result-index">

    <div class="card-box">
        <h2><?= \Yii::t('app', 'Отправка своего результата по базовой фигуре') ?></h2>

        <h4><?= Html::a(\Yii::t('app', 'посмотреть историю'), ['/figures/requests']) ?></h4>
    </div>
	
	<div class="card-box">
		<?php $form = ActiveForm::begin(['options' => ['id' => 'sendFigureResult']]); ?>
		
		<?= $form->field($model, 'athleteId')->hiddenInput()->error(false)->label(false) ?>
		
		<?= $form->field($model, 'motorcycleId')->dropDownList(ArrayHelper::map($motorcycles, 'id', function (\common\models\Motorcycle $item) {
			return $item->getFullTitle();
		})) ?>
		
		<?= $form->field($model, 'figureId')->dropDownList(ArrayHelper::map($figures, 'id', 'title')) ?>
		
		<?= $form->field($model, 'dateForHuman')->widget(DatePicker::className(), [
			'type'          => DatePicker::TYPE_INPUT,
			'value'         => date('d.m.Y', time()),
			'language'      => 'ru',
			'options'       => ['placeholder' => \Yii::t('app', 'Укажите дату')],
			'removeButton'  => false,
			'pluginOptions' => [
				'autoclose'      => true,
				'format'         => 'dd.mm.yyyy',
				'todayHighlight' => true
			]
		]) ?>
		
		<?= $form->field($model, 'timeForHuman', ['inputTemplate' => '<div class="input-with-description"><div class="text">
 ' . \Yii::t('app', 'мин:сек.мсек') . '
</div>{input}</div>'])->widget(MaskedInput::classname(), [
			'mask'    => '99:99.99',
			'options' => [
				'id'    => 'setTime',
				'class' => 'form-control',
				'type'  => 'tel'
			]
		]) ?>
		
		<?= $form->field($model, 'fine')->textInput() ?>
		
		<?= $form->field($model, 'videoLink', ['inputTemplate' => '<div class="input-with-description"><div class="text">
 ' . \Yii::t('app', 'принимаются результаты, для которых есть видео заезда')
			. '</div>{input}</div>'])->textInput(['maxlength' => true]) ?>

        <div class="alert alert-danger" style="display: none"></div>
        <div class="alert alert-success" style="display: none"></div>

        <div class="form-group">
			<?= Html::submitButton(\Yii::t('app', 'Отправить'), ['class' => 'btn btn-dark']) ?>
        </div>
		
		<?php ActiveForm::end(); ?>
    </div>

</div>
