<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var \admin\models\MergeAthletesForm $formModel
 * @var \common\models\Athlete          $firstAthlete
 * @var \common\models\Athlete          $secondAthlete
 * @var array                           $motorcyclesForMerge
 * @var array                           $otherMotorcycles
 * @var \common\models\Motorcycle       $motorcycle
 */
?>

    <div class="alert alert-warning">
        <b>Внимательно проверьте данные.</b> Вместо двух спортсменов будет создан один:
    </div>

    <div>
        <ul>
            <li><?= $firstAthlete->getFullName() ?></li>
            <li><?= $firstAthlete->city->title ?></li>
			<?php if ($formModel->number) { ?>
                <li>№<?= $formModel->number ?></li>
			<?php } ?>
            <li>Класс: <?= $formModel->resultClass->title ?></li>
            <li>
                Мотоциклы:
                <ol>
					<?php foreach ($otherMotorcycles as $motorcycle) { ?>
                        <li><?= $motorcycle->getFullTitle() ?>  (<?= \common\models\Motorcycle::$statusesTitle[$motorcycle->status] ?>)</li>
					<?php } ?>
					<?php foreach ($motorcyclesForMerge as $item) { ?>
                        <li><?= $item['first']->getFullTitle() ?> <span
                                    class="fa fa-arrows-h"></span> <?= $item['second']->getFullTitle() ?>&nbsp;
                            (<?= \common\models\Motorcycle::$statusesTitle[$item['first']->status] ?>)</li>
					<?php } ?>
                </ol>
            </li>
        </ul>
    </div>

<?php $form = ActiveForm::begin(['id' => 'confirmMerge']); ?>
<?= $form->field($formModel, 'firstAthleteId')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($formModel, 'secondAthleteId')->hiddenInput()->label(false)->error(false) ?>
<?php $i = 0; ?>
<?php foreach ($motorcyclesForMerge as $item) { ?>
	<?= Html::hiddenInput('firstMotorcycles[' . $i . ']', $item['first']->id) ?>
	<?= Html::hiddenInput('secondMotorcycles[' . $i . ']', $item['second']->id) ?>
<?php } ?>

    <div class="pt-10">
		<?= \yii\helpers\Html::submitButton('Объединить', ['class' => 'btn btn-my-style btn-red']) ?>
    </div>

<?php $form->end(); ?>