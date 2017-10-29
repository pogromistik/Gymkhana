<?php
/**
 * @var \common\models\Stage $model
 * @var string               $token
 * @var string               $language
 */
$championship = $model->championship;
?>
<b>
	<?= \Yii::t('app', 'Анонсирован новый этап чемпионата {champTitle}: "{stageTitle}"!', [
		'champTitle' => $championship->title,
		'stageTitle' => '<a href="http://gymkhana-cup.ru/competitions/special-stage?id="' . $model->id
			. 'target="_blank" style="color: #56a025">' . $model->title . '</a>'
	], $language) ?>
</b>

<?php if ($model->description) { ?>
    <br><?= $model->description ?>
<?php } ?>

<?php if ($model->dateOfThe) { ?>
    <br><?= \Yii::t('app', 'Этап пройдёт {date}.', ['date' => $model->dateOfTheHuman], $language) ?>
<?php } ?>

<?php if ($model->location) { ?>
    &nbsp;<?= \Yii::t('app', 'Место проведения: {location}.', ['location' => $model->location], $language) ?>
<?php } ?>

<?php if ($model->startRegistration) { ?>
    <br><?= \Yii::t('app', '>Регистрация начнётся {date}.', ['date' => $model->dateOfTheHuman], $language) ?>
<?php } ?>

<?php if ($model->participantsLimit) { ?>
    <br><b><?= \Yii::t('app', 'Обратите внимание', [], $language) ?></b>
    - <?= \Yii::t('app', 'количество участников для этапа ограничено числом {count}. Успейте зарегистрироваться!',
        ['count' => $model->participantsLimit], $language) ?>
<?php } ?>

<?php if ($championship->onlyRegions) { ?>
    <br>
    <small><?= \Yii::t('app', 'Этап проводится в рамках закрытого чемпионата, к участию допускаются только перечисленные регионы: {regions}.', [
                'regions' => $championship->getRegionsFor(true)
        ], $language) ?></small>
<?php } ?>

<br><br>
<?= \Yii::t('app', 'Подробнее о этапе вы можете узнать на сайте {site}.', [
	'site' => '<a href="http://gymkhana-cup.ru/competitions/stage?id=' . $model->id . '" target="_blank" style="color: #56a025">gymkhana-cup.ru</a>.&nbsp;'
], $language) ?>

