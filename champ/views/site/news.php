<?php
/**
 * @var \yii\web\View              $this
 * @var \common\models\AssocNews $news
 */

use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\bootstrap\Html;

?>

<h2><?= $this->context->pageTitle ?></h2>

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
		<a href="/"><?= \Yii::t('app', 'К списку новостей') ?></a>
	</div>
</div>