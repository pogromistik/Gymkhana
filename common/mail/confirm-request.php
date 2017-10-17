<?php
/**
 * @var \common\models\Stage               $stage
 * @var \common\models\Championship        $championship
 * @var \common\models\Participant|null    $participant
 * @var \common\models\TmpParticipant|null $tmpParticipant
 */
?>
<?= \Yii::t('app', 'Предварительная регистрация на этап принята.') ?><br>
<?php if ($participant) {
	$athlete = $participant->athlete;
	?>
	<?= \Yii::t('app', 'В случае отклонения вашей заявки, вам будет отправлено соответствующее письмо на этот email и уведомление в личный кабинет. При успешном подтверждении заявки - только уведомление.') ?>
<?php } else { ?>
	<?= \Yii::t('app', 'В случае отклонения вашей заявки, будет отправлено соответствующее письмо на этот email.') ?>
    <br>
	<?= \Yii::t('app', 'На сайте {site} неподтверждённые заявки выделены серым цветом. Если ваша заявка другого цвета - значит, ваше участие подтверждено.',
		['site' => '<a href="http://gymkhana-cup.ru" target="_blank">gymkhana-cup.ru</a>']) ?>
<?php } ?>
<br><br>
<b><?= \Yii::t('app', 'Чемпионат') ?>:</b> <?= $championship->title ?><br>
<b><?= \Yii::t('app', 'Этап') ?>:</b> <?= $stage->title ?><br>
<b><?= \Yii::t('app', 'Участник') ?>:</b>
<?php if ($participant) { ?>
	<?= $athlete->getFullName() ?>
<?php } else { ?>
	<?= $tmpParticipant->lastName ?> <?= $tmpParticipant->firstName ?>
<?php } ?><br>
<b><?= \Yii::t('app', 'Мотоцикл') ?>:</b>
<?php if ($participant) { ?>
	<?= $participant->motorcycle->getFullTitle() ?>
<?php } else { ?>
	<?= $tmpParticipant->motorcycleMark ?> <?= $tmpParticipant->motorcycleModel ?>
<?php } ?>
