<?php
/**
 * @var \common\models\Interview $interview
 */
?>

<div class="card-box answers">
    <p><?= \Yii::t('app', 'Чтобы проголосовать, нажмите на строку с нужным вариантом. ОБРАТИТЕ ВНИМАНИЕ! Голос нельзя отменить или изменить. Проголосовать можно только один раз.') ?></p>
    <p>
	    <?= \Yii::t('app', 'Если у вас не получается проголосовать, свяжитесь с нами:') ?>
        <br>
        <a href="mailto:gymkhana.cup@gmail.com">gymkhana.cup@gmail.com</a><br>
        <a href="https://vk.com/id19792817">https://vk.com/id19792817</a><br>
        <a href="#" data-toggle="modal"
           data-target="#feedbackForm"><?= \Yii::t('app', 'форма обратной связи') ?></a>
    </p>
    <div class="alert alert-danger" style="display: none;"></div>
	<?php foreach ($interview->interviewAnswers as $answer) { ?>
        <div class="item">
			<?= \yii\helpers\Html::a($answer->getText(), 'javascript:;', [
				'class'          => 'addVote',
				'data-interview' => $interview->id,
				'data-answer'    => $answer->id]) ?>
        </div>
	<?php } ?>
</div>
