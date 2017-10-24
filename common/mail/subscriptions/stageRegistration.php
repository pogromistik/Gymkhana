<?php
/**
 * @var \common\models\Stage $stage
 */
$championship = $stage->championship;
?>
<b>Открыта регистрация на этап <?= $championship->title ?>: "<?= $stage->title ?>"!</b>

<?php if ($stage->dateOfThe) { ?>
    <br>Этап пройдёт <?= $stage->dateOfTheHuman ?>.&nbsp;
<?php } ?>

<?php if ($stage->location) { ?>
    Место проведения: <?= $stage->location ?>.
<?php } ?>

<?php if ($stage->participantsLimit) { ?>
    <br><b>Обратите
        внимание</b> - количество участников для этапа ограничено числом <?= $stage->participantsLimit ?>.
<?php } ?>

<br><?php if ($stage->endRegistration) { ?>
    Успейте зарегистрироваться до <?= $stage->dateOfTheHuman ?>!
<?php } else { ?>
    Успейте зарегистрироваться!
<?php } ?>


<?php if ($championship->onlyRegions) { ?>
    <br>
    <small>Этап проводится в рамках закрытого чемпионата, к участию допускаются только перечисленные
        регионы: <?= $championship->getRegionsFor(true) ?></small>
<?php } ?>

<br><br>
Подробнее о этапе вы можете узнать на сайте <a href="//gymkhana-cup.ru/competitions/stage?id=<?= $stage->id ?>">gymkhana-cup.ru</a>

