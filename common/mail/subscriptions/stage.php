<?php
/**
 * @var \common\models\Stage $model
 * @var string               $token
 */
$championship = $model->championship;
?>
<b>Анонсирован новый этап чемпионата
    <a href="http://gymkhana-cup.ru/competitions/stage?id=<?= $model->id ?>" target="_blank" style="color: #56a025"><?= $championship->title ?>:
        "<?= $model->title ?>"</a>!</b>
<br><br>

<?php if ($model->description) { ?>
	<?= $model->description ?><br>
<?php } ?>

<?php if ($model->dateOfThe) { ?>
    <br>Этап пройдёт <?= $model->dateOfTheHuman ?>.&nbsp;
<?php } ?>

<?php if ($model->location) { ?>
    Место проведения: <?= $model->location ?>.
<?php } ?>

<?php if ($model->startRegistration) { ?>
    <br>Регистрация начнётся <?= $model->dateOfTheHuman ?>.
<?php } ?>

<?php if ($model->participantsLimit) { ?>
    <br><b>Обратите
        внимание</b> - количество участников для этапа ограничено числом <?= $model->participantsLimit ?>. Успейте зарегистрироваться!
<?php } ?>

<?php if ($championship->onlyRegions) { ?>
    <br>
    <small>Этап проводится в рамках закрытого чемпионата, к участию допускаются только перечисленные
        регионы: <?= $championship->getRegionsFor(true) ?></small>
<?php } ?>

<br><br>
Подробнее о этапе вы можете узнать на сайте <a href="http://gymkhana-cup.ru/competitions/stage?id=<?= $model->id ?>"
                                               target="_blank" style="color: #56a025">gymkhana-cup.ru</a>

