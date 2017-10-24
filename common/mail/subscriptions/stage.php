<?php
/**
 * @var \common\models\Stage $stage
 */
$championship = $stage->championship;
?>
<b>Анонсирован новый этап чемпионата <?= $championship->title ?>: "<?= $stage->title ?>"!</b>
<br><br>

<?php if ($stage->description) { ?>
	<?= $stage->description ?><br>
<?php } ?>

<?php if ($stage->dateOfThe) { ?>
    <br>Этап пройдёт <?= $stage->dateOfTheHuman ?>.&nbsp;
<?php } ?>

<?php if ($stage->location) { ?>
    Место проведения: <?= $stage->location ?>.
<?php } ?>

<?php if ($stage->startRegistration) { ?>
    <br>Регистрация начнётся <?= $stage->dateOfTheHuman ?>.
<?php } ?>

<?php if ($stage->participantsLimit) { ?>
    <br><b>Обратите
        внимание</b> - количество участников для этапа ограничено числом <?= $stage->participantsLimit ?>. Успейте зарегистрироваться!
<?php } ?>

<?php if ($championship->onlyRegions) { ?>
    <br>
    <small>Этап проводится в рамках закрытого чемпионата, к участию допускаются только перечисленные
        регионы: <?= $championship->getRegionsFor(true) ?></small>
<?php } ?>

<br><br>
Подробнее о этапе вы можете узнать на сайте <a href="//gymkhana-cup.ru/competitions/stage?id=<?= $stage->id ?>">gymkhana-cup.ru</a>

