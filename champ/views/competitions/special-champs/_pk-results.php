<?php
/**
 * @var \common\models\RequestForSpecialStage[] $participants
 */
$place = 1;
?>

<div class="show-pk">
    <table class="table results results-with-img">
        <thead>
        <tr>
            <th><img src="/img/table/place.png"></th>
            <th><img src="/img/table/class.png"></th>
            <th><img src="/img/table/placeInClass.png"></th>
            <th><img src="/img/table/participant.png"></th>
            <th><img src="/img/table/motorcycle.png"></th>
            <th><img src="/img/table/time.png"></th>
            <th><img src="/img/table/fine.png"></th>
            <th><img src="/img/table/resultTime.png"></th>
            <th><img src="/img/table/percent.png"></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
		<?php foreach ($participants as $participant) {
			$athlete = $participant->athlete;
			?>
            <tr>
                <td><?= $place++ ?></td>
                <td><?= $participant->athleteClass->title ?></td>
                <td></td>
                <td>
					<?= \yii\helpers\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                    <br>
					<?= $athlete->city->title ?>
                </td>
                <td><?= $participant->motorcycle->getFullTitle() ?></td>
                <td><?= \yii\helpers\Html::a($participant->timeHuman, ['athlete-progress', 'id' => $participant->id]) ?></td>
                <td><?= $participant->fine ?></td>
                <td><?= \yii\helpers\Html::a($participant->resultTimeHuman, ['athlete-progress', 'id' => $participant->id]) ?></td>
                <td><?= $participant->percent ?></td>
                <th><a href="<?= $participant->videoLink ?>" class="big-icon"><span class="fa fa-youtube"></span></a></th>
            </tr>
		<?php } ?>
        </tbody>
    </table>
</div>
