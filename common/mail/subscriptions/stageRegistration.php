<?php
/**
 * @var \common\models\Stage $model
 */
$championship = $model->championship;
?>
<b>Открыта регистрация на этап <?= $championship->title ?>: "<?= $model->title ?>"!</b>

<?php if ($model->dateOfThe) { ?>
    <br>Этап пройдёт <?= $model->dateOfTheHuman ?>.&nbsp;
<?php } ?>

<?php if ($model->location) { ?>
    Место проведения: <?= $model->location ?>.
<?php } ?>

<?php if ($model->participantsLimit) { ?>
    <br><b>Обратите
        внимание</b> - количество участников для этапа ограничено числом <?= $model->participantsLimit ?>.
<?php } ?>

<br><?php if ($model->endRegistration) { ?>
    Успейте зарегистрироваться до <?= $model->dateOfTheHuman ?>!
<?php } else { ?>
    Успейте зарегистрироваться!
<?php } ?>


<?php if ($championship->onlyRegions) { ?>
    <br>
    <small>Этап проводится в рамках закрытого чемпионата, к участию допускаются только перечисленные
        регионы: <?= $championship->getRegionsFor(true) ?></small>
<?php } ?>

<br><br>
Подробнее о этапе вы можете узнать на сайте <a href="//gymkhana-cup.ru/competitions/stage?id=<?= $model->id ?>">gymkhana-cup.ru</a>

