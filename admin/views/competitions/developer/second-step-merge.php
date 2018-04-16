<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var \common\models\Athlete          $firstAthlete
 * @var \common\models\Athlete          $secondAthlete
 * @var \admin\models\MergeAthletesForm $formModel
 */
$i = 0;
?>

<?php if ($firstAthlete->lastName != $secondAthlete->lastName) { ?>
    <div class="alert alert-danger">
        <b>Обратите внимание! </b> Фамилии спортсменов различаются. Уверены, что выбрали их правильно?
    </div>
<?php } ?>

    <table class="table">
        <thead>
        <tr>
            <th></th>
            <th>Первый спортсмен</th>
            <th>Второй спортсмен</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><b>ID</b></td>
            <td><?= $firstAthlete->id ?></td>
            <td><?= $secondAthlete->id ?></td>
        </tr>
        <tr>
            <td><b>Имя</b></td>
            <td><?= $firstAthlete->firstName ?></td>
            <td><?= $secondAthlete->firstName ?></td>
        </tr>
        <tr>
            <td><b>Фамилия</b></td>
            <td><?= $firstAthlete->lastName ?></td>
            <td><?= $secondAthlete->lastName ?></td>
        </tr>
        <tr>
            <td><b>Город</b></td>
            <td><?= $firstAthlete->city->title ?></td>
            <td><?= $secondAthlete->city->title ?></td>
        </tr>
        <tr>
            <td><b>Номер</b></td>
            <td><?= $firstAthlete->number ?></td>
            <td><?= $secondAthlete->number ?></td>
        </tr>
        <tr>
            <td><b>Класс</b></td>
            <td><?= $firstAthlete->athleteClass->title ?></td>
            <td><?= $secondAthlete->athleteClass->title ?></td>
        </tr>
        <tr>
            <td><b>Мотоциклы</b></td>
            <td>
                <ul>
					<?php foreach ($firstAthlete->motorcycles as $motorcycle) { ?>
                        <li><?= $motorcycle->getFullTitle() ?> (<?= \common\models\Motorcycle::$statusesTitle[$motorcycle->status] ?>)</li>
					<?php } ?>
                </ul>
            </td>
            <td>
                <ul>
					<?php foreach ($secondAthlete->motorcycles as $motorcycle) { ?>
                        <li><?= $motorcycle->getFullTitle() ?> (<?= \common\models\Motorcycle::$statusesTitle[$motorcycle->status] ?>)</li>
					<?php } ?>
                </ul>
            </td>
        </tr>
        </tbody>
    </table>


<?php $form = ActiveForm::begin(['id' => 'secondStepMerge']); ?>
<?= $form->field($formModel, 'firstAthleteId')->hiddenInput()->label(false)->error(false) ?>
<?= $form->field($formModel, 'secondAthleteId')->hiddenInput()->label(false)->error(false) ?>
    <div class="motorcycles">
        <div class="row">
            <div class="col-md-5">
				<?= Html::dropDownList('firstMotorcycles[' . $i . ']', null, ArrayHelper::map($firstAthlete->motorcycles, 'id',
					function (\common\models\Motorcycle $item) {
						return $item->getFullTitle();
					}),
					[
						'class'  => 'form-control',
						'prompt' => 'Выберите мотоцикл для объединения'
					]) ?>
            </div>
            <div class="col-md-5">
				<?= Html::dropDownList('secondMotorcycles[' . $i . ']', null, ArrayHelper::map($secondAthlete->motorcycles, 'id',
					function (\common\models\Motorcycle $item) {
						return $item->getFullTitle();
					}),
					[
						'class'  => 'form-control',
						'prompt' => 'Выберите мотоцикл для объединения'
					]) ?>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
    <div class="pt-10 text-right">
        <a href="#" id="appendMotorcycle"
           class="btn btn-my-style btn-green"
           data-i=<?= $i ?>
           data-first-athlete-id=<?= $firstAthlete->id ?> data-second-athlete-id=<?= $secondAthlete->id ?>>
            Добавить ещё один мотоцикл</a>
    </div>

<div class="pt-10">
	<?= \yii\helpers\Html::submitButton('Далее', ['class' => 'btn btn-my-style btn-blue']) ?>
</div>

<?php $form->end(); ?>

<div id="checkStep"></div>
