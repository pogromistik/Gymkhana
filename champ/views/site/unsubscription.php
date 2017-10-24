<?php
/**
 * @var string $error
 */
?>

<div class="pt-20">
	<?php if ($error) { ?>
		<?= $error ?>. Для решения проблемы свяжитесь, пожалуйста, с
        <a href="#" data-toggle="modal" data-target="#feedbackForm">администрацией сайта</a>.
	<?php } else { ?>
        Вы успешно отписались от новостной рассылки. Если вам всё равно продолжают приходить наши письма - пожалуйста, свяжитесь с
        <a href="#" data-toggle="modal" data-target="#feedbackForm">администрацией сайта</a>.
	<?php } ?>
</div>
