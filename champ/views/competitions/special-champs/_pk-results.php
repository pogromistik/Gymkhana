<?php
/**
 * @var \common\models\RequestForSpecialStage[] $participants
 * @var array                                   $tmpPlaces
 */
$id = null;
if (!\Yii::$app->user->isGuest) {
	$id = \Yii::$app->user->id;
}
$path = '/img/table/';
$lang = \Yii::$app->language;
if (\Yii::$app->language != \common\models\TranslateMessage::LANGUAGE_RU) {
	$path = '/img/table/en/';
}
?>

<div class="show-pk">
    <table class="table results results-with-img">
        <thead>
        <tr>
            <th><img src="<?= $path ?>placeWithoutClass.png"></th>
            <th><img src="<?= $path ?>class.png"></th>
            <th><img src="<?= $path ?>participant.png"></th>
            <th><img src="<?= $path ?>motorcycle.png"></th>
            <th><img src="<?= $path ?>time.png"></th>
            <th><img src="<?= $path ?>fine.png"></th>
            <th><img src="<?= $path ?>resultTime.png"></th>
            <th><img src="<?= $path ?>percent.png"></th>
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
			$oldClassTitle = $participant->athleteClass->title;
			if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($oldClassTitle, 'UTF-8')])) {
				$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($oldClassTitle, 'UTF-8')];
				$class .= " result-{$cssClass}";
			}
			?>
            <tr class="<?= $class ?>">
                <td><?= $participant->place ?? $tmpPlaces[$participant->athleteId] ?? '' ?></td>
                <td><?= $oldClassTitle ?></td>
                <td>
					<?= \yii\helpers\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                    <br>
					<?php
					if ($flagInfo = \common\models\HelpModel::getFlagInfo($participant->countryId, $lang)) {
						?>
						<?= \yii\helpers\Html::img('/img/flags/' . $flagInfo['flag'], [
							'alt'   => $flagInfo['title'],
							'title' => $flagInfo['title']
						]) ?>
						<?php
					}
					?>
					<?= \common\helpers\TranslitHelper::translitCity($athlete->city->title) ?>
                </td>
                <td><?= $participant->motorcycle->getFullTitle() ?></td>
                <td><?= \yii\helpers\Html::a($participant->timeHuman, ['athlete-progress', 'id' => $participant->id]) ?></td>
                <td><?= $participant->fine ?></td>
                <td><?= \yii\helpers\Html::a($participant->resultTimeHuman, ['athlete-progress', 'id' => $participant->id]) ?></td>
				<?php if ($participant->newAthleteClassId
					&& $participant->newAthleteClassStatus == \common\models\RequestForSpecialStage::STATUS_APPROVE
				) { ?>
                    <td class="result-<?= \common\models\Athlete::$classesCss[$participant->newAthleteClass->title] ?>">
						<?= $participant->percent ? $participant->percent . '%' : '' ?>
                        &nbsp;(<?= $participant->newAthleteClass->title ?>)
                    </td>
				<?php } else { ?>
                    <td class="result-default">
						<?= $participant->percent ? $participant->percent . '%' : '' ?>
                    </td>
				<?php } ?>
                <th><a href="<?= $participant->videoLink ?>" class="big-icon"><span class="fa fa-youtube"></span></a>
                </th>
            </tr>
		<?php } ?>
        </tbody>
    </table>
</div>
