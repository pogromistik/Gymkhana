<?php
/**
 * @var string $error
 */
?>

<div class="pt-20">
	<?php if ($error) { ?>
		<?= $error ?> <a href="#" data-toggle="modal" data-target="#feedbackForm">
			<?= \Yii::t('app', 'Для решения проблемы свяжитесь, пожалуйста, с администрацией сайта') ?>
        </a>.
	<?php } else { ?>
		<?= \Yii::t('app', 'Вы успешно отписались от новостной рассылки.') ?> <a href="#" data-toggle="modal"
                                                                                 data-target="#feedbackForm">
			<?= \Yii::t('app', 'Если вам всё равно продолжают приходить наши письма - пожалуйста, свяжитесь с администрацией сайта') ?></a>.
	<?php } ?>
</div>
