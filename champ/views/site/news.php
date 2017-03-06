<?php
/**
 * @var \yii\web\View              $this
 * @var \common\models\AssocNews $news
 */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\bootstrap\Html;

?>

<h3><?= $news->title ?></h3>

<div class="z-100">
	<div class="detail-news">
		<div class="item">
			<div class="preview_text">
				<?= $news->fullText ? $news->fullText : $news->previewText ?>
			</div>
			<div class="date text-left"><?= \Yii::$app->formatter->asDate($news->datePublish, "dd.MM.Y") ?></div>
		</div>
	</div>
	
	<div class="text-left pt-20">
		<a href="/">К списку новостей</a>
	</div>
</div>