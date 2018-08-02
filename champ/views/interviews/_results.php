<?php
/**
 * @var \common\models\Interview $interview
 */
$checked = false;
if (!\Yii::$app->user->isGuest) {
	$myVote = $interview->getMyVote();
	if ($myVote) {
		$checked = $myVote->answerId;
	}
}
?>

<div class="card-box">
    <div class="votes">
		<?php foreach ($interview->interviewAnswers as $answer) {
			$votesCount = $answer->getVotesCount();
			?>
            <div class="vote">
                <div class="item">
                    <div class="text">
						<?php if ($checked === $answer->id) { ?>
                            <span class="fa fa-check"></span>
						<?php } ?>
						<?= $answer->getText() ?> (<?= $votesCount ?>)
                    </div>
					<?php
					$percent = 0;
					if ($votesCount > 0) {
						$votesCount = round(($votesCount / $interview->getTotalVotes()) * 100, 0);
					} ?>
                    <div class="percent" style="width: <?= $votesCount ?>%"></div>
                </div>
                <div class="answer-votes">
					<?php
					$athletes = [];
					foreach ($answer->votes as $vote) {
						$athletes[] = $vote->athlete->getFullName();
					} ?>
					<?= implode(', ', $athletes) ?>
                </div>
            </div>
		<?php } ?>
    </div>
</div>
