<?php
/**
 * @var \common\models\Stage               $stage
 * @var \common\models\Championship        $championship
 * @var \common\models\Participant|null    $participant
 * @var \common\models\TmpParticipant|null $tmpParticipant
 */
$text = '.';
?>
<?php if ($participant) {
    $athlete = $participant->athlete;
    if ($athlete->hasAccount) {
        $text = ', а так же придёт уведомление в личный кабинет.';
    }
} ?>
Предварительная регистрация на этап принята.
<?php if ($participant) {
	$athlete = $participant->athlete;
	?>
    В случае отклонения вашей заявки, будет отправлено соответствующее письмо на этот email и уведомление в личный кабинет.<br>
    При успешном подтверждении заявки - только уведомление.
<?php } else { ?>
    В случае отклонения вашей заявки, будет отправлено соответствующее письмо на этот email.<br>
    На сайте <a href="http://gymkhana-cup.ru">gymkhana-cup.ru</a> неподтверждённые заявки выделены серым цветом.
    Если ваша заявка другого цвета - значит, ваше участие подтверждено.
<?php } ?>
<br><br>
<b>Чемпионат:</b> <?= $championship->title ?><br>
<b>Этап:</b> <?= $stage->title ?><br>
<b>Участник:</b>
<?php if ($participant) { ?>
    <?= $athlete->getFullName() ?>
<?php } else { ?>
    <?= $tmpParticipant->lastName ?> <?= $tmpParticipant->firstName ?>
<?php } ?><br>
<b>Мотоцикл:</b>
<?php if ($participant) { ?>
    <?= $participant->motorcycle->getFullTitle() ?>
<?php } else { ?>
    <?= $tmpParticipant->motorcycleMark ?> <?= $tmpParticipant->motorcycleModel ?>
<?php } ?>
