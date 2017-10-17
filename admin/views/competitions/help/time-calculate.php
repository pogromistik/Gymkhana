<?php
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\bootstrap\Html;

/**
 * @var \yii\web\View                   $this
 * @var \admin\models\ReferenceTimeForm $model
 * @var \common\models\AthletesClass[]  $classes
 * @var array                           $needTime
 */

$this->title = 'Расчёт эталонного времени трассы';
?>

    <div class="alert help-alert alert-info">
        <div class="text-right">
            <span class="fa fa-remove closeHintBtn"></span>
        </div>
        Необходимо указать время лучшего заезда (с учётом штрафов) и класс соревнования.<br>
        <b>Внимание!</b> Класс спортсмена, показавшего лучшее время должен совпадать с классом соревнования. Например,
        лучшее время на этапе
        показал спортсмен класса C1, но класс соревнования - C3. В таком случае, указывать необходимо результат
        спортсмена, показавшего лучшее время
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
			<?= Html::submitButton('Рассчитать эталонное время', ['class' => 'btn btn-my-style btn-aquamarine']) ?>
        </div>
    </div>
<?php $form->end(); ?>

<?php if ($model->referenceTime) { ?>
    <div class="calculate-result">
        Коеффициент для класса <?= $model->classModel->title ?>: <?= $model->coefficient ?><br>
        Эталонное время рассчитывается по формуле: время/коэффициент<br>
        <b>Эталонное время для указанных данных:</b> <?= $model->referenceTimeForHuman ?>
		<?php if ($needTime) { ?>
            <div class="pt-20">
                <b>Время, необходимое для повышения класса:</b>
                <table class="table">
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    Класс
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    Процент
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    Минимальное время
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    Максимальное время
                                </div>
                            </div>
                        </td>
                    </tr>
					<?php foreach ($needTime as $id => $data) {
						$cssClass = null;
						if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($data['classModel']->title, 'UTF-8')])) {
							$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($data['classModel']->title, 'UTF-8')];
						}
						?>
                        <tr class="result-<?= $cssClass ?>">
                            <td>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
										<?= $data['classModel']->title ?>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
										<?= $data['percent'] ?>%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-sm-6 col-xs-12">
										<?= $data['startTime'] ?>
                                    </div>
                                    <div class="col-sm-6 col-xs-12">
										<?= $data['endTime'] ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
					<?php } ?>
                </table>
            </div>
		<?php } ?>
    </div>
<?php } ?>