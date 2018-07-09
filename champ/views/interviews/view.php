<?php
/**
 * @var \common\models\Interview $interview
 * @var \yii\web\View            $this
 */
?>

    <div class="card-box">
        <h3><?= $interview->getTitle() ?></h3>
        <div>
			<?= $interview->getDescription() ?>
        </div>
    </div>

    <h4><?= \Yii::t('app', 'Варианты:') ?></h4>
<?php if ($interview->onlyPictures) { ?>
    <div class="tracks-gallery stages">
        <div class="row">
			<?php foreach ($interview->interviewAnswers as $answer) { ?>
                <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 item">
                    <div class="img">
                        <figure class="effect-julia">
							<?= yii\helpers\Html::a(\yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $answer->imgPath),
								\Yii::getAlias('@filesView') . '/' . $answer->imgPath,
								['data-fancybox' => 'gallery']) ?>
                        </figure>
                        <figure class="effect-julia">
							<?= yii\helpers\Html::a(\yii\bootstrap\Html::img(\Yii::getAlias('@filesView') . '/' . $answer->imgPath),
								\Yii::getAlias('@filesView') . '/' . $answer->imgPath) ?>
                        </figure>
                    </div>
                    <div class="info">
                        <div><?= $answer->getText() ?></div>
                    </div>
                </div>
			<?php } ?>
        </div>
    </div>
<?php } ?>

    <h3><?= \Yii::t('app', 'Голосование') ?></h3>
<?php
$totalCount = $interview->getTotalVotes();
if ($totalCount > 0) { ?>
	<?= \Yii::t('app', 'Количество проголосовавших: {count}', ['count' => $totalCount]) ?>
<?php } ?>
<?php if ($interview->dateEnd < time()) {
	echo $this->render('_results', ['interview' => $interview]);
} else { ?>
	<?php if (\Yii::$app->user->isGuest) { ?>
        <p><?= \Yii::t('app', 'Голосовать могут только авторизованные пользователи. Чтобы проголосовать, {login} или {signup}.',
				[
					'login'  => \yii\helpers\Html::a(\Yii::t('app', 'войдите в личный кабинет'), ['/site/login']),
					'signup' => \yii\helpers\Html::a(\Yii::t('app', 'зарегистрируйтесь'), ['/registration'])
				]) ?></p>
        <p>
			<?= \Yii::t('app', 'Или вы можете прислать свой голос одним из указанных способов:') ?>
            <a href="mailto:gymkhana.cup@gmail.com">gymkhana.cup@gmail.com</a><br>
            <a href="https://vk.com/id19792817">https://vk.com/id19792817</a><br>
            <a href="#" data-toggle="modal"
               data-target="#feedbackForm"><?= \Yii::t('app', 'форма обратной связи') ?></a>
        </p>
	<?php } else {
		if ($interview->getMyVote()) {
			echo $this->render('_results', ['interview' => $interview]);
		} else {
			echo $this->render('_votes', ['interview' => $interview]);
		}
	} ?>
<?php } ?>