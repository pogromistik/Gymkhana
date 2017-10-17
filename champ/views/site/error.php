<?php

/* @var $this yii\web\View */
/* @var $text string */
/* @var $title string */
/* @var $statusCode integer */

use yii\helpers\Html;

$this->title = $name;
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
