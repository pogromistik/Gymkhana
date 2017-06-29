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

    <h2>Отправить запрос на измменение класса</h2>

    <h4><?= Html::a('посмотреть историю', ['/figures/requests']) ?></h4>
	
	<?php $form = ActiveForm::begin(['options' => ['id' => 'changeClassRequest']]); ?>
	
	<?= $form->field($model, 'athleteId')->hiddenInput()->error(false)->label(false) ?>
	
	<?= $form->field($model, 'newClassId')->dropDownList(ArrayHelper::map($classes, 'id', 'title')) ?>
	
	<?= $form->field($model, 'comment')->textarea(['rows' => 3, 'placeholder' =>
        'Укажите всю необходимую информацию. Например, добавите ссылку на результаты чемпионата и описание класса соревнований']) ?>

    <div class="alert alert-danger" style="display: none"></div>
    <div class="alert alert-success" style="display: none"></div>

    <div class="form-group">
		<?= Html::submitButton('Отправить', ['class' => 'btn btn-dark']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
