<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View                  $this
 * @var \admin\models\ResultTimeForm   $model
 * @var \common\models\AthletesClass[] $classes
 */

$this->title = 'Расчёт результата заезда спортсмена';
?>

    <div class="alert alert-info">
        Необходимо указать время лучшего заезда (с учётом штрафов) спортсмена, класс спортсмена и эталонное время
        трассы.
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
			)) ?>
        </div>
        <div class="col-sm-3">
			<?= $form->field($model, 'referenceTimeForHuman')->widget(MaskedInput::classname(), [
				'mask'    => '99:99.99',
				'options' => [
					'id'    => 'referenceTimeForHuman',
					'class' => 'form-control',
					'type'  => 'tel'
				]
			]) ?>
        </div>
        <div class="col-sm-2">
            <label>&nbsp;</label><br>
			<?= Html::submitButton('Результат заезда', ['class' => 'btn btn-my-style btn-aquamarine']) ?>
        </div>
    </div>
<?php $form->end(); ?>

<?php if ($model->percent) { ?>
    <div class="calculate-result">
        <b>Рейтинг:</b> <?= $model->percent ?>%<br>
        <?php if ($model->newClass) { ?>
            <b>Новый класс:</b> <?= $model->newClass ?>.
            <br><br>
            <b>Обратите внимание!</b> Если класс соревнования ниже, чем новый класс спортсмена - спортсмену присваивается класс соревнования.<br>
            <small>Например: если рассчитанный класс спортсмена C2, а класс соревнования - D1, то спортсмен получает класс D1.</small>
        <?php } else { ?>
            <b>Класс спортсмена не изменился</b>
        <?php } ?>
    </div>
<?php } ?>