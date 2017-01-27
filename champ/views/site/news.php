<?php
/**
 * @var \yii\web\View              $this
 * @var \common\models\AssocNews $news
 */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\bootstrap\Html;

?>

<h2><?= $news->title ?></h2>

<div class="z-100">
	<div class="detail-news">
		<div class="item">
			<div class="preview_text">
				<?= $news->fullText ? $news->fullText : $news->previewText ?>
			</div>
			<div class="date text-right"><?= \Yii::$app->formatter->asDate($news->datePublish, "dd.MM.Y") ?></div>
		</div>
	</div>
	
	<div class="text-left">
		<a href="/">К списку новостей</a>
	</div>
</div>