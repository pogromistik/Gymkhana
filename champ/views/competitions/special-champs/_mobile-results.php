<?php
/**
 * @var \common\models\RequestForSpecialStage[] $participants
 */
$place = 1;
?>

<div class="show-mobile">
    <table class="table results results-with-img">
        <thead>
        <tr>
            <th>Место вне класса /<br>Место в классе</th>
            <th>Участник</th>
            <th>Время</th>
            <th>Рейтинг</th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($participants as $participant) {
			$athlete = $participant->athlete;
			?>
            <tr>
                <td>
                    <?= $place++ ?> /
                    <br>
                    <a href="<?= $participant->videoLink ?>" class="big-icon"><span class="fa fa-youtube"></span></a>
                </td>
                <td>
					<?= \yii\helpers\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                    <br>
	                <?= $athlete->city->title ?>
                    <br>
	                <?= $participant->motorcycle->getFullTitle() ?>
                    <br>
	                <?= $participant->athleteClass->title ?>
                </td>
                <td><?= \yii\helpers\Html::a($participant->resultTimeHuman, ['athlete-progress', 'id' => $participant->id]) ?></td>
                <td><?= $participant->percent ?></td>
            </tr>
		<?php } ?>
        </tbody>
    </table>
</div>
