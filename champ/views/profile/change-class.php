<?php

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\widgets\MaskedInput;

/**
 * @var \yii\web\View                  $this
 * @var \common\models\ClassesRequest  $model
 * @var \common\models\AthletesClass[] $classes
 */
?>
<div class="tmp-figure-result-index">

    <h2><?= \Yii::t('app', 'Отправить запрос на изменение класса') ?></h2>
    <div class="card-box">
        <div>
			<?= \Yii::t('app', 'Если  у вас есть достаточные основания для смены класса - отправьте нам соответствующий запрос. Например, если вы приняли участие в соревновании, которое не было проведено через наш сайт. В таком случае нам будет достаточно ссылки на результаты, где будет видно ваш процент и класс соревнования.') ?>
        </div>
    </div>

    <h4><?= Html::a(\Yii::t('app', 'посмотреть историю'), ['/profile/history-classes-request']) ?></h4>

    <div class="card-box">
		<?php $form = ActiveForm::begin(['options' => ['id' => 'changeClassRequest']]); ?>
		
		<?= $form->field($model, 'athleteId')->hiddenInput()->error(false)->label(false) ?>
		
		<?= $form->field($model, 'newClassId')->dropDownList(ArrayHelper::map($classes, 'id', 'title')) ?>
		
		<?= $form->field($model, 'comment')->textarea(['rows' => 3, 'placeholder' =>
			\Yii::t('app', 'Укажите всю необходимую информацию. Например, добавьте ссылку на результаты чемпионата и описание класса соревнований')]) ?>

        <div class="alert alert-danger" style="display: none"></div>
        <div class="alert alert-success" style="display: none"></div>

        <div class="form-group">
			<?= Html::submitButton(\Yii::t('app', 'Отправить'), ['class' => 'btn btn-dark']) ?>
        </div>
		
		<?php ActiveForm::end(); ?>
    </div>

</div>
