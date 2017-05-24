<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View                   $this
 * @var \admin\models\ReferenceTimeForm $model
 * @var \common\models\AthletesClass[]  $classes
 */

$this->title = 'Расчёт эталонного времени трассы';
?>

<div class="alert alert-info">
    Необходимо указать время лучшего заезда (с учётом штрафов) и класс соревнования.<br>
    <b>Внимание!</b> Класс спортсмена, показавшего лучшее время должен совпадать с классом соревнования. Например, лучшее время на этапе
    показал спортсмен класса C1, но класс соревнования - C3. В таком случае, указывать необходимо результат спортсмена, показавшего лучшее время
    в классе C3.
</div>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-sm-3">
		<?= $form->field($model, 'timeForHuman')->widget(MaskedInput::classname(), [
			'mask'    => '99:99.99',
			'options' => [
				'id'    => 'timeForHuman',
				'class' => 'form-control',
				'type'  => 'tel'
			]
		]) ?>
    </div>
    <div class="col-sm-2"><?= $form->field($model, 'class')->dropDownList(\yii\helpers\ArrayHelper::map(
			$classes, 'id', 'title'
		)) ?></div>
    <div class="col-sm-2">
        <label>&nbsp;</label><br>
		<?= Html::submitButton('Рассчитать эталонное время', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php $form->end(); ?>

<?php if ($model->referenceTime) { ?>
    <div class="calculate-result">
        <b>Эталонное время для указанных данных:</b> <?= $model->referenceTimeForHuman ?>
    </div>
<?php } ?>