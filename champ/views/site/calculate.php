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

?>

    <div class="card-box">
        <h1><?= $this->context->pageTitle ?></h1>
		<?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-sm-4">
				<?= $form->field($model, 'timeForHuman')->widget(MaskedInput::classname(), [
					'mask'    => '99:99.99',
					'options' => [
						'id'    => 'timeForHuman',
						'class' => 'form-control',
						'type'  => 'tel'
					]
				]) ?>
            </div>
            <div class="col-sm-4"><?= $form->field($model, 'class')->dropDownList(\yii\helpers\ArrayHelper::map(
					$classes, 'id', 'title'
				)) ?></div>
            <div class="col-sm-2">
                <label>&nbsp;</label><br>
				<?= Html::submitButton(\Yii::t('app', 'Рассчитать'), ['class' => 'btn btn-green']) ?>
            </div>
        </div>
		<?php $form->end(); ?>
		
		<?php if ($model->referenceTime) { ?>
            <div>
                <?= \Yii::t('app', 'Коэффициент для класса {class}', [
                        'class' => $model->classModel->title
                ]) ?>: <?= $model->coefficient ?><br>
                <?= \Yii::t('app', 'Эталонное время рассчитывается по формуле: время/коэффициент') ?><br>
                <b><?= \Yii::t('app', 'Эталонное время для указанных данных') ?>:</b> <?= $model->referenceTimeForHuman ?>
            </div>
		<?php } ?>
    </div>

<?php if ($model->referenceTime) { ?>
    <div class="calculate-result">
		<?php if ($needTime) { ?>
            <div class="card-box">
                <b><?= \Yii::t('app', 'Время, необходимое для повышения класса:') ?></b>
                <table class="table">
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <?= \Yii::t('app', 'Класс') ?>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <?= \Yii::t('app', 'Рейтинг') ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <?= \Yii::t('app', 'Минимальное время') ?>
                                </div>
                                <div class="col-sm-6 col-xs-12">
                                    <?= \Yii::t('app', 'Максимальное время') ?>
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