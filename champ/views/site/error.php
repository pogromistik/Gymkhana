<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;

$statusCode = 404;
$text = '';
$title = 'not found';
if ($exception && isset($exception->statusCode)) {
    $statusCode = $exception->statusCode;
	switch ($exception->statusCode) {
		case 404:
			$title = 'not found';
			$text = 'Монстр в недоумении, потому что не может найти нужную вам страницу. Но вы всегда можете ' .
				'<a href="/">вернуться на главную</a> или
<a href="#" data-toggle="modal" data-target="#feedbackForm">сообщить нам об ошибке</a>.';
			break;
		case 403:
			$title = 'forbidden';
			$text = 'Монстр расстроен, потому что вы пытаетесь зайти на запретную страницу. Но вы всегда можете ' .
				'<a href="/">вернуться на главную</a> или
<a href="#" data-toggle="modal" data-target="#feedbackForm">сообщить нам об ошибке</a>.';
			break;
		default:
			$title = 'not found';
	}
}
?>
<div class="site-error">
    <div class="text pt-20 text-center">
		<?= $text ?>
    </div>
    <div class="errors">
        <div class="img">
			<?= Html::img('/img/404.png') ?>
        </div>
        <div class="text">
            <div class="code"><?= $statusCode ?></div>
            <div class="message"><?= $title ?></div>
        </div>
    </div>

</div>
