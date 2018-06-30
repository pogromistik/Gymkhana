<?php
/**
 * @var \common\models\Interview $interview
 */
$checked = false;
if (!\Yii::$app->user->isGuest) {
    $checked = $interview->getMyVote()->answerId;
}
?>

<div class="card-box votes">
	<?php foreach ($interview->interviewAnswers as $answer) { ?>
		<div class="item">
            <?php if ($checked === $answer->id) { ?>
                <span class="fa fa-check"></span>
            <?php } ?>
            <?= $answer->getText() ?> (<?= $answer->getVotesCount() ?>)
		</div>
	<?php } ?>
</div>
