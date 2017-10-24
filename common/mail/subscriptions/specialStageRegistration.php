<?php
/**
 * @var \common\models\SpecialStage $stage
 */
$championship = $stage->championship;
?>
<b>Начался приём результатов для этапа <?= $championship->title ?>: "<?= $stage->title ?>"!</b>

<br><?php if ($stage->dateEnd) { ?>
	Успейте прислать результат до <?= $stage->dateEndHuman ?>!
<?php } else { ?>
	Успейте прислать результат!
<?php } ?>

<?php if ($stage->dateResult) { ?>
	<br>Результаты будут опубликованы <?= $stage->dateResultHuman ?>.&nbsp;
<?php } ?>

<br><br>
Подробнее о этаеп вы можете узнать на сайте
<a href="//gymkhana-cup.ru/competitions/special-stage?id=<?= $stage->id ?>">gymkhana-cup.ru</a>.&nbsp;
<?php if ($stage->photoPath) { ?>
	Там же можно посмотреть фото трассы.
<?php } else { ?>
	Фотография трассы будет опубликована позже на том же сайте.
<?php } ?>
