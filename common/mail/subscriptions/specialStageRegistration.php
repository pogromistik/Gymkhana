<?php
/**
 * @var \common\models\SpecialStage $model
 */
$championship = $model->championship;
?>
<b>Начался приём результатов для этапа <?= $championship->title ?>: "<?= $model->title ?>"!</b>

<br><?php if ($model->dateEnd) { ?>
	Успейте прислать результат до <?= $model->dateEndHuman ?>!
<?php } else { ?>
	Успейте прислать результат!
<?php } ?>

<?php if ($model->dateResult) { ?>
	<br>Результаты будут опубликованы <?= $model->dateResultHuman ?>.&nbsp;
<?php } ?>

<br><br>
Подробнее о этаеп вы можете узнать на сайте
<a href="//gymkhana-cup.ru/competitions/special-stage?id=<?= $model->id ?>">gymkhana-cup.ru</a>.&nbsp;
<?php if ($model->photoPath) { ?>
	Там же можно посмотреть фото трассы.
<?php } else { ?>
	Фотография трассы будет опубликована позже на том же сайте.
<?php } ?>
