<?php
/**
 * @var \common\models\SpecialStage $model
 * @var string                      $token
 */
$championship = $model->championship;
?>
<b>Анонсирован новый этап чемпионата
    <a href="//gymkhana-cup.ru/competitions/special-stage?id=<?= $model->id ?>"
       target="_blank" style="color: #56a025"><?= $championship->title ?>: "<?= $model->title ?>"</a>!</b>
<br><br>

<?php if ($model->description) { ?>
	<?= $model->description ?><br>
<?php } ?>

<?php if ($model->dateStart) { ?>
    <br>Приём результатов начнётся <?= $model->dateStart ?>.
<?php } ?>

<?php if ($model->dateResult) { ?>
    <br>Результаты будут опубликованы <?= $model->dateResultHuman ?>.&nbsp;
<?php } ?>

<br><br>
Подробнее о этаеп вы можете узнать на сайте
<a href="//gymkhana-cup.ru/competitions/special-stage?id=<?= $model->id ?>" target="_blank" style="color: #56a025">gymkhana-cup.ru</a>.&nbsp;
<?php if ($model->photoPath) { ?>
    Там же можно посмотреть фото трассы.
<?php } else { ?>
    Фотография трассы будет опубликована позже на том же сайте.
<?php } ?>