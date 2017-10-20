<?php
/**
 * @var \common\models\RequestForSpecialStage[] $participants
 */
$id = null;
if (!\Yii::$app->user->isGuest) {
	$id = \Yii::$app->user->id;
}
?>

<div class="show-mobile">
    <table class="table results results-with-img">
        <thead>
        <tr>
            <th><?= \Yii::t('app', 'Место') ?></th>
            <th><?= \Yii::t('app', 'Участник') ?></th>
            <th><?= \Yii::t('app', 'Время') ?></th>
            <th><?= \Yii::t('app', 'Рейтинг') ?></th>
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
                <td>
					<?= $participant->place ?>
                </td>
                <td>
					<?= \yii\helpers\Html::a($athlete->getFullName(), ['/athletes/view', 'id' => $athlete->id]) ?>
                    <br>
					<?= \common\helpers\TranslitHelper::translitCity($athlete->city->title) ?>
                    <br>
					<?= $participant->motorcycle->getFullTitle() ?>
                    <br>
					<?= $participant->athleteClass->title ?>
                </td>
                <td><?= \yii\helpers\Html::a($participant->resultTimeHuman, ['athlete-progress', 'id' => $participant->id]) ?>
                    &nbsp;
                    <a href="<?= $participant->videoLink ?>" class="big-icon"><span class="fa fa-youtube"></span></a>
                </td>
                <td>
					<?= $participant->percent ? $participant->percent . '%' : '' ?>
					<?php if ($participant->newAthleteClassId
						&& $participant->newAthleteClassStatus == \common\models\RequestForSpecialStage::STATUS_APPROVE
					) { ?>
                        &nbsp;(<?= $participant->newAthleteClass->title ?>)
					<?php } ?>
                </td>
            </tr>
		<?php } ?>
        </tbody>
    </table>
</div>
