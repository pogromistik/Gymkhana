<?php
/**
 * @var \common\models\SpecialStage $model
 * @var string                      $token
 * @var string                      $language
 */
$championship = $model->championship;
?>
<b>
	<?= \Yii::t('app', 'Начался приём результатов для этапа {champTitle}: "{stageTitle}"!', [
		'champTitle' => $championship->title,
		'stageTitle' => '<a href="http://gymkhana-cup.ru/competitions/special-stage?id="' . $model->id
			. 'target="_blank" style="color: #56a025">' . $model->title . '</a>'
	], $language) ?>
</b>

<br><?php if ($model->dateEnd) { ?>
	<?= \Yii::t('app', 'Успейте прислать результат до {date}!', ['date' => $model->dateEndHuman], $language) ?>
<?php } else { ?>
	<?= \Yii::t('app', 'Успейте прислать результат!', ['date' => $model->dateEndHuman], $language) ?>
<?php } ?>

<?php if ($model->dateResult) { ?>
    <br><?= \Yii::t('app', 'Результаты будут опубликованы {date}.', ['date' => $model->dateResultHuman], $language) ?>
<?php } ?>

<br><br>
<?php if ($model->photoPath) { ?>
	<?= \Yii::t('app', 'Подробнее о этапе вы можете узнать на сайте {site}. Там же можно посмотреть фото трассы.', [
		'site' => '<a href="http://gymkhana-cup.ru/competitions/special-stage?id=' . $model->id . '" target="_blank" style="color: #56a025">gymkhana-cup.ru</a>.&nbsp;'
	], $language) ?>
<?php } else { ?>
	<?= \Yii::t('app', 'Подробнее о этапе вы можете узнать на сайте {site}. Фотография трассы будет опубликована позже на том же сайте.', [
		'site' => '<a href="http://gymkhana-cup.ru/competitions/special-stage?id=' . $model->id . '" target="_blank" style="color: #56a025">gymkhana-cup.ru</a>.&nbsp;'
	], $language) ?>
<?php } ?>