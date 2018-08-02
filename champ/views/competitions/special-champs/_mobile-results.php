<?php
/**
 * @var \common\models\RequestForSpecialStage[] $participants
 * @var array                                   $tmpPlaces
 */
$id = null;
if (!\Yii::$app->user->isGuest) {
	$id = \Yii::$app->user->id;
}
$lang = \Yii::$app->language;
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
			$oldClassTitle = $participant->athleteClass->title;
			if (isset(\common\models\Athlete::$classesCss[mb_strtoupper($oldClassTitle, 'UTF-8')])) {
				$cssClass = \common\models\Athlete::$classesCss[mb_strtoupper($oldClassTitle, 'UTF-8')];
				$class .= " result-{$cssClass}";
			}
			?>
            <tr class="<?= $class ?>">
                <td>
					<?= $participant->place ?? $tmpPlaces[$participant->athleteId] ?? '' ?>
                </td>
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
                    <br>
					<?= $participant->motorcycle->getFullTitle() ?>
                    <br>
					<?= $oldClassTitle ?>
                </td>
                <td><?= \yii\helpers\Html::a($participant->resultTimeHuman, ['athlete-progress', 'id' => $participant->id]) ?>
                    &nbsp;
                    <a href="<?= $participant->videoLink ?>" class="big-icon"><span class="fa fa-youtube"></span></a>
                </td>
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
            </tr>
		<?php } ?>
        </tbody>
    </table>
</div>
