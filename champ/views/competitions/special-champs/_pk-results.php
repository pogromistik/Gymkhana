<?php
/**
 * @var \common\models\RequestForSpecialStage[] $participants
 */
$id = null;
if (!\Yii::$app->user->isGuest) {
	$id = \Yii::$app->user->id;
}
?>

<div class="show-pk">
    <table class="table results results-with-img">
        <thead>
        <tr>
            <th><img src="/img/table/placeWithoutClass.png"></th>
            <th><img src="/img/table/class.png"></th>
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
			$class = 'default';
			if ($id && $id == $athlete->id) {
				$class = 'my-row';
			}
			?>
            <tr class="<?= $class ?>">
                <td><?= $participant->place ?></td>
                <td><?= $participant->athleteClass->title ?></td>
                <td>
					<?= \yii\helpers\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                    <br>
					<?= $athlete->city->title ?>
                </td>
                <td><?= $participant->motorcycle->getFullTitle() ?></td>
                <td><?= \yii\helpers\Html::a($participant->timeHuman, ['athlete-progress', 'id' => $participant->id]) ?></td>
                <td><?= $participant->fine ?></td>
                <td><?= \yii\helpers\Html::a($participant->resultTimeHuman, ['athlete-progress', 'id' => $participant->id]) ?></td>
                <td>
					<?= $participant->percent ? $participant->percent . '%' : '' ?>
					<?php if ($participant->newAthleteClassId
						&& $participant->newAthleteClassStatus == \common\models\RequestForSpecialStage::STATUS_APPROVE
					) { ?>
                        &nbsp;(<?= $participant->newAthleteClass->title ?>)
					<?php } ?>
                </td>
                <th><a href="<?= $participant->videoLink ?>" class="big-icon"><span class="fa fa-youtube"></span></a>
                </th>
            </tr>
		<?php } ?>
        </tbody>
    </table>
</div>
